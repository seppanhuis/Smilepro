DELIMITER $$
DROP PROCEDURE IF EXISTS Sp_GetAllPatiënten;
$$
CREATE PROCEDURE Sp_GetAllPatiënten()
BEGIN
    SELECT * FROM users WHERE rol_naam = 'patient';
END;
$$

DELIMITER ;
