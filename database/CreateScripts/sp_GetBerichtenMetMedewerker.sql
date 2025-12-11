DROP PROCEDURE IF EXISTS sp_GetBerichtenMetMedewerker;
DELIMITER $$

CREATE PROCEDURE sp_GetBerichtenMetMedewerker(IN p_patient_id INT, IN p_medewerker_id INT)
BEGIN
    SELECT c.id, c.bericht, c.verzonden_datum, c.is_actief
    FROM communicaties c
    WHERE c.patient_id = p_patient_id
      AND c.medewerker_id = p_medewerker_id
    ORDER BY c.verzonden_datum ASC;
END //

DELIMITER ;

