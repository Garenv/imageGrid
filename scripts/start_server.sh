#!/bin/bash
set -e  # Exit immediately if a command exits with a non-zero status

LOG_FILE="/var/log/imageGrid/start_server.log"
exec > >(tee -i ${LOG_FILE}) 2>&1  # Redirect all output to the log file

echo "Starting start_server.sh script"

cd /var/www/imageGrid
echo "Current directory:"
pwd

# Log npm and composer versions
echo "npm version:"
npm --version

echo "composer version:"
composer --version

# Remove composer.lock if it exists
echo "Removing composer.lock"
rm -f /var/www/imageGrid/composer.lock

# install from the lock file without updating dependencies
echo "Running composer install --prefer-dist"
composer install --prefer-dist

# install from the lock file without updating dependencies
echo "Running npm ci"
npm ci

# Build assets
echo "Running npm run build"
npm run build

# Optimize Laravel application
echo "Running php artisan optimize:clear"
php artisan optimize:clear

# Check if php-fpm is present and restart it if found
if command -v php-fpm &> /dev/null; then
  sudo systemctl restart php-fpm
else
  echo "Php-fpm is not installed, so it won't be restarted."
fi

# Check if Nginx is present and restart it if found
if command -v nginx &> /dev/null; then
  sudo systemctl restart nginx
else
  echo "Nginx is not installed, so it won't be restarted."
fi

echo "start_server.sh script completed successfully"
