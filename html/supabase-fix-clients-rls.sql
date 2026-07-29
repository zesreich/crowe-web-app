-- =============================================================================
-- HSY Crowe — clients tablosu RLS politikalarını yenile
-- =============================================================================
-- Nerede: Supabase → SQL Editor → Run
-- Amaç: Giriş yapmış (authenticated) kullanıcıların müşteri eklemesine izin ver
-- =============================================================================

ALTER TABLE public.clients ENABLE ROW LEVEL SECURITY;

DROP POLICY IF EXISTS "Authenticated users can view clients" ON public.clients;
DROP POLICY IF EXISTS "Authenticated users can insert clients" ON public.clients;
DROP POLICY IF EXISTS "Authenticated users can update clients" ON public.clients;
DROP POLICY IF EXISTS "Authenticated users can delete clients" ON public.clients;
DROP POLICY IF EXISTS "clients_select_authenticated" ON public.clients;
DROP POLICY IF EXISTS "clients_insert_authenticated" ON public.clients;
DROP POLICY IF EXISTS "clients_update_authenticated" ON public.clients;
DROP POLICY IF EXISTS "clients_delete_authenticated" ON public.clients;

CREATE POLICY "clients_select_authenticated" ON public.clients
  FOR SELECT TO authenticated
  USING (true);

CREATE POLICY "clients_insert_authenticated" ON public.clients
  FOR INSERT TO authenticated
  WITH CHECK (true);

CREATE POLICY "clients_update_authenticated" ON public.clients
  FOR UPDATE TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "clients_delete_authenticated" ON public.clients
  FOR DELETE TO authenticated
  USING (true);

-- Kontrol
SELECT schemaname, tablename, policyname, roles, cmd
FROM pg_policies
WHERE tablename = 'clients'
ORDER BY policyname;
