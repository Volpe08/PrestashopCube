#!/bin/sh
set -eu

CUSTOM_MODULES_DIR="/opt/prestashop-custom-modules"
TARGET_MODULES_DIR="/var/www/html/modules"
ADMIN_TARGET="admin104hsvuc059ecpn57uw"

echo "[init] custom entrypoint started"

# Detect prestashop install
if [ -f /var/www/html/app/config/parameters.php ] || [ -f /var/www/html/config/settings.inc.php ]; then
  echo "[init] Prestashop config detected"
  rm -rf /var/www/html/install || true
else
  echo "[init] Prestashop config NOT detected"
fi

# Rename admin folder if needed
if [ -d "/var/www/html/admin" ]; then
  echo "[init] renaming admin folder"
  mv /var/www/html/admin "/var/www/html/$ADMIN_TARGET"
fi

# Sync custom modules
if [ -d "$CUSTOM_MODULES_DIR" ]; then
  echo "[init] syncing custom modules..."
  for module_path in "$CUSTOM_MODULES_DIR"/*; do
    if [ -d "$module_path" ]; then
      module_name="$(basename "$module_path")"
      echo "[init] sync module: $module_name"
      rm -rf "$TARGET_MODULES_DIR/$module_name"
      mkdir -p "$TARGET_MODULES_DIR/$module_name"
      cp -a "$module_path"/. "$TARGET_MODULES_DIR/$module_name"/
    fi
  done
fi

# Ensure htaccess exists (for URL rewriting)
if [ ! -f /var/www/html/.htaccess ]; then
  echo "[init] .htaccess missing, generating it"
  php /var/www/html/bin/console prestashop:config generate-htaccess || true
fi

echo "[init] starting Prestashop"
exec "$@"