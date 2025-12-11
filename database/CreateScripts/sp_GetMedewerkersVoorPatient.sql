DELIMITER $$

DROP PROCEDURE IF EXISTS sp_GetMedewerkersVoorPatient $$
CREATE PROCEDURE sp_GetMedewerkersVoorPatient(IN p_patient_id INT)
BEGIN
    SELECT DISTINCT
        m.id AS medewerker_id,
        CONCAT(p.voornaam, ' ', p.achternaam) AS medewerker_naam
    FROM communicaties c
    JOIN medewerkers m ON c.medewerker_id = m.id
    JOIN personen p ON m.persoon_id = p.id
    WHERE c.patient_id = p_patient_id;
END $$

DELIMITER ;
