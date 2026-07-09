-- =====================================================
-- Denetçi modülü — Supabase tabloları
-- SQL Editor'da supabase-yeni-proje-kurulum.sql SONRASI çalıştırın
-- =====================================================

-- Denetim ekibi üyeleri (auth.users ile eşleşir)
create table if not exists public.auditor_team_members (
  id uuid primary key default gen_random_uuid(),
  user_id uuid references auth.users(id) on delete cascade,
  email varchar(255) not null unique,
  full_name varchar(255) not null,
  team varchar(100) not null,
  role varchar(50) not null default 'auditor',
  status varchar(50) not null default 'active',
  joined_at date default current_date,
  suspended_at date,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now()
);

create index if not exists idx_auditor_team_email on public.auditor_team_members(email);
create index if not exists idx_auditor_team_team on public.auditor_team_members(team);
create index if not exists idx_auditor_team_status on public.auditor_team_members(status);

-- Müşteri + ekip workspace şablonu
create table if not exists public.auditor_workspaces (
  id uuid primary key default gen_random_uuid(),
  client_id text not null,
  team varchar(100) not null,
  client_name varchar(255),
  vergi_no varchar(50),
  items jsonb not null default '{}'::jsonb,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now(),
  unique (client_id, team)
);

create index if not exists idx_auditor_ws_client on public.auditor_workspaces(client_id);
create index if not exists idx_auditor_ws_team on public.auditor_workspaces(team);

drop trigger if exists trg_auditor_members_updated_at on public.auditor_team_members;
create trigger trg_auditor_members_updated_at
before update on public.auditor_team_members
for each row execute function public.set_updated_at();

drop trigger if exists trg_auditor_ws_updated_at on public.auditor_workspaces;
create trigger trg_auditor_ws_updated_at
before update on public.auditor_workspaces
for each row execute function public.set_updated_at();

alter table public.auditor_team_members enable row level security;
alter table public.auditor_workspaces enable row level security;

drop policy if exists "auditor_members_all" on public.auditor_team_members;
create policy "auditor_members_all" on public.auditor_team_members
  for all to authenticated using (true) with check (true);

drop policy if exists "auditor_workspaces_all" on public.auditor_workspaces;
create policy "auditor_workspaces_all" on public.auditor_workspaces
  for all to authenticated using (true) with check (true);

-- Mevcut denetçi listesi (auditor-login.html'deki ekip)
insert into public.auditor_team_members (email, full_name, team, role, status)
values
  ('ibrahim.kuvvet@crowehsy.net', 'İbrahim Kuvvet', 'B1_EDA', 'auditor', 'active'),
  ('yunusemre.durmus@crowehsy.net', 'Yunus Emre Durmuş', 'B1_EDA', 'auditor', 'active'),
  ('berki.ozkan@crowehsy.net', 'Berki Özkan', 'B1_EDA', 'auditor', 'active'),
  ('dilan.ulusu@crowehsy.net', 'Dilan Ulusu', 'B1_EDA', 'auditor', 'active'),
  ('aykan.altintel@crowehsy.net', 'Aykan Altıntel', 'B1_EDA', 'auditor', 'active'),
  ('beste.darcan@crowehsy.net', 'Beste Darcan', 'B1_EDA', 'auditor', 'active'),
  ('fatih.senturk@crowehsy.net', 'Fatih Şentürk', 'B2_MAS', 'auditor', 'active'),
  ('kasim.durmus@crowehsy.net', 'Kasım Durmuş', 'B2_MAS', 'auditor', 'active'),
  ('yaprak.tosun@crowehsy.net', 'Yaprak Tosun', 'B2_MAS', 'auditor', 'active'),
  ('nida.cetin@crowehsy.net', 'Nida Çetin', 'B2_MAS', 'auditor', 'active'),
  ('dilek.cinar2@crowehsy.net', 'Dilek Çınar', 'B2_MAS', 'auditor', 'active'),
  ('elif.girgin@crowehsy.net', 'Elif Girgin', 'B2_MAS', 'auditor', 'active'),
  ('servet.aykanat@crowehsy.net', 'Servet Aykanat', 'B3_HAKAN', 'auditor', 'active'),
  ('cevahir.dogan@crowehsy.net', 'Cevahir Doğan', 'B3_HAKAN', 'auditor', 'active'),
  ('adar.kilic@crowehsy.net', 'Adar Kılıç', 'B3_HAKAN', 'auditor', 'active'),
  ('hasan.bozkaya@crowehsy.net', 'Hasan Bozkaya', 'B3_HAKAN', 'auditor', 'active'),
  ('mesut.molla@crowehsy.net', 'Mesut Molla', 'B3_HAKAN', 'auditor', 'active'),
  ('s.korkmaz@crowehsy.net', 'S. Korkmaz', 'B3_HAKAN', 'auditor', 'active'),
  ('serdar.karaagin@crowehsy.net', 'Serdar Karaağın', 'B3_HAKAN', 'auditor', 'active')
on conflict (email) do nothing;

-- =====================================================
-- EKİP GÜNCELLEME
-- Yukarıdaki B1/B2/B3 atamaları örnektir. Doğru atamaları
-- iki şekilde yapabilirsiniz:
--   1) Denetçi paneli → "Ekip kullanıcıları" sekmesindeki
--      ekip açılır menüsünden (otomatik Supabase'e yazılır)
--   2) Aşağıdaki gibi SQL ile:
-- update public.auditor_team_members set team = 'B2_MAS'
--   where email = 'ibrahim.kuvvet@crowehsy.net';
-- =====================================================

-- Doğrulama
select table_name from information_schema.tables
where table_schema = 'public'
  and table_name in ('auditor_team_members', 'auditor_workspaces')
order by table_name;
