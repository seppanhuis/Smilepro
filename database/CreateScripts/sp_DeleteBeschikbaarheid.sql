-- =============================================
-- Author: SmilePro Development Team
-- Create date: 2026-01-15
-- Description: Stored procedure voor het verwijderen van beschikbaarheid
--             Controleert of beschikbaarheid correct is gekoppeld
-- =============================================

DROP PROCEDURE IF EXISTS sp_DeleteBeschikbaarheid;

DELIMITER $$

CREATE PROCEDURE sp_DeleteBeschikbaarheid(
    IN p_beschikbaarheid_id INT,
    IN p_medewerker_id INT,
    OUT p_success BOOLEAN,
    OUT p_error_message VARCHAR(500)
)
BEGIN
    -- Declareer variabelen
    DECLARE v_beschikbaarheid_count INT DEFAULT 0;
    DECLARE v_linked_medewerker_id INT;
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
    -- VALIDATIE: Controleer of beschikbaarheid bestaat
    -- =============================================
    SELECT COUNT(*) INTO v_beschikbaarheid_count
    FROM beschikbaarheden
    WHERE id = p_beschikbaarheid_id;

    IF v_beschikbaarheid_count = 0 THEN
        SET p_error_message = 'Beschikbaarheid niet gevonden';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer of beschikbaarheid bij juiste medewerker hoort
    -- Gebruik JOIN om medewerker informatie op te halen
    -- =============================================
    IF v_continue THEN
        SELECT b.medewerker_id INTO v_linked_medewerker_id
        FROM beschikbaarheden b
        INNER JOIN medewerkers m ON b.medewerker_id = m.id
        WHERE b.id = p_beschikbaarheid_id;

        IF v_linked_medewerker_id != p_medewerker_id THEN
            SET p_error_message = 'Beschikbaarheid kan niet worden verwijderd';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- DELETE: Verwijder beschikbaarheid
    -- =============================================
    IF v_continue THEN
        DELETE FROM beschikbaarheden
        WHERE id = p_beschikbaarheid_id;

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
