-- =====================================================
-- Crowe HSY — Yeni Supabase Projesi (tek seferde çalıştır)
-- Supabase Dashboard → SQL Editor → New query → Run
-- =====================================================

-- clients
create table if not exists public.clients (
  id uuid primary key default gen_random_uuid(),
  vergi_no varchar(50) unique not null,
  unvan varchar(255) not null,
  vergi_dairesi varchar(255),
  ekip varchar(100),
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now()
);

create index if not exists idx_clients_vergi_no on public.clients(vergi_no);
create index if not exists idx_clients_unvan on public.clients(unvan);

create or replace function public.set_updated_at()
returns trigger language plpgsql as $$
begin
  new.updated_at = now();
  return new;
end;
$$;

drop trigger if exists trg_clients_updated_at on public.clients;
create trigger trg_clients_updated_at
before update on public.clients
for each row execute function public.set_updated_at();

alter table public.clients enable row level security;

drop policy if exists "clients_select_authenticated" on public.clients;
create policy "clients_select_authenticated" on public.clients for select to authenticated using (true);
drop policy if exists "clients_insert_authenticated" on public.clients;
create policy "clients_insert_authenticated" on public.clients for insert to authenticated with check (true);
drop policy if exists "clients_update_authenticated" on public.clients;
create policy "clients_update_authenticated" on public.clients for update to authenticated using (true) with check (true);
drop policy if exists "clients_delete_authenticated" on public.clients;
create policy "clients_delete_authenticated" on public.clients for delete to authenticated using (true);

-- diğer tablolar
create extension if not exists "uuid-ossp";

create table if not exists public.payments (
  id uuid primary key default uuid_generate_v4(),
  company varchar(255) not null,
  report_type varchar(100) not null,
  report_type_value varchar(100),
  amount decimal(15, 2) not null,
  currency varchar(10) not null default 'TRY',
  payment_date date not null,
  description text,
  created_at timestamptz default now(),
  updated_at timestamptz default now(),
  created_by uuid references auth.users(id) on delete set null
);

create table if not exists public.reports (
  id uuid primary key default uuid_generate_v4(),
  company varchar(255) not null,
  service varchar(255) not null,
  report_type varchar(50) not null,
  start_date date not null,
  end_date date not null,
  team varchar(100) not null,
  status varchar(50) not null default 'Devam Ediyor',
  created_at timestamptz default now(),
  updated_at timestamptz default now(),
  created_by uuid references auth.users(id) on delete set null
);

create table if not exists public.contracts (
  id uuid primary key default uuid_generate_v4(),
  contract_type varchar(100) not null,
  company varchar(255) not null,
  contract_name varchar(255),
  period varchar(50),
  auditor1 varchar(255),
  auditor2 varchar(255),
  auditor3 varchar(255),
  team varchar(100),
  hours varchar(50),
  contract_received varchar(50),
  kgk_reported varchar(50),
  created_at timestamptz default now(),
  updated_at timestamptz default now(),
  created_by uuid references auth.users(id) on delete set null
);

create table if not exists public.offers (
  id uuid primary key default uuid_generate_v4(),
  offer_no varchar(100) unique not null,
  client_name varchar(255) not null,
  offer_type varchar(255) not null,
  send_date date not null,
  status varchar(50) not null default 'Onay Bekliyor',
  ppt_files jsonb default '[]'::jsonb,
  pdf_files jsonb default '[]'::jsonb,
  created_at timestamptz default now(),
  updated_at timestamptz default now(),
  created_by uuid references auth.users(id) on delete set null
);

create table if not exists public.online_sessions (
  id uuid primary key default uuid_generate_v4(),
  user_id uuid references auth.users(id) on delete cascade,
  user_email varchar(255) not null,
  full_name varchar(255),
  session_start timestamptz default now(),
  last_activity timestamptz default now(),
  active_files jsonb default '[]'::jsonb,
  ip_address varchar(45),
  user_agent text,
  is_active boolean default true,
  created_at timestamptz default now()
);

-- RLS — tüm tablolar authenticated kullanıcıya açık
alter table public.payments enable row level security;
alter table public.reports enable row level security;
alter table public.contracts enable row level security;
alter table public.offers enable row level security;
alter table public.online_sessions enable row level security;

do $$
declare t text;
begin
  foreach t in array array['payments','reports','contracts','offers','online_sessions']
  loop
    execute format('drop policy if exists "%1$s_all" on public.%1$s', t);
    execute format('create policy "%1$s_all" on public.%1$s for all to authenticated using (true) with check (true)', t);
  end loop;
end $$;

-- demo müşteri
insert into public.clients (vergi_no, unvan, vergi_dairesi, ekip)
values ('1111111111', 'Demo Musteri A.S.', 'Besiktas', 'A Takimi')
on conflict (vergi_no) do nothing;

-- doğrulama
select table_name from information_schema.tables
where table_schema = 'public'
  and table_name in ('clients','payments','reports','contracts','offers','online_sessions')
order by table_name;
