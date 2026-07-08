-- Crowe HSY - First Supabase Setup
-- SQL Editor'de tek seferde calistirabilirsiniz.

-- 1) clients tablosu
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

-- updated_at trigger
create or replace function public.set_updated_at()
returns trigger
language plpgsql
as $$
begin
  new.updated_at = now();
  return new;
end;
$$;

drop trigger if exists trg_clients_updated_at on public.clients;
create trigger trg_clients_updated_at
before update on public.clients
for each row
execute function public.set_updated_at();

-- 2) RLS
alter table public.clients enable row level security;

drop policy if exists "clients_select_authenticated" on public.clients;
create policy "clients_select_authenticated"
on public.clients
for select
to authenticated
using (true);

drop policy if exists "clients_insert_authenticated" on public.clients;
create policy "clients_insert_authenticated"
on public.clients
for insert
to authenticated
with check (true);

drop policy if exists "clients_update_authenticated" on public.clients;
create policy "clients_update_authenticated"
on public.clients
for update
to authenticated
using (true)
with check (true);

drop policy if exists "clients_delete_authenticated" on public.clients;
create policy "clients_delete_authenticated"
on public.clients
for delete
to authenticated
using (true);

-- 3) Test kaydi (opsiyonel)
insert into public.clients (vergi_no, unvan, vergi_dairesi, ekip)
values ('1111111111', 'Demo Musteri A.S.', 'Besiktas', 'A Takimi')
on conflict (vergi_no) do nothing;
