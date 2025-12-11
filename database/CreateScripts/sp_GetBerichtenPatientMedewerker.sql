DROP PROCEDURE IF EXISTS sp_GetBerichtenPatientMedewerker;
DELIMITER $$

CREATE PROCEDURE sp_GetBerichtenPatientMedewerker(IN p_patient_id INT, IN p_medewerker_id INT)
BEGIN
    SELECT c.id, c.afzender_id, c.ontvanger_id, c.bericht, c.datum
    FROM communicaties c
    WHERE ((c.afzender_id = p_patient_id AND c.ontvanger_id = p_medewerker_id)
       OR  (c.afzender_id = p_medewerker_id AND c.ontvanger_id = p_patient_id))
      AND c.is_actief = 1
    ORDER BY c.datum ASC;
END$$

DELIMITER ;
