DROP PROCEDURE IF EXISTS sp_GetGesprekkenVanPatient;
DELIMITER $$

CREATE PROCEDURE sp_GetGesprekkenVanPatient(IN p_patient_id INT)
BEGIN
    SELECT DISTINCT
        u.id AS medewerker_id,
        u.gebruikersnaam AS medewerker_naam,
        u.rol_naam,
        CONCAT(p.voornaam, ' ', IFNULL(p.tussenvoegsel, ''), ' ', p.achternaam) AS volledige_naam
    FROM communicaties c
    JOIN users u ON (u.id = c.afzender_id OR u.id = c.ontvanger_id)
    LEFT JOIN personen p ON p.gebruiker_id = u.id
    WHERE (c.afzender_id = p_patient_id OR c.ontvanger_id = p_patient_id)
      AND u.rol_naam IN ('tandarts','mondhygiënist')
      AND u.id != p_patient_id
      AND c.is_actief = 1;
END$$

DELIMITER ;
