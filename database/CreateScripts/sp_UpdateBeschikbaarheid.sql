-- =============================================
-- Author: SmilePro Development Team
-- Create date: 2026-01-15
-- Description: Stored procedure voor het wijzigen van beschikbaarheid
--             Bevat validatie voor overlappende tijden en transactie management
-- =============================================

DROP PROCEDURE IF EXISTS sp_UpdateBeschikbaarheid;

DELIMITER $$

CREATE PROCEDURE sp_UpdateBeschikbaarheid(
    IN p_beschikbaarheid_id INT,
    IN p_medewerker_id INT,
    IN p_datum_vanaf DATE,
    IN p_datum_tot_met DATE,
    IN p_tijd_vanaf TIME,
    IN p_tijd_tot_met TIME,
    IN p_status VARCHAR(50),
    IN p_is_actief BOOLEAN,
    IN p_opmerking TEXT,
    OUT p_success BOOLEAN,
    OUT p_error_message VARCHAR(500)
)
BEGIN
    -- Declareer variabelen
    DECLARE v_beschikbaarheid_count INT DEFAULT 0;
    DECLARE v_overlap_count INT DEFAULT 0;
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
    -- VALIDATIE: Controleer datums en tijden
    -- =============================================
    IF v_continue AND p_datum_vanaf > p_datum_tot_met THEN
        SET p_error_message = 'Ongeldige beschikbaarheid';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND p_tijd_vanaf >= p_tijd_tot_met THEN
        SET p_error_message = 'Ongeldige beschikbaarheid';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND p_datum_vanaf < CURDATE() THEN
        SET p_error_message = 'Ongeldige beschikbaarheid';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer op overlappende beschikbaarheid met JOIN
    -- Gebruik een JOIN om te controleren of er conflicten zijn
    -- =============================================
    IF v_continue THEN
        SELECT COUNT(*) INTO v_overlap_count
        FROM beschikbaarheden b
        INNER JOIN medewerkers m ON b.medewerker_id = m.id
        WHERE b.medewerker_id = p_medewerker_id
        AND b.id != p_beschikbaarheid_id
        AND b.is_actief = TRUE
        AND (
            -- Overlapping datums
            (p_datum_vanaf BETWEEN b.datum_vanaf AND b.datum_tot_met)
            OR (p_datum_tot_met BETWEEN b.datum_vanaf AND b.datum_tot_met)
            OR (b.datum_vanaf BETWEEN p_datum_vanaf AND p_datum_tot_met)
        )
        AND (
            -- Overlapping tijden
            (p_tijd_vanaf < b.tijd_tot_met AND p_tijd_tot_met > b.tijd_vanaf)
        );

        IF v_overlap_count > 0 THEN
            SET p_error_message = 'Ongeldige beschikbaarheid';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer verplichte velden
    -- =============================================
    IF v_continue AND p_medewerker_id IS NULL THEN
        SET p_error_message = 'Medewerker is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_status IS NULL OR p_status = '') THEN
        SET p_error_message = 'Status is verplicht';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- UPDATE: Beschikbaarheden tabel
    -- =============================================
    IF v_continue THEN
        UPDATE beschikbaarheden
        SET
            medewerker_id = p_medewerker_id,
            datum_vanaf = p_datum_vanaf,
            datum_tot_met = p_datum_tot_met,
            tijd_vanaf = p_tijd_vanaf,
            tijd_tot_met = p_tijd_tot_met,
            status = p_status,
            is_actief = p_is_actief,
            opmerking = p_opmerking,
            updated_at = CURRENT_TIMESTAMP
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
