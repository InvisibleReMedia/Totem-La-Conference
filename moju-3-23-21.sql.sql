-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           8.0.21 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Listage de la structure de la procédure moju. addLikes
DROP PROCEDURE IF EXISTS `addLikes`;
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
		DECLARE permissions INT DEFAULT 0;
		DECLARE insert_review TINYINT(1);
		DECLARE insert_likes MEDIUMINT;
		DECLARE total_likes MEDIUMINT;

		SET @multiple_likes = 0;
		SELECT JSON_EXTRACT(e.infos, "$.multipleLikes")
		FROM events AS e
		WHERE e.id = id_event
		INTO @multiple_likes;

	   SELECT u.permissions
   	FROM users AS u
    	WHERE u.id = id_user
    	INTO @permissions;
		SET @insert_likes = newLikes;
		SET @insert_review = 0;
		SET @curDate = NOW();
		-- s'il existe déjà des likes pour cet utilisateur
		IF (SELECT EXISTS(SELECT l.likes
						  FROM likes AS l
						  WHERE l.id_evqust = id_evqust AND l.id_user = id_user)) THEN
			IF @multiple_likes = 1 OR (@permissions & 2) = 2 OR (@permissions & 8) = 8 THEN
				UPDATE likes AS l
				SET l.likes = l.likes + newLikes
				WHERE l.id_evqust = id_evqust AND l.id_user = id_user;
				SET @insert_review = 1;
			END IF;
		ELSE
			SET @insert_review = 1;
			IF @multiple_likes = 1 OR (@permissions & 2) = 2 OR (@permissions & 8) = 8 THEN
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
DROP PROCEDURE IF EXISTS `addQuestion`;
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
  						WHERE e.id_question = q.id
  						AND q.content = question)) THEN
    SELECT id
    FROM questions
    WHERE content = question
    INTO @curQuestion;
  	UPDATE questions SET readOnly = true WHERE id = @curQuestion;
  	-- si cette question est déjà dans l'événement
  	IF (SELECT EXISTS(SELECT ev.id
						FROM evqust AS ev, questions AS q
						WHERE ev.id_question = q.id AND q.content = question AND ev.id_event = id_event
						AND ((ev.id_user = id_user AND (SELECT JSON_EXTRACT(e.infos, "$.duplicateQuestionType") = "perUser"
													FROM events AS e WHERE e.id = id_event)) OR 
							  (SELECT IFNULL(JSON_EXTRACT(e.infos, "$.duplicateQuestionType"),"perEvent") = "perEvent"
							  	FROM events AS e WHERE e.id = id_event)))) THEN
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
	SELECT JSON_EXTRACT(e.infos, "$.automaticModeration")
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
DROP PROCEDURE IF EXISTS `closeQuestion`;
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
    IF @suppressed = 0 AND ((@permissions & 2) = 2 OR (@permissions & 8) = 8) THEN
		UPDATE evqust
		SET suppressed = 1
		WHERE id = id_evqust;
        INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'SUPPRESSED', 'Suppression d\'une question', JSON_OBJECT('question', @id_question));
    END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure moju. moderateQuestion
DROP PROCEDURE IF EXISTS `moderateQuestion`;
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
    IF (@permissions & 8) = 8 THEN
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

-- Listage de la structure de la procédure moju. modifyQuestion
DROP PROCEDURE IF EXISTS `modifyQuestion`;
DELIMITER //
CREATE PROCEDURE `modifyQuestion`(
	IN `id_user` MEDIUMINT,
	IN `id_event` MEDIUMINT,
	IN `id_evqust` MEDIUMINT,
	IN `question` VARCHAR(256)
)
BEGIN
DECLARE permissions TINYINT(1) DEFAULT 0;
DECLARE curEvent MEDIUMINT;
DECLARE curQuestion MEDIUMINT;
DECLARE curDate DATETIME;
DECLARE auto_moderate TINYINT(1);
DECLARE insert_review TINYINT(1);

SELECT u.permissions
FROM users AS u
WHERE u.id = id_user
INTO @permissions;
IF (@permissions & 8) = 8 THEN


	-- si la question existe déjà
	IF (SELECT EXISTS(SELECT q.content
  							FROM questions AS q
  							WHERE q.content = question)) THEN
  		SELECT id
		FROM questions
      WHERE content = question
      INTO @curQuestion;
  		UPDATE questions SET readOnly = true WHERE id = @curQuestion;
  		-- si cette question est déjà dans l'événement
  		IF (SELECT EXISTS(SELECT ev.id
							FROM evqust AS ev, questions AS q
							WHERE ev.id_question = q.id AND q.content = question AND ev.id_event = id_event
							AND ((ev.id_user = id_user AND (SELECT JSON_EXTRACT(e.infos, "$.duplicateQuestionType") = "perUser"
														FROM events AS e WHERE e.id = id_event)) OR 
								  (SELECT IFNULL(JSON_EXTRACT(e.infos, "$.duplicateQuestionType"),"perEvent") = "perEvent"
								  	FROM events AS e WHERE e.id = id_event)))) THEN
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
		UPDATE evqust
		SET id_question = @curQuestion, id_user = id_user, `date` = @curDate
		WHERE id = id_evqust;
		-- Mise à jour de la table REVIEW
		-- Ajoute un enregistrement dans la table
		INSERT INTO review(`date`, id_evqust, id_user, type, description, infos)
		VALUES (@curDate, id_evqust, id_user, 'UPDATE', 'Mise à jour d\'une question', JSON_OBJECT('question', @curQuestion));
	END IF;
END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure moju. pushQuestion
DROP PROCEDURE IF EXISTS `pushQuestion`;
DELIMITER //
CREATE PROCEDURE `pushQuestion`(
	IN `id_evqust` MEDIUMINT,
	IN `id_user` MEDIUMINT
)
BEGIN
    DECLARE permissions INT DEFAULT 0;
    DECLARE id_question MEDIUMINT;
    SELECT u.permissions
    FROM users AS u
    WHERE u.id = id_user
    INTO @permissions;
    IF (@permissions & 8) = 8 THEN
		UPDATE evqust
		SET `source` = 1
		WHERE id = id_evqust;
      INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'PUSH', 'Push d\'une question', JSON_OBJECT('question', @id_question));
    END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure moju. selectQuestion
DROP PROCEDURE IF EXISTS `selectQuestion`;
DELIMITER //
CREATE PROCEDURE `selectQuestion`(
	IN `id_evqust` MEDIUMINT,
	IN `id_user` MEDIUMINT
)
BEGIN
	DECLARE selected TINYINT(1) DEFAULT 0;
    DECLARE permissions INT DEFAULT 0;
    DECLARE id_question MEDIUMINT;
    SELECT e.selected, e.id_question
    FROM evqust AS e
    WHERE e.id = id_evqust
    INTO @selected,
    	 @id_question;
    SELECT u.permissions
    FROM users AS u
    WHERE u.id = id_user
    INTO @permissions;
    IF (@permissions & 2) = 2 THEN
      IF @selected = 1 THEN
			UPDATE evqust
			SET selected = 0
			WHERE id = id_evqust;
         INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'SELECT', 'Désélection d\'une question', JSON_OBJECT('question', @id_question, 'selected', 0));
		ELSE
			UPDATE evqust
			SET selected = 1
			WHERE id = id_evqust;
         INSERT INTO review(id_evqust, id_user, `date`, type, description, infos) VALUES (id_evqust, id_user, NOW(), 'SELECT', 'Sélection d\'une question', JSON_OBJECT('question', @id_question, 'selected', 1));
		END IF;
    END IF;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
