#!/usr/bin/env bash
# Builds the static site in ./public from the WordPress source in this repo.
# Needs: php (with mysqli), mariadb/mysql server, wget, python3.
# Usage: scripts/build-static.sh   (from the repo root)
set -euo pipefail

DOMAIN="${DOMAIN:-https://casinosidprovider.com}"
PORT=8080
LOCAL="http://localhost:$PORT"
REPO="$(cd "$(dirname "$0")/.." && pwd)"
WORK="$(mktemp -d)"
trap 'kill $PHP_PID 2>/dev/null || true; rm -rf "$WORK"' EXIT

# 1. Copy WordPress to a scratch dir and point it at a throwaway local database.
rsync -a --exclude .git --exclude public --exclude scripts "$REPO/" "$WORK/wp/"
mysql -e "DROP DATABASE IF EXISTS reddyanna_build; CREATE DATABASE reddyanna_build;
  CREATE USER IF NOT EXISTS 'wpbuild'@'localhost' IDENTIFIED BY 'wpbuild';
  GRANT ALL ON reddyanna_build.* TO 'wpbuild'@'localhost';"
mysql reddyanna_build < "$REPO/reddyanna.sql"
mysql reddyanna_build -e "UPDATE wp_options SET option_value='$LOCAL' WHERE option_name IN ('siteurl','home');
  UPDATE wp_options SET option_value='1' WHERE option_name='blog_public';"
sed -i -E "s/'DB_NAME', *'[^']*'/'DB_NAME', 'reddyanna_build'/;
  s/'DB_USER', *'[^']*'/'DB_USER', 'wpbuild'/;
  s/'DB_PASSWORD', *'[^']*'/'DB_PASSWORD', 'wpbuild'/;
  s/'DB_HOST', *'[^']*'/'DB_HOST', '127.0.0.1'/" "$WORK/wp/wp-config.php"

# 2. Serve it with PHP's built-in server.
cat > "$WORK/router.php" <<PHP
<?php
\$root = '$WORK/wp';
\$path = parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH);
\$file = \$root . \$path;
if (\$path !== '/' && is_file(\$file) && substr(\$file, -4) !== '.php') return false;
if (is_file(\$file) && substr(\$file, -4) === '.php') { chdir(dirname(\$file)); require \$file; return; }
chdir(\$root); require \$root . '/index.php';
PHP
php -S 127.0.0.1:$PORT -t "$WORK/wp" "$WORK/router.php" >"$WORK/php.log" 2>&1 &
PHP_PID=$!
for _ in $(seq 1 20); do curl -fs "$LOCAL/" -o /dev/null && break; sleep 0.5; done

# 3. Crawl every page plus everything listed in the Yoast sitemaps.
OUT="$WORK/out"; mkdir -p "$OUT"; cd "$OUT"
REJECT='(wp-admin|wp-login|xmlrpc|/feed/|/comments/feed|replytocom|wp-json|/author/)'
wget -q -m -p -E -nH --restrict-file-names=unix -e robots=off --reject-regex "$REJECT" \
  "$LOCAL/" "$LOCAL/sitemap_index.xml" || true
for s in $(grep -oE "$LOCAL/[a-z0-9_-]+-sitemap\.xml" sitemap_index.xml); do
  wget -q -nH -x "$s"
  for u in $(grep -oE '<loc>[^<]+' "$(basename "$s")" | sed 's/<loc>//'); do
    wget -q -m -p -E -nH --restrict-file-names=unix -e robots=off --reject-regex "$REJECT" "$u" || true
  done
done
printf 'User-agent: *\nDisallow:\n\nSitemap: %s/sitemap_index.xml\n' "$DOMAIN" > robots.txt
curl -s "$LOCAL/this-page-does-not-exist/" -o 404.html

# 4. Clean up and publish to ./public.
python3 "$REPO/scripts/postprocess.py" "$OUT" "$LOCAL" "$DOMAIN"
rm -rf "$REPO/public"; cp -a "$OUT" "$REPO/public"
cp "$REPO/scripts/static-forms.js" "$REPO/public/static-forms.js"
echo "Built $(find "$REPO/public" -name '*.html' | wc -l) pages into public/"
