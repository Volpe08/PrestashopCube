#!/bin/sh
set -eu

CUSTOM_MODULES_DIR="/opt/prestashop-custom-modules"
TARGET_MODULES_DIR="/var/www/html/modules"

CONFIG_FILE_1="/var/www/html/app/config/parameters.php"
CONFIG_FILE_2="/var/www/html/config/settings.inc.php"

echo "[init] Checking Prestashop installation state..."

if [ -f "$CONFIG_FILE_1" ] || [ -f "$CONFIG_FILE_2" ]; then
  echo "[init] Existing Prestashop config detected."
  if [ -d /var/www/html/install ]; then
    echo "[init] Removing install directory..."
    rm -rf /var/www/html/install || true
  fi
else
  echo "[init] No existing Prestashop config detected."
fi

echo "[init] Sync custom modules..."

if [ -d "$CUSTOM_MODULES_DIR" ]; then
  for module_path in "$CUSTOM_MODULES_DIR"/*; do
    if [ -d "$module_path" ]; then
      module_name="$(basename "$module_path")"
      echo "[init] Installing/updating custom module: $module_name"
      rm -rf "$TARGET_MODULES_DIR/$module_name"
      mkdir -p "$TARGET_MODULES_DIR/$module_name"
      cp -a "$module_path"/. "$TARGET_MODULES_DIR/$module_name"/
    fi
  done
fi

echo "[init] Starting original Prestashop startup..."
exec "$@"