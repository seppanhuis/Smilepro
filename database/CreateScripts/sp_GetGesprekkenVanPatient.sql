DROP PROCEDURE IF EXISTS sp_GetGesprekkenVanPatient;
DELIMITER $$

CREATE PROCEDURE sp_GetGesprekkenVanPatient(IN p_patient_id INT)
BEGIN
    SELECT DISTINCT u.id AS gebruiker_id, u.gebruikersnaam, u.rol_naam
    FROM communicaties c
    JOIN users u ON (u.id = c.afzender_id OR u.id = c.ontvanger_id)
    WHERE (c.afzender_id = p_patient_id OR c.ontvanger_id = p_patient_id)
      AND u.rol_naam IN ('tandarts','mondhygiënist')
      AND u.id != p_patient_id
      AND c.is_actief = 1;
END$$

DELIMITER ;
