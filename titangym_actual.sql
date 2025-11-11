-- =========================================================
--  TITANGYM - Dump compatible MySQL 5.7 (XAMPP)
--  Shrek Dev Edition 🧅
-- =========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Crea la BD si no existe
CREATE DATABASE IF NOT EXISTS titangym
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE titangym;

-- ---------------------------------------------------------
-- Eliminar tablas en orden seguro (por FK)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS enrolls_to;
DROP TABLE IF EXISTS health_status;
DROP TABLE IF EXISTS address;
DROP TABLE IF EXISTS plan;
DROP TABLE IF EXISTS timetable;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS address_backup;
DROP TABLE IF EXISTS users_backup;

-- ---------------------------------------------------------
-- Tabla: users
-- ---------------------------------------------------------
CREATE TABLE users (
  userid       VARCHAR(20) NOT NULL,
  username     VARCHAR(40) NOT NULL,
  gender       VARCHAR(8)  NOT NULL,
  mobile       VARCHAR(20) NOT NULL,
  email        VARCHAR(100) DEFAULT NULL,
  dob          VARCHAR(10) NOT NULL,
  joining_date VARCHAR(10) NOT NULL,
  PRIMARY KEY (userid),
  UNIQUE KEY email (email)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: users
INSERT INTO users (userid, username, gender, mobile, email, dob, joining_date) VALUES
('1529336794', 'Juana Maren',    'Mujer',  '3166549781', 'jmaren@cweb.com',  '1994-06-15', '2020-01-30'),
('1580392920', 'Roberto Forerr', 'Hombre', '3116759143', 'rforero@cweb.com', '1996-06-11', '2020-01-30'),
('1580393244', 'Johana Camposs', 'Mujer',  '3016749413', 'jcampos@cweb.com', '1993-02-20', '2020-01-30'),
('1760152862', 'Alejandro',      'Hombre', '64230086',   NULL,               '2004-09-24', '2025-10-23'),
('1760157394', 'Diegos',         'Hombre', '76743577',   NULL,               '1991-10-15', '2025-10-23'),
('1760199452', 'Sandro',         'Hombre', '67223456',   NULL,               '2002-02-16', '2025-10-16');

-- ---------------------------------------------------------
-- Tabla: admin
-- ---------------------------------------------------------
CREATE TABLE admin (
  username  VARCHAR(20) NOT NULL,
  pass_key  VARCHAR(100) NOT NULL,
  securekey VARCHAR(100) NOT NULL,
  Full_name VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (username)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: admin
INSERT INTO admin (username, pass_key, securekey, Full_name) VALUES
('configuroweb', '1234abcd..', '1234abcd..', 'ConfiguroWeb');

-- ---------------------------------------------------------
-- Tabla: address
-- ---------------------------------------------------------
CREATE TABLE address (
  id         VARCHAR(20) NOT NULL,
  streetName VARCHAR(40) DEFAULT NULL,
  state      VARCHAR(15) DEFAULT NULL,
  city       VARCHAR(15) DEFAULT NULL,
  zipcode    VARCHAR(20) DEFAULT NULL,
  KEY idx_address_id (id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: address (1 fila por usuario, sin duplicados)
INSERT INTO address (id, streetName, state, city, zipcode) VALUES
('1529336794', 'Calle 23 Carrera 54', NULL, NULL, NULL),
('1580392920', 'Calle 92 Carrera 7',  NULL, NULL, NULL),
('1580393244', 'Calle 12 N 34-23',    '',   '',   ''),
('1760152862', 'Santa Barbara',       NULL, NULL, NULL),
('1760157394', '',                    NULL, NULL, NULL),
('1760199452', NULL,                  NULL, NULL, NULL);

-- ---------------------------------------------------------
-- Tabla: health_status
-- ---------------------------------------------------------
CREATE TABLE health_status (
  hid     INT NOT NULL AUTO_INCREMENT,
  calorie VARCHAR(8)  DEFAULT NULL,
  height  VARCHAR(8)  DEFAULT NULL,
  weight  VARCHAR(8)  DEFAULT NULL,
  fat     VARCHAR(8)  DEFAULT NULL,
  remarks VARCHAR(200) DEFAULT NULL,
  uid     VARCHAR(20) NOT NULL,
  PRIMARY KEY (hid),
  KEY idx_health_uid (uid)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: health_status (una fila por usuario)
INSERT INTO health_status (hid, calorie, height, weight, fat, remarks, uid) VALUES
(1, NULL, NULL, NULL, NULL, NULL, '1529336794'),
(2, NULL, NULL, NULL, NULL, NULL, '1580392920'),
(3, NULL, NULL, NULL, NULL, NULL, '1580393244'),
(4, NULL, NULL, NULL, NULL, NULL, '1760152862'),
(5, NULL, NULL, NULL, NULL, NULL, '1760157394'),
(6, NULL, NULL, NULL, NULL, NULL, '1760199452');

-- ---------------------------------------------------------
-- Tabla: plan
-- ---------------------------------------------------------
CREATE TABLE plan (
  pid        VARCHAR(8)  NOT NULL,
  planName   VARCHAR(20) NOT NULL,
  description VARCHAR(200) NOT NULL,
  validity   VARCHAR(20) NOT NULL,
  amount     INT NOT NULL,
  active     VARCHAR(10) DEFAULT 'yes',
  PRIMARY KEY (pid)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: plan (texto corregido a UTF-8 legible)
INSERT INTO plan (pid, planName, description, validity, amount, active) VALUES
('AQPHJN', 'Bonbardyli',       'Eres un ratón en una jaula',                                                                        '4',  233,   'yes'),
('CXYFBV', 'Funcional Trap',   'Rutinas de cardio basadas en circuitos prolongados, con 6 niveles de esfuerzo durante 1 hora.',     '7',  85000, 'yes'),
('INPBSY', 'Machosogro',       'Cagastebro',                                                                                          '6',  1000,  'no'),
('KMARLQ', 'Funcional',        'Movimientos con peso corporal en circuitos de 10 min con 1 min de descanso.',                        '1',  65000, 'no'),
('NIADFE', 'KetoGato',         'Consta en dolor máximo',                                                                              '4',  700,   'no'),
('OIFVSP', 'Alfa',             'Compas de hierro',                                                                                    '5',  345,   'no'),
('POQKJC', 'Plan Mensual',     'Una suscripción mensual que desbloquea toda la app y soporte del entrenador en el chat.',            '1',  60000, 'yes');

-- ---------------------------------------------------------
-- Tabla: timetable
-- ---------------------------------------------------------
CREATE TABLE timetable (
  tid  INT NOT NULL AUTO_INCREMENT,
  tname VARCHAR(45) DEFAULT NULL,
  day1  VARCHAR(200) DEFAULT NULL,
  day2  VARCHAR(200) DEFAULT NULL,
  day3  VARCHAR(200) DEFAULT NULL,
  day4  VARCHAR(200) DEFAULT NULL,
  day5  VARCHAR(200) DEFAULT NULL,
  day6  VARCHAR(200) DEFAULT NULL,
  PRIMARY KEY (tid)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: timetable
INSERT INTO timetable (tid, tname, day1, day2, day3, day4, day5, day6) VALUES
(4, 'Arobis',              'Sentadilla',   'Peso',         'Militar',          'Hombros',          'Poderes',        'Remo'),
(5, 'Tralaleo tralala',    'Abdominales',  'Piernas',      'Peso muerto',      'Espalda',          'Puede ser',      'Algo');

-- ---------------------------------------------------------
-- Tabla: enrolls_to
-- ---------------------------------------------------------
CREATE TABLE enrolls_to (
  et_id     INT NOT NULL AUTO_INCREMENT,
  pid       VARCHAR(8)  NOT NULL,
  uid       VARCHAR(20) NOT NULL,
  paid_date VARCHAR(15) DEFAULT NULL,
  expire    VARCHAR(15) DEFAULT NULL,
  renewal   VARCHAR(15) DEFAULT NULL,
  PRIMARY KEY (et_id),
  KEY idx_enroll_uid (uid),
  KEY idx_enroll_pid (pid)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- Datos: enrolls_to (respetando tus estados yes/no)
INSERT INTO enrolls_to (et_id, pid, uid, paid_date, expire, renewal) VALUES
(1,  'POQKJC', '1529336794', '2018-06-18', '2018-07-18', 'no'),
(2,  'POQKJC', '1529336794', '2020-01-30', '2020-03-01', 'no'),
(3,  'POQKJC', '1580392920', '2020-01-30', '2020-03-01', 'no'),
(4,  'POQKJC', '1580393244', '2020-01-30', '2020-03-01', 'no'),
(5,  'POQKJC', '1760152862', '2025-10-10', '2025-11-10', 'no'),
(6,  'CXYFBV', '1760157394', '2025-10-10', '2026-02-10', 'no'),
(7,  'POQKJC', '1529336794', '2025-10-10', '2025-11-10', 'no'),
(9,  'CXYFBV', '1580392920', '2025-10-11', '2026-02-11', 'no'),
(10, 'POQKJC', '1580393244', '2025-10-11', '2025-11-11', 'no'),
(11, 'POQKJC', '1760157394', '2025-10-11', '2025-11-11', 'no'),
(12, 'CXYFBV', '1580392920', '2025-10-11', '2026-02-11', 'no'),
(14, 'CXYFBV', '1760152862', '2025-10-11', '2026-02-11', 'yes'),
(17, 'POQKJC', '1760199452', '2025-10-11', '2025-11-11', 'no'),
(18, 'CXYFBV', '1760199452', '2025-10-11', '2026-02-11', 'no'),
(19, 'CXYFBV', '1529336794', '2025-10-11', '2026-02-11', 'yes'),
(20, 'AQPHJN', '1580392920', '2025-10-11', '2026-02-11', 'yes'),
(21, 'CXYFBV', '1580393244', '2025-10-11', '2026-05-11', 'yes'),
(22, 'CXYFBV', '1760157394', '2025-10-11', '2026-05-11', 'yes'),
(23, 'AQPHJN', '1760199452', '2025-10-11', '2026-02-11', 'yes');

-- ---------------------------------------------------------
-- Backups (opcional; normalizados a 5.7)
-- ---------------------------------------------------------
CREATE TABLE address_backup (
  id         VARCHAR(20) NOT NULL,
  streetName VARCHAR(40) NOT NULL,
  state      VARCHAR(15) NOT NULL,
  city       VARCHAR(15) NOT NULL,
  zipcode    VARCHAR(20) NOT NULL
) ENGINE=MyISAM
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO address_backup (id, streetName, state, city, zipcode) VALUES
('1529336794', 'Calle 23 Carrera 54j', '', '', ''),
('1580392920', 'Calle 92 Carrera 7',   '', '', ''),
('1580393244', 'Calle 12 N 34-23',     '', '', ''),
('1760152862', '',                      '', '', '');

CREATE TABLE users_backup (
  userid       VARCHAR(20) NOT NULL,
  username     VARCHAR(40) NOT NULL,
  gender       VARCHAR(8)  NOT NULL,
  mobile       VARCHAR(20) NOT NULL,
  email        VARCHAR(150) DEFAULT NULL,
  dob          VARCHAR(10) NOT NULL,
  joining_date VARCHAR(10) NOT NULL
) ENGINE=MyISAM
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

INSERT INTO users_backup (userid, username, gender, mobile, email, dob, joining_date) VALUES
('1529336794', 'Juana Maren',   'Mujer',  '3166549781', 'jmaren@cweb.com', '1994-06-15', '2020-01-30'),
('1580392920', 'Roberto Forer', 'Hombre', '3116759143', 'rforero@cweb.com','1996-06-11', '2020-01-30'),
('1580393244', 'Johana Camposs','Mujer',  '3016749413', 'jcampos@cweb.com','1993-02-20', '2020-01-30'),
('1760152862', 'Alejandrito',   'Hombre', '64230086',   NULL,              '2004-09-24', '2025-10-23'),
('1760157394', 'Diegote',       'Hombre', '76743577',   NULL,              '1991-10-15', '2025-10-23'),
('1760160171', 'Alancito',      'Hombre', '3243423e4',  NULL,              '2007-09-20', '2025-10-30');

-- ---------------------------------------------------------
-- Foreign Keys
-- ---------------------------------------------------------
ALTER TABLE address
  ADD CONSTRAINT fk_address_user
    FOREIGN KEY (id) REFERENCES users(userid)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE health_status
  ADD CONSTRAINT fk_health_user
    FOREIGN KEY (uid) REFERENCES users(userid)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE enrolls_to
  ADD CONSTRAINT fk_enroll_user
    FOREIGN KEY (uid) REFERENCES users(userid)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  ADD CONSTRAINT fk_enroll_plan
    FOREIGN KEY (pid) REFERENCES plan(pid)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

-- ---------------------------------------------------------
-- Triggers (formato compatible 5.7)
-- Crea address + health_status en INSERT de users
-- ---------------------------------------------------------
DROP TRIGGER IF EXISTS trg_users_after_insert_address;
DELIMITER //
CREATE TRIGGER trg_users_after_insert_address
AFTER INSERT ON users
FOR EACH ROW
BEGIN
  INSERT INTO address (id, streetName, state, city, zipcode)
  VALUES (NEW.userid, NULL, NULL, NULL, NULL);
END//
DELIMITER ;

DROP TRIGGER IF EXISTS trg_users_after_insert_health;
DELIMITER //
CREATE TRIGGER trg_users_after_insert_health
AFTER INSERT ON users
FOR EACH ROW
BEGIN
  INSERT INTO health_status (uid, calorie, height, weight, fat, remarks)
  VALUES (NEW.userid, NULL, NULL, NULL, NULL, NULL);
END//
DELIMITER ;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
--  FIN - titangym (MySQL 5.7 compatible)
-- =========================================================