START TRANSACTION;

CREATE DATABASE IF NOT EXISTS vacances;

USE vacances;

CREATE TABLE IF NOT EXISTS task (
  id int(10) NOT NULL AUTO_INCREMENT,
  task_name varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO `task` (`task_name`) VALUES
('Ranger ma chambre'),
('Faire la cuisine'),
('Aller faire des courses'),
('Terminer le brief'),
('Sortir courir');


COMMIT;