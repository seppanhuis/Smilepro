-- =============================================
-- Author: SmilePro Development Team
-- Create date: 2026-01-15
-- Description: Stored procedure voor het wijzigen van een bestaande medewerker
--             Bevat validatie, joins en transactie management (MySQL versie)
-- =============================================

DROP PROCEDURE IF EXISTS sp_UpdateMedewerker;

DELIMITER $$

CREATE PROCEDURE sp_UpdateMedewerker(
    IN p_medewerker_id INT,
    IN p_email VARCHAR(255),
    IN p_voornaam VARCHAR(100),
    IN p_tussenvoegsel VARCHAR(50),
    IN p_achternaam VARCHAR(100),
    IN p_geboortedatum DATE,
    IN p_medewerker_type VARCHAR(50),
    IN p_specialisatie VARCHAR(255),
    IN p_is_actief BOOLEAN,
    IN p_opmerking TEXT,
    OUT p_success BOOLEAN,
    OUT p_error_message VARCHAR(500)
)
BEGIN
    -- Declareer variabelen
    DECLARE v_persoon_id INT;
    DECLARE v_user_id INT;
    DECLARE v_email_count INT DEFAULT 0;
    DECLARE v_medewerker_count INT DEFAULT 0;
    DECLARE v_current_email VARCHAR(255);
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
    -- VALIDATIE: Haal persoon_id en user_id op via JOIN
    -- =============================================
    IF v_continue THEN
        SELECT
            m.persoon_id,
            p.gebruiker_id,
            u.email
        INTO
            v_persoon_id,
            v_user_id,
            v_current_email
        FROM medewerkers m
        INNER JOIN personen p ON m.persoon_id = p.id
        INNER JOIN users u ON p.gebruiker_id = u.id
        WHERE m.id = p_medewerker_id;

        IF v_persoon_id IS NULL THEN
            SET p_error_message = 'Persoon niet gevonden voor deze medewerker';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer of email al in gebruik is (behalve door deze user)
    -- =============================================
    IF v_continue AND p_email IS NOT NULL AND p_email != v_current_email THEN
        SELECT COUNT(*) INTO v_email_count
        FROM users
        WHERE email = p_email COLLATE utf8mb4_unicode_ci
        AND id != v_user_id;

        IF v_email_count > 0 THEN
            SET p_error_message = 'email al in gebruik';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer verplichte velden
    -- =============================================
    IF v_continue AND (p_voornaam IS NULL OR p_voornaam = '') THEN
        SET p_error_message = 'Voornaam is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_achternaam IS NULL OR p_achternaam = '') THEN
        SET p_error_message = 'Achternaam is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_email IS NULL OR p_email = '') THEN
        SET p_error_message = 'Email is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND p_geboortedatum > CURDATE() THEN
        SET p_error_message = 'Geboortedatum kan niet in de toekomst liggen';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_medewerker_type IS NULL OR p_medewerker_type = '') THEN
        SET p_error_message = 'Medewerker type is verplicht';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- UPDATE: User tabel
    -- =============================================
    IF v_continue THEN
        UPDATE users
        SET
            email = p_email,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = v_user_id;
    END IF;

    -- =============================================
    -- UPDATE: Personen tabel
    -- =============================================
    IF v_continue THEN
        UPDATE personen
        SET
            voornaam = p_voornaam,
            tussenvoegsel = p_tussenvoegsel,
            achternaam = p_achternaam,
            geboortedatum = p_geboortedatum,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = v_persoon_id;
    END IF;

    -- =============================================
    -- UPDATE: Medewerkers tabel
    -- =============================================
    IF v_continue THEN
        UPDATE medewerkers
        SET
            medewerker_type = p_medewerker_type,
            specialisatie = p_specialisatie,
            is_actief = p_is_actief,
            opmerking = p_opmerking,
            updated_at = CURRENT_TIMESTAMP
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
