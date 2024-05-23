#!/usr/bin/env sh
current_date=$(date "+%Y%m%d")

cd /var/www/imageGrid

# Empty existing .env file if present
if [ -e .env ]; then
    echo ".env file exists, backing up and emptying file."
    cp .env .env.$current_date.BAK
    echo "" > .env
else
    echo ".env file not found, moving on to merge."
fi

# Check .env file is properly formatted in KEY=VALUE format
if grep -qE '^[^=]+=[^=]+$' .env; then
    echo ".env File is formatted correctly, removing seed files."
    rm -rf .env-*
else
    echo "Error: File is not formatted in KEY=VALUE pattern, exiting."
    exit 1
fi
