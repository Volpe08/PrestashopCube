#!/bin/sh
set -eu

CUSTOM_MODULES_DIR="/opt/prestashop-custom-modules"
TARGET_MODULES_DIR="/var/www/html/modules"

echo "[init] custom entrypoint started"

if [ -d "$CUSTOM_MODULES_DIR" ]; then
  echo "[init] syncing custom modules from image..."
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

exec "$@"