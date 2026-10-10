#!/bin/bash
set -e

echo "========================================"
echo "  PHP Development Container"
echo "========================================"
echo ""
echo "Directory: $(pwd)"
echo "User:      $(whoami)"
echo "PHP:       $(php -r 'echo PHP_VERSION;')"
echo "Composer:  $(composer --version 2>/dev/null | head -n 1 || echo 'not installed')"
echo ""

# Install dependencies if composer.json exists
if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
    echo "Installing dependencies..."
    composer install --no-interaction
fi

echo "========================================"
echo "  Container ready!"
echo "========================================"

# Keep container running
exec tail -f /dev/null
