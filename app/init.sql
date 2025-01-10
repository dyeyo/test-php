CREATE DATABASE NombreDB_PrimerApellido;
USE NombreDB_PrimerApellido;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    gender ENUM('M','F') NOT NULL,
    birthday DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    state BOOLEAN  NULL DEFAULT true,
);


DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Backuop`()
BEGIN
    DECLARE table_name VARCHAR(255);
    SET table_name = CONCAT('users_copy_', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'));

    SET @create_query = CONCAT('CREATE TABLE ', table_name, ' LIKE users');
    PREPARE stmt FROM @create_query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    -- Insertar los datos de la tabla persona a la nueva tabla
    SET @insert_query = CONCAT('INSERT INTO ', table_name, ' SELECT * FROM users');
    PREPARE stmt FROM @insert_query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    -- Opcional: Puedes devolver el nombre de la nueva tabla si lo necesitas
    SELECT table_name AS 'New Table Created';
END$$
DELIMITER ;