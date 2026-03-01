#!/bin/bash
# Ukázkový deploy script pro Laravel Forge.
# V Forge → Site → Deployment Script můžeš obsah zkopírovat a upravit.

set -e

# Zajistí existenci složek (prevence "Please provide a valid cache path")
mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Zbytek tvého deploy scriptu (composer, migrate, atd.)
# cd /home/forge/tvoje-site/release
# composer install --no-interaction --prefer-dist --optimize-autoloader
# php artisan migrate --force
# php artisan config:cache
# ...
