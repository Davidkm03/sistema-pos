#!/bin/bash

# ============================================
# SCRIPT DE DEPLOYMENT - Sistema POS
# Fecha: 2025-11-10
# Servidor: Hostinger Shared Hosting
# ============================================

# COLORES PARA OUTPUT
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}   DEPLOYMENT - Sistema POS${NC}"
echo -e "${BLUE}   Fecha: $(date)${NC}"
echo -e "${BLUE}========================================${NC}\n"

# 1. GIT PULL
echo -e "${YELLOW}[1/8]${NC} Pulling latest changes from GitHub..."
git pull origin main

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Git pull exitoso${NC}\n"
else
    echo -e "${RED}✗ Error en git pull${NC}"
    exit 1
fi

# 2. COMPOSER
echo -e "${YELLOW}[2/8]${NC} Optimizing Composer autoload..."
composer dump-autoload --optimize --no-dev

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Composer optimizado${NC}\n"
else
    echo -e "${RED}✗ Error en composer${NC}"
    exit 1
fi

# 3. MIGRACIONES
echo -e "${YELLOW}[3/8]${NC} Running database migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migraciones ejecutadas${NC}\n"
else
    echo -e "${RED}✗ Error en migraciones${NC}"
    echo -e "${YELLOW}Tip: Verifica las migraciones pendientes con: php artisan migrate:status${NC}"
    exit 1
fi

# 4. LIMPIAR CACHÉS
echo -e "${YELLOW}[4/8]${NC} Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo -e "${GREEN}✓ Cachés limpiados${NC}\n"

# 5. OPTIMIZAR PARA PRODUCCIÓN
echo -e "${YELLOW}[5/8]${NC} Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Optimización completa${NC}\n"
else
    echo -e "${RED}✗ Error en optimización${NC}"
    exit 1
fi

# 6. PERMISOS (Si aplica)
echo -e "${YELLOW}[6/8]${NC} Setting permissions..."
chmod -R 755 storage bootstrap/cache

echo -e "${GREEN}✓ Permisos configurados${NC}\n"

# 7. VERIFICAR ESTADO
echo -e "${YELLOW}[7/8]${NC} Checking application status..."
php artisan optimize

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Verificación exitosa${NC}\n"
else
    echo -e "${RED}✗ Error en verificación${NC}"
    exit 1
fi

# 8. RESUMEN
echo -e "${YELLOW}[8/8]${NC} Deployment summary..."
echo -e "\n${BLUE}========================================${NC}"
echo -e "${GREEN}✓ DEPLOYMENT COMPLETADO EXITOSAMENTE${NC}"
echo -e "${BLUE}========================================${NC}\n"

echo -e "${BLUE}Commits desplegados:${NC}"
git log -5 --oneline

echo -e "\n${BLUE}Estado de migraciones:${NC}"
php artisan migrate:status

echo -e "\n${BLUE}Próximos pasos:${NC}"
echo -e "1. Verificar aplicación en navegador"
echo -e "2. Configurar SMTP como super-admin"
echo -e "3. Probar envío de cotización por email"
echo -e "4. Revisar logs: ${YELLOW}storage/logs/laravel.log${NC}"

echo -e "\n${GREEN}¡Deployment finalizado!${NC}\n"
