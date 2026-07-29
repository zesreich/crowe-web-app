-- =============================================================================
-- HSY Crowe — Admin şifrelerini Crowe2022! olarak sıfırla
-- =============================================================================
-- Nerede: Supabase Dashboard → SQL Editor → Run
-- Amaç: Giriş 400 (invalid_credentials) sonrası adminleri tekrar aç
-- Sonra: crowehsy.com → Crowe2022! ile giriş → şifre değiştirme zorunlu
-- =============================================================================

CREATE EXTENSION IF NOT EXISTS pgcrypto;

DO $$
DECLARE
  r record;
  v_password constant text := 'Crowe2022!';
  v_hash text;
  v_user_id uuid;
BEGIN
  v_hash := crypt(v_password, gen_salt('bf'));

  FOR r IN
    SELECT * FROM (VALUES
      ('mert.cengiz@crowehsy.net', 'Mert Cengiz'),
      ('ozkan.cengiz@crowehsy.net', 'Özkan Cengiz'),
      ('mehmetali.sariad@crowehsy.net', 'Mehmet Ali Sarıad'),
      ('eda.sefer@crowehsy.net', 'Eda Sefer'),
      ('hakan.kilic@crowehsy.net', 'Hakan Kılıç')
    ) AS t(email, full_name)
  LOOP
    SELECT id INTO v_user_id
    FROM auth.users
    WHERE lower(email) = lower(r.email)
    LIMIT 1;

    IF v_user_id IS NULL THEN
      -- Kullanıcı yoksa oluştur
      v_user_id := gen_random_uuid();
      INSERT INTO auth.users (
        instance_id, id, aud, role, email, encrypted_password,
        email_confirmed_at, raw_app_meta_data, raw_user_meta_data,
        created_at, updated_at, confirmation_token, recovery_token,
        email_change_token_new, email_change
      ) VALUES (
        COALESCE((SELECT id FROM auth.instances LIMIT 1), '00000000-0000-0000-0000-000000000000'::uuid),
        v_user_id,
        'authenticated',
        'authenticated',
        lower(r.email),
        v_hash,
        now(),
        '{"provider":"email","providers":["email"]}'::jsonb,
        jsonb_build_object(
          'full_name', r.full_name,
          'role', 'admin',
          'requires_password_change', true,
          'password_changed', false
        ),
        now(), now(), '', '', '', ''
      );
    ELSE
      UPDATE auth.users
      SET
        encrypted_password = v_hash,
        email_confirmed_at = COALESCE(email_confirmed_at, now()),
        raw_app_meta_data = COALESCE(raw_app_meta_data, '{}'::jsonb)
          || '{"provider":"email","providers":["email"]}'::jsonb,
        raw_user_meta_data = COALESCE(raw_user_meta_data, '{}'::jsonb)
          || jsonb_build_object(
            'full_name', r.full_name,
            'role', 'admin',
            'requires_password_change', true,
            'password_changed', false
          ),
        updated_at = now()
      WHERE id = v_user_id;
    END IF;

    -- Identity yoksa ekle (login için zorunlu)
    IF NOT EXISTS (
      SELECT 1 FROM auth.identities
      WHERE user_id = v_user_id AND provider = 'email'
    ) THEN
      INSERT INTO auth.identities (
        id, user_id, identity_data, provider, provider_id,
        last_sign_in_at, created_at, updated_at
      ) VALUES (
        gen_random_uuid(),
        v_user_id,
        jsonb_build_object(
          'sub', v_user_id::text,
          'email', lower(r.email),
          'email_verified', true,
          'phone_verified', false
        ),
        'email',
        lower(r.email),
        now(), now(), now()
      );
    ELSE
      UPDATE auth.identities
      SET
        provider_id = lower(r.email),
        identity_data = COALESCE(identity_data, '{}'::jsonb)
          || jsonb_build_object(
            'sub', v_user_id::text,
            'email', lower(r.email),
            'email_verified', true
          ),
        updated_at = now()
      WHERE user_id = v_user_id AND provider = 'email';
    END IF;
  END LOOP;

  RAISE NOTICE 'Admin şifreleri Crowe2022! olarak sıfırlandı.';
END $$;

-- Kontrol
SELECT
  u.email,
  (u.email_confirmed_at IS NOT NULL) AS confirmed,
  EXISTS (
    SELECT 1 FROM auth.identities i
    WHERE i.user_id = u.id AND i.provider = 'email'
  ) AS has_email_identity,
  u.raw_user_meta_data->>'requires_password_change' AS requires_password_change,
  u.updated_at
FROM auth.users u
WHERE lower(u.email) IN (
  'mert.cengiz@crowehsy.net',
  'ozkan.cengiz@crowehsy.net',
  'mehmetali.sariad@crowehsy.net',
  'eda.sefer@crowehsy.net',
  'hakan.kilic@crowehsy.net'
)
ORDER BY u.email;
