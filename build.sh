#!/bin/bash
set -e

# Install PHP dependencies
if [ -f "composer.json" ]; then
  composer install --no-dev --optimize-autoloader
fi

# Install Node dependencies and build assets
if [ -f "package.json" ]; then
  npm install
  npm run build
fi
