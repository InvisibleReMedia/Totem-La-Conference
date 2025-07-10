USE `totem`;

-- Listage de la structure de la procédure moju. addLikes
DELIMITER //
CREATE PROCEDURE `addLikes`(
	IN `id_user` MEDIUMINT,
	IN `id_event` MEDIUMINT,
	IN `id_evqust` MEDIUMINT,
	IN `newLikes` MEDIUMINT
)
    NO SQL
BEGIN
		DECLARE curDate DATETIME;
		DECLARE multiple_likes TINYINT(1);
		DECLARE insert_review TINYINT(1);
		DECLARE insert_likes MEDIUMINT;
		DECLARE total_likes MEDIUMINT;

		SET @multiple_likes = 0;
		SELECT e.infos->"$.multipleLikes"
		FROM events AS e
		WHERE e.id = id_event
		INTO @multiple_likes;

		SET @insert_likes = newLikes;
		SET @insert_review = 0;
		SET @curDate = NOW();
		-- s'il existe déjà des likes pour cet utilisateur
		IF (SELECT EXISTS(SELECT l.likes
						  FROM likes AS l
						  WHERE l.id_evqust = id_evqust AND l.id_user = id_user)) THEN
			IF @multiple_likes = 1 THEN
				UPDATE likes AS l
				SET l.likes = l.likes + newLikes
				WHERE l.id_evqust = id_evqust AND l.id_user = id_user;
				SET @insert_review = 1;
			END IF;
		ELSE
			SET @insert_review = 1;
			IF @multiple_likes = 1 THEN
				INSERT INTO likes(id_evqust,id_user,likes)
				VALUES (id_evqust, id_user, newLikes);
			ELSE
				INSERT INTO likes(id_evqust, id_user, likes)
				VALUES (id_evqust, id_user, 1);
         	SET @insert_likes = 1;
			END IF;
		END IF;

		IF @insert_review = 1 THEN
            -- Mise à jour de la table REVIEW
			UPDATE evqust AS e
			SET e.likes = (SELECT SUM(likes) FROM likes AS l WHERE l.id_evqust = id_evqust)
			WHERE e.id = id_evqust;
			SELECT e.likes
			FROM evqust AS e
			WHERE e.id = id_evqust
			INTO @total_likes;
			-- Ajoute un enregistrement dans la table
			INSERT INTO review(date, id_evqust, id_user, type, description, infos)
			VALUES (@curDate, id_evqust, id_user, 'LIKES', 'Ajout de like', JSON_OBJECT('likes', @insert_likes, 'totalLikes', @total_likes));
		END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure moju. addQuestion
DELIMITER //
CREATE PROCEDURE `addQuestion`(
	IN `id_user` MEDIUMINT,
	IN `id_event` MEDIUMINT,
	IN `question` VARCHAR(256),
	IN `source` SMALLINT
)
BEGIN
DECLARE curEvent MEDIUMINT;
DECLARE curQuestion MEDIUMINT;
DECLARE curDate DATETIME;
DECLARE id_evqust MEDIUMINT;
DECLARE auto_moderate TINYINT(1);
DECLARE insert_review TINYINT(1);


-- si la question existe déjà
IF (SELECT EXISTS(SELECT e.id_event
                  FROM evqust AS e,
                       questions AS q
                  WHERE e.id_question = q.id AND q.content = question
                  LIMIT 1)) THEN
    SELECT id
    FROM questions
    WHERE content = question
    INTO @curQuestion;
  	UPDATE questions SET readOnly = true WHERE id = @curQuestion;
  	-- si cette question est déjà dans l'événement
  	IF (SELECT EXISTS(SELECT e.id_event
  							FROM evqust AS e,
  								  questions AS q
  							WHERE e.id_event = id_event AND e.id_question = q.id
  							AND q.content = question)) THEN
	  	SET @insert_review = 0;
	ELSE
		SET @insert_review = 1;
	END IF;
ELSE
	INSERT INTO questions(content,
			              readOnly)
	VALUES (question,
		    false);
	SET @curQuestion = LAST_INSERT_ID();
	SET @insert_review = 1;
END IF;

IF @insert_review = 1 THEN
	SET @curDate = NOW();
	SELECT e.infos->"$.automaticModeration"
	FROM events AS e
	WHERE e.id = id_event
	INTO @auto_moderate;
	IF ISNULL(@auto_moderate) THEN
		SET @auto_moderate = 0;
	END IF;
	INSERT INTO evqust(id_question,
		           		 id_event,
		           		 id_user,
		           		 `date`,
		           		 `source`,
				   		 `likes`,
				   		 moderation,
		           		 suppressed)
	VALUES (@curQuestion,
		    id_event,
			 id_user,
	    	 @curDate,
	    	 `source`,
	    	 0,
	    	 @auto_moderate,
	    	 false);
	SET @id_evqust = LAST_INSERT_ID();
	-- Mise à jour de la table REVIEW
	-- Ajoute un enregistrement dans la table
	INSERT INTO review(`date`, id_evqust, id_user, type, description, infos)
	VALUES (@curDate, @id_evqust, id_user, 'QUESTION', 'Ajout d\'une question', JSON_OBJECT('question', @curQuestion, 'source', `source`));
END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure moju. closeQuestion
DELIMITER //
CREATE PROCEDURE `closeQuestion`(
	IN `id_evqust` MEDIUMINT,
	IN `id_user` MEDIUMINT
)
    NO SQL
BEGIN
	DECLARE suppressed TINYINT(1) DEFAULT 0;
    DECLARE permissions INT DEFAULT 0;
    DECLARE id_question MEDIUMINT;
    SELECT e.suppressed, e.id_question
    FROM evqust AS e
    WHERE e.id = id_evqust
    INTO @suppressed,
    	 @id_question;
    SELECT u.permissions
    FROM users AS u
    WHERE u.id = id_user
    INTO @permissions;
    IF @suppressed = 0 AND (@permissions & 2) = 2 THEN
		UPDATE evqust
		SET suppressed = 1
		WHERE id = id_evqust;
        INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'SUPPRESSED', 'Suppression d\'une question', JSON_OBJECT('question', @id_question));
    END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure moju. disconnect
DELIMITER //
CREATE PROCEDURE `disconnect`(
	IN `id_user` MEDIUMINT,
	IN `id_event` MEDIUMINT
)
BEGIN
	UPDATE sessions AS s SET date_end = NOW() WHERE s.id_user = id_user AND s.id_event = id_event;
END//
DELIMITER ;

-- Listage de la structure de la table moju. events
CREATE TABLE IF NOT EXISTS `events` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `address` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `infos` json NOT NULL,
  `suppressed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `label` (`title`,`date_start`,`date_end`,`address`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.events : ~3 rows (environ)
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`, `title`, `date_start`, `date_end`, `address`, `infos`, `suppressed`) VALUES
	(1, 'test', '2021-01-01 00:00:00', '2021-01-31 00:00:00', 'ici', '{}', 0),
	(2, 'test 2', '2021-02-01 00:00:00', '2021-02-28 00:00:00', 'ici', '{"multipleLikes": 1, "automaticModeration": 1}', 0),
	(3, 'test 3', '2021-04-10 10:00:00', '2021-04-10 18:00:00', 'En normandie', '{}', 0);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;

-- Listage de la structure de la table moju. evqust
CREATE TABLE IF NOT EXISTS `evqust` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `id_event` mediumint NOT NULL,
  `id_question` mediumint NOT NULL,
  `id_user` mediumint NOT NULL,
  `date` datetime NOT NULL,
  `source` smallint NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `moderation` tinyint(1) NOT NULL DEFAULT '0',
  `suppressed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_evqst` (`id_event`,`id_question`,`id_user`,`date`) USING BTREE,
  KEY `id_event` (`id_event`),
  KEY `id_question` (`id_question`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `evqust_ibfk_1` FOREIGN KEY (`id_event`) REFERENCES `events` (`id`),
  CONSTRAINT `evqust_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.evqust : ~0 rows (environ)
/*!40000 ALTER TABLE `evqust` DISABLE KEYS */;
/*!40000 ALTER TABLE `evqust` ENABLE KEYS */;

-- Listage de la structure de la table moju. likes
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_evqust` mediumint NOT NULL,
  `id_user` mediumint NOT NULL,
  `likes` int NOT NULL DEFAULT '0',
  `suppressed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_evqust` (`id_evqust`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.likes : ~0 rows (environ)
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;

-- Listage de la structure de la procédure moju. moderateQuestion
DELIMITER //
CREATE PROCEDURE `moderateQuestion`(
	IN `id_evqust` MEDIUMINT,
	IN `id_user` MEDIUMINT
)
BEGIN
	DECLARE moderated TINYINT(1) DEFAULT 0;
    DECLARE permissions INT DEFAULT 0;
    DECLARE id_question MEDIUMINT;
    SELECT e.moderation, e.id_question
    FROM evqust AS e
    WHERE e.id = id_evqust
    INTO @moderated,
    	 @id_question;
    SELECT u.permissions
    FROM users AS u
    WHERE u.id = id_user
    INTO @permissions;
    IF (@permissions & 4) = 4 THEN
      IF @moderated = 1 THEN
			UPDATE evqust
			SET moderation = 0
			WHERE id = id_evqust;
         INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'MODERATED', 'Modération d\'une question', JSON_OBJECT('question', @id_question, 'moderated', 0));
		ELSE
			UPDATE evqust
			SET moderation = 1
			WHERE id = id_evqust;
         INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'MODERATED', 'Modération d\'une question', JSON_OBJECT('question', @id_question, 'moderated', 1));
		END IF;
    END IF;
END//
DELIMITER ;

-- Listage de la structure de la table moju. questions
CREATE TABLE IF NOT EXISTS `questions` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `content` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `readOnly` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content` (`content`),
  FULLTEXT KEY `search` (`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.questions : ~0 rows (environ)
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;

-- Listage de la structure de la table moju. review
CREATE TABLE IF NOT EXISTS `review` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `id_user` mediumint NOT NULL,
  `id_evqust` mediumint NOT NULL,
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `infos` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_evqust` (`id_evqust`),
  KEY `ref` (`id_user`,`id_evqust`,`type`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_evqust`) REFERENCES `evqust` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.review : ~0 rows (environ)
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;

-- Listage de la structure de la table moju. sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session` varchar(256) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `id_user` mediumint NOT NULL,
  `id_event` mediumint NOT NULL,
  UNIQUE KEY `session` (`session`),
  KEY `id_user` (`id_user`),
  KEY `id_event` (`id_event`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`id_event`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.sessions : ~0 rows (environ)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Listage de la structure de la table moju. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `greetings` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `firstName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `role` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `permissions` smallint NOT NULL DEFAULT '1',
  `infos` json DEFAULT NULL,
  `suppressed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`firstName`,`lastName`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table moju.users : ~9 rows (environ)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `greetings`, `firstName`, `lastName`, `password`, `role`, `permissions`, `infos`, `suppressed`) VALUES
	(1, 'Mr.', 'Olivier', 'G.D.B.', 'pass', 'participant, animateur', 3, '{}', 0),
	(2, 'Mr.', 'Ronan', 'P.', 'pass', 'tous', 31, '{"id": 1}', 0),
	(5, 'Mr.', 'Olivier', 'C.', 'pass2', 'animateur', 2, '{}', 0),
	(6, 'Mr.', 'Olivier', 'A.', 'pass', 'admin', 8, '{}', 0),
	(8, 'Mr.', 'Olivier', 'M.', 'pass', 'participant, animateur', 3, '{"id": 1}', 0),
	(9, 'Mr.', 'Olivier', 'O.', 'pass', 'participant', 1, '{"key": 1}', 0),
	(11, 'Mr.', 'Mathhieu', 'ADOR', 'pass', 'participant', 1, '{}', 0),
	(12, 'Mr.', 'béta', 'testeur', 'pass', 'participant', 1, '{}', 0),
	(13, 'Mr.', 'Ronan', 'testeur', 'pass', 'participant', 1, '{}', 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Listage de la structure de la fonction moju. verifyUser
DELIMITER //
CREATE FUNCTION `verifyUser`(
	`firstName` VARCHAR(50),
	`lastName` VARCHAR(50),
	`id_event` INT
) RETURNS varchar(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
BEGIN
	DECLARE cur_date DATETIME;
	DECLARE res TINYINT(1) DEFAULT 0;
	DECLARE new_s VARCHAR(256);
	DECLARE id_user MEDIUMINT;
    IF (SELECT EXISTS(SELECT * FROM users AS u
        WHERE u.firstName = firstName AND u.lastName = lastName)) THEN
        SELECT id FROM users AS u
        WHERE u.firstName = firstName AND u.lastName = lastName
        INTO @id_user;
      SET @cur_date = NOW();
      SET @new_s = MD5(CONCAT("Bienvenue sur le site - user ", firstName, " ", lastName, " il est ", @cur_date));
    	INSERT INTO sessions(`session`, date_start, id_user, id_event) VALUES(@new_s, @cur_date, @id_user, id_event);
	   RETURN @new_s;
	 ELSE
	   RETURN '';
    END IF;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
