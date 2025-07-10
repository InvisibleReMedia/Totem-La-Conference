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

-- Listage de la structure de la procédure moju. addQuestionResponse
DELIMITER //
CREATE PROCEDURE `addQuestionResponse`(
	IN `id_user` MEDIUMINT,
	IN `id_event` MEDIUMINT,
	IN `question` VARCHAR(256),
	IN `response1` VARCHAR(256),
	IN `response2` VARCHAR(256),
	IN `response3` VARCHAR(256),
	IN `source` INT
)
BEGIN
	DECLARE id_question MEDIUMINT;
	SELECT retAddQuestion(id_user, id_event, question, `source`) INTO @id_question;
	INSERT INTO questionresponses(question, id_event, response1, response2, response3)
	VALUES (@id_question,
			  id_event,
			  response1,
			  response2,
			  response3);

END//
DELIMITER ;

-- Listage de la structure de la procédure moju. answerQuestion
DELIMITER //
CREATE PROCEDURE `answerQuestion`(
	IN `id_user` MEDIUMINT,
	IN `id_event` MEDIUMINT,
	IN `question` MEDIUMINT,
	IN `id_response` MEDIUMINT,
	IN `responseNumber` MEDIUMINT,
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
  						FROM evqust AS e
  						WHERE e.id_question = question)) THEN
  	SET @curQuestion = question;
  	UPDATE questions SET readOnly = true WHERE id = @curQuestion;
  	-- si cette question est déjà dans l'événement
  	IF (SELECT EXISTS(SELECT ev.id
						FROM evqust AS ev
						WHERE ev.id_question = question AND ev.id_event = id_event
						AND ((ev.id_user = id_user AND (SELECT JSON_EXTRACT(e.infos, "$.duplicateQuestionType") = "perUser"
													FROM events AS e WHERE e.id = id_event)) OR 
							  (ev.responseNumber = responseNumber AND ev.id_response = id_response AND (SELECT IFNULL(JSON_EXTRACT(e.infos, "$.duplicateQuestionType"),"perEvent") = "perEvent"
							  	FROM events AS e WHERE e.id = id_event))))) THEN
	  	SET @insert_review = 0;
	ELSE
		SET @insert_review = 1;
	END IF;
ELSE
	SET @curQuestion = question;
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
							 id_response,
							 responseNumber,
		           		 id_event,
		           		 id_user,
		           		 `date`,
		           		 `source`,
				   		 `likes`,
				   		 moderation,
		           		 suppressed)
	VALUES (@curQuestion,
			 id_response,
			 responseNumber,
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
	VALUES (@curDate, @id_evqust, id_user, 'QUESTION', 'Ajout d\'une réponse à une question', JSON_OBJECT('question', @curQuestion, 'response', responseNumber, 'source', `source`));
END IF;
END//
DELIMITER ;

-- Listage de la structure de la table moju. questionresponses
CREATE TABLE IF NOT EXISTS `questionresponses` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `id_event` mediumint NOT NULL,
  `question` mediumint NOT NULL DEFAULT '0',
  `response1` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `response2` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `response3` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la fonction moju. retAddQuestion
DELIMITER //
CREATE FUNCTION `retAddQuestion`(
	`id_user` MEDIUMINT,
	`id_event` MEDIUMINT,
	`question` VARCHAR(256),
	`source` INT
) RETURNS mediumint
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
RETURN @curQuestion;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
