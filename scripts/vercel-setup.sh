#!/usr/bin/env bash
# Vercel + GitHub bağlantısını otomatik kurar (tek seferlik)
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

ENV_FILE="$ROOT/.env.vercel"
VERCEL="npx --yes vercel@latest"

echo ""
echo "══════════════════════════════════════════════"
echo "  CROWE HSY — Vercel kurulum"
echo "══════════════════════════════════════════════"
echo ""

if [[ ! -f "$ENV_FILE" ]]; then
  echo "❌ .env.vercel bulunamadı."
  exit 1
fi

# shellcheck disable=SC1090
source "$ENV_FILE"

for var in SUPABASE_URL SUPABASE_ANON_KEY; do
  if [[ -z "${!var:-}" ]]; then
    echo "❌ .env.vercel içinde $var boş."
    exit 1
  fi
done

echo "1/5 — Vercel girişi"
echo "     Tarayıcı açılacak. zesreich GitHub hesabınızla giriş yapın."
echo ""
$VERCEL login

echo ""
echo "2/5 — Proje bağlantısı (crowe-web-app)"
rm -rf "$ROOT/.vercel"
$VERCEL link --yes --project crowe-web-app 2>/dev/null || \
  $VERCEL link --yes

add_env() {
  local name="$1"
  local value="$2"
  local target="$3"
  $VERCEL env rm "$name" "$target" --yes 2>/dev/null || true
  $VERCEL env add "$name" "$target" --value "$value" --yes --sensitive 2>/dev/null || \
    $VERCEL env add "$name" "$target" --value "$value" --yes
}

echo ""
echo "3/5 — Ortam değişkenleri ekleniyor"
for target in preview development production; do
  add_env SUPABASE_URL "$SUPABASE_URL" "$target"
  add_env SUPABASE_ANON_KEY "$SUPABASE_ANON_KEY" "$target"
  add_env APP_ENV "${APP_ENV:-development}" "$target"
  add_env ALLOW_FALLBACK_ADMINS "${ALLOW_FALLBACK_ADMINS:-true}" "$target"
done

echo ""
echo "4/5 — Preview deploy (production değil)"
DEPLOY_URL=$($VERCEL deploy --yes 2>&1 | tee /dev/stderr | grep -Eo 'https://[a-zA-Z0-9.-]+\.vercel\.app' | tail -1)

echo ""
echo "5/5 — GitHub otomatik deploy"
echo "     Vercel Dashboard → Project → Settings → Git"
echo "     GitHub repo: zesreich/crowe-web-app bağlı olmalı."
echo ""
echo "══════════════════════════════════════════════"
if [[ -n "${DEPLOY_URL:-}" ]]; then
  echo "  ✅ Preview site: $DEPLOY_URL"
  echo "  Test: $DEPLOY_URL/login.html"
else
  echo "  ✅ Deploy tamamlandı. URL için: npx vercel ls"
fi
echo "══════════════════════════════════════════════"
echo ""
