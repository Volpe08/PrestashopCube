#!/bin/sh
set -eu

CUSTOM_MODULES_DIR="/opt/prestashop-custom-modules"
TARGET_MODULES_DIR="/var/www/html/modules"

echo "[init] custom entrypoint started"

if [ -f /var/www/html/app/config/parameters.php ] || [ -f /var/www/html/config/settings.inc.php ]; then
  echo "[init] Prestashop config detected"
  rm -rf /var/www/html/install || true
else
  echo "[init] Prestashop config NOT detected"
fi

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

echo "[init] startup Prestashop"
exec "$@"