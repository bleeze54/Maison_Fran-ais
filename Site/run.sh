#!/bin/bash
set -e

echo "=== 1. Create ==="
./scripts/create.sh

echo "=== 2. Push ==="
./scripts/push.sh

echo "=== 3. Terminal + Composer install ==="
./scripts/terminal.sh << 'EOF'


cd CI4/

echo "=== Composer install ==="
composer install

echo "=== Exit terminal ==="
exit
EOF

echo "=== 4. Terminé ==="
echo "Ouvre : http://localhost:8080"
