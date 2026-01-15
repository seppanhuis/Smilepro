-- =============================================
-- Author: SmilePro Development Team
-- Create date: 2026-01-15
-- Description: Stored procedure voor het verwijderen van een medewerker
--             Controleert of medewerker actief is en bevat transactie management
-- =============================================

DROP PROCEDURE IF EXISTS sp_DeleteMedewerker;

DELIMITER $$

CREATE PROCEDURE sp_DeleteMedewerker(
    IN p_medewerker_id INT,
    OUT p_success BOOLEAN,
    OUT p_error_message VARCHAR(500)
)
BEGIN
    -- Declareer variabelen
    DECLARE v_is_actief BOOLEAN;
    DECLARE v_medewerker_count INT DEFAULT 0;
    DECLARE v_afspraak_count INT DEFAULT 0;
    DECLARE v_continue BOOLEAN DEFAULT TRUE;
    DECLARE v_sql_error VARCHAR(1000);

    -- Declareer handler voor SQL exceptions
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            v_sql_error = MESSAGE_TEXT;
        ROLLBACK;
        SET p_error_message = CONCAT('Database fout: ', v_sql_error);
        SET p_success = FALSE;
    END;

    -- Initialiseer output parameters
    SET p_success = FALSE;
    SET p_error_message = NULL;

    -- Start transactie
    START TRANSACTION;

    -- =============================================
    -- VALIDATIE: Controleer of medewerker bestaat
    -- =============================================
    SELECT COUNT(*) INTO v_medewerker_count
    FROM medewerkers
    WHERE id = p_medewerker_id;

    IF v_medewerker_count = 0 THEN
        SET p_error_message = 'Medewerker niet gevonden';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer of medewerker inactief is
    -- =============================================
    IF v_continue THEN
        SELECT is_actief INTO v_is_actief
        FROM medewerkers
        WHERE id = p_medewerker_id;

        IF v_is_actief = FALSE THEN
            SET p_error_message = 'Kan geen inactieve medewerkers verwijderen';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer of medewerker gekoppelde afspraken heeft
    -- =============================================
    IF v_continue THEN
        SELECT COUNT(*) INTO v_afspraak_count
        FROM afspraken
        WHERE medewerker_id = p_medewerker_id;

        IF v_afspraak_count > 0 THEN
            SET p_error_message = 'Kan medewerker met bestaande afspraken niet verwijderen';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- DELETE: Verwijder medewerker (cascade gebeurt automatisch)
    -- =============================================
    IF v_continue THEN
        DELETE FROM medewerkers
        WHERE id = p_medewerker_id;

        SET p_success = TRUE;
    END IF;

    -- Commit transactie als alles succesvol is
    IF v_continue THEN
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;

END$$

DELIMITER ;
