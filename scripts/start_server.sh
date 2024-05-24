#!/bin/bash
set -e  # Exit immediately if a command exits with a non-zero status

echo "Starting start_server.sh script"

cd /var/www/imageGrid
echo "Current directory:"
pwd

# Log npm and composer versions
echo "npm version:"
npm --version

echo "composer version:"
composer --version

# Define the non-root user to run Composer and npm
USER_NAME="garen"  # Replace with the actual username
USER_HOME="/home/$USER_NAME"

# Ensure the environment variables are set
export HOME=$USER_HOME
export COMPOSER_HOME=$USER_HOME/.composer

# Remove composer.lock if it exists
echo "Removing composer.lock"
rm -f /var/www/imageGrid/composer.lock

# Run Composer install as the non-root user
echo "Running composer install --prefer-dist"
sudo -u $USER_NAME -H sh -c 'composer install --prefer-dist'

# Run npm install as the non-root user
echo "Running npm ci"
sudo -u $USER_NAME -H sh -c 'npm ci'

# Build assets as the non-root user
echo "Running npm run build"
sudo -u $USER_NAME -H sh -c 'npm run build'

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
