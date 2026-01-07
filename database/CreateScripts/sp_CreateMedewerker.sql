-- =============================================
-- Author: SmilePro Development Team
-- Create date: 2026-01-06
-- Description: Stored procedure voor het aanmaken van een nieuwe medewerker
--             Bevat validatie en transactie management (MySQL versie)
-- =============================================

DROP PROCEDURE IF EXISTS sp_CreateMedewerker;

DELIMITER $$

CREATE PROCEDURE sp_CreateMedewerker(
    IN p_gebruikersnaam VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_password VARCHAR(255),
    IN p_rol_naam VARCHAR(50),
    IN p_voornaam VARCHAR(100),
    IN p_tussenvoegsel VARCHAR(50),
    IN p_achternaam VARCHAR(100),
    IN p_geboortedatum DATE,
    IN p_nummer VARCHAR(50),
    IN p_medewerker_type VARCHAR(50),
    IN p_specialisatie VARCHAR(255),
    IN p_opmerking TEXT,
    OUT p_medewerker_id INT,
    OUT p_error_message VARCHAR(500)
)
BEGIN
    -- Declareer variabelen
    DECLARE v_user_id INT;
    DECLARE v_persoon_id INT;
    DECLARE v_email_count INT DEFAULT 0;
    DECLARE v_nummer_count INT DEFAULT 0;
    DECLARE v_continue BOOLEAN DEFAULT TRUE;
    DECLARE v_sql_error VARCHAR(1000);

    -- Declareer handler voor SQL exceptions met betere error info
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            v_sql_error = MESSAGE_TEXT;
        ROLLBACK;
        SET p_error_message = CONCAT('Database fout: ', v_sql_error);
        SET p_medewerker_id = NULL;
    END;

    -- Initialiseer output parameters
    SET p_medewerker_id = NULL;
    SET p_error_message = NULL;

    -- Start transactie
    START TRANSACTION;

    -- =============================================
    -- VALIDATIE: Controleer of email al bestaat
    -- =============================================
    SELECT COUNT(*) INTO v_email_count
    FROM users
    WHERE email = p_email COLLATE utf8mb4_unicode_ci;

    IF v_email_count > 0 THEN
        SET p_error_message = 'Deze medewerker bestaat al';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer of medewerkernummer al bestaat
    -- =============================================
    IF v_continue THEN
        SELECT COUNT(*) INTO v_nummer_count
        FROM medewerkers
        WHERE nummer = p_nummer COLLATE utf8mb4_unicode_ci;

        IF v_nummer_count > 0 THEN
            SET p_error_message = 'Dit medewerkernummer bestaat al';
            SET v_continue = FALSE;
        END IF;
    END IF;

    -- =============================================
    -- VALIDATIE: Controleer verplichte velden
    -- =============================================
    IF v_continue AND (p_gebruikersnaam IS NULL OR p_gebruikersnaam = '') THEN
        SET p_error_message = 'Gebruikersnaam is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_email IS NULL OR p_email = '') THEN
        SET p_error_message = 'Email is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_voornaam IS NULL OR p_voornaam = '') THEN
        SET p_error_message = 'Voornaam is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND (p_achternaam IS NULL OR p_achternaam = '') THEN
        SET p_error_message = 'Achternaam is verplicht';
        SET v_continue = FALSE;
    END IF;

    IF v_continue AND p_medewerker_type NOT IN ('Assistent', 'Mondhygiënist', 'Tandarts', 'Praktijkmanagement') THEN
        SET p_error_message = 'Ongeldig medewerkertype';
        SET v_continue = FALSE;
    END IF;

    -- =============================================
    -- Als validatie succesvol is, voer inserts uit
    -- =============================================
    IF v_continue THEN
        -- =============================================
        -- INSERT: Maak nieuwe gebruiker aan
        -- =============================================
        INSERT INTO users (
            gebruikersnaam,
            email,
            password,
            rol_naam,
            is_actief,
            created_at,
            updated_at
        )
        VALUES (
            p_gebruikersnaam,
            p_email,
            p_password,
            p_rol_naam,
            1,
            NOW(),
            NOW()
        );

        SET v_user_id = LAST_INSERT_ID();

        -- =============================================
        -- INSERT: Maak nieuwe persoon aan
        -- =============================================
        INSERT INTO personen (
            gebruiker_id,
            voornaam,
            tussenvoegsel,
            achternaam,
            geboortedatum,
            is_actief,
            opmerking,
            created_at,
            updated_at
        )
        VALUES (
            v_user_id,
            p_voornaam,
            p_tussenvoegsel,
            p_achternaam,
            p_geboortedatum,
            1,
            p_opmerking,
            NOW(),
            NOW()
        );

        SET v_persoon_id = LAST_INSERT_ID();

        -- =============================================
        -- INSERT: Maak nieuwe medewerker aan
        -- =============================================
        INSERT INTO medewerkers (
            persoon_id,
            nummer,
            medewerker_type,
            specialisatie,
            is_actief,
            opmerking,
            created_at,
            updated_at
        )
        VALUES (
            v_persoon_id,
            p_nummer,
            p_medewerker_type,
            p_specialisatie,
            1,
            p_opmerking,
            NOW(),
            NOW()
        );

        SET p_medewerker_id = LAST_INSERT_ID();

        -- Commit transactie als alles succesvol is
        COMMIT;
    ELSE
        -- Rollback als validatie gefaald is
        ROLLBACK;
    END IF;

END$$

DELIMITER ;
