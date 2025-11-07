<div align="center">

# 🛒 Sistema POS

### _Sistema de Punto de Venta Profesional_

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.6-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![SQLite](https://img.shields.io/badge/SQLite-3.x-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://sqlite.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**Un sistema completo de gestión comercial con facturación electrónica, control de inventario y análisis de ventas en tiempo real.**

[Características](#-características-principales) •
[Instalación](#-instalación-rápida) •
[Documentación](#-documentación) •
[Tecnologías](#️-stack-tecnológico)

---

</div>

## ✨ Características Principales

### 💼 Gestión de Ventas
- **🎯 Punto de Venta Intuitivo** - Interfaz moderna y rápida para procesar transacciones
- **📱 Responsive Design** - Funciona perfectamente en tablets y dispositivos móviles
- **🔍 Búsqueda Inteligente** - Encuentra productos al instante con búsqueda en tiempo real
- **🧾 Impresión de Tickets** - Sistema de tickets térmicos completamente personalizable
- **💳 Múltiples Métodos de Pago** - Efectivo, tarjeta, transferencia y pagos combinados

### 📊 Sistema Tributario Avanzado
- **🏛️ Facturación Electrónica** - Compatible con normativas fiscales
- **📑 Comprobantes Fiscales** - Facturas, boletas y notas de crédito
- **🔢 Numeración Automática** - Control secuencial de documentos
- **💰 Cálculo de Impuestos** - IVA, IGV y otros impuestos configurables
- **📈 Reportes Fiscales** - Informes detallados para declaraciones

### 📦 Control de Inventario
- **📊 Stock en Tiempo Real** - Actualización automática de existencias
- **⚠️ Alertas de Stock Bajo** - Notificaciones inteligentes de reabastecimiento
- **📝 Movimientos de Inventario** - Historial completo de entradas y salidas
- **🏷️ Gestión de Categorías** - Organización jerárquica de productos
- **💵 Control de Precios** - Precios regulares, promocionales y por mayoreo
- **🤖 Análisis de Productos con IA** - Toma una foto y la IA identifica el producto automáticamente

### 🎯 Sistema de Metas y Objetivos
- **📈 Objetivos de Ventas** - Define y rastrea metas mensuales/semanales
- **🏆 Seguimiento de Desempeño** - Indicadores KPI en tiempo real
- **👥 Metas por Usuario** - Objetivos individuales para tu equipo
- **📊 Dashboard Analítico** - Visualización clara del progreso

### 🔐 Seguridad y Auditoría
- **✅ Sistema de Anulaciones** - Control completo de ventas canceladas
- **📋 Log de Auditoría** - Registro detallado de todas las operaciones
- **🔒 Motivos de Anulación** - Justificación obligatoria para cancelaciones
- **👤 Roles y Permisos** - Control granular de acceso con Spatie Permission
- **🕐 Historial Completo** - Trazabilidad de todas las modificaciones

### 👥 Gestión de Usuarios
- **🔑 Autenticación Segura** - Laravel Breeze con validación robusta
- **👔 Roles Personalizables** - Administrador, Vendedor, Supervisor
- **🎨 Perfiles de Usuario** - Gestión completa de información personal
- **📊 Reportes por Vendedor** - Análisis individual de desempeño

### ⚙️ Configuración Empresarial
- **🏢 Datos del Negocio** - Información fiscal y comercial
- **🎨 Personalización de Tickets** - Logo, mensajes y formato
- **💼 Configuración Tributaria** - Parámetros fiscales personalizables
- **🌍 Multi-idioma** - Preparado para internacionalización

---

## 🚀 Instalación Rápida

### Requisitos Previos

```bash
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM o Yarn
- SQLite 3.x
```

### Instalación en 3 Pasos

```bash
# 1️⃣ Clonar el repositorio
git clone https://github.com/Davidkm03/sistema-pos.git
cd sistema-pos

# 2️⃣ Instalar dependencias y configurar
composer run setup

# 3️⃣ Sembrar datos de ejemplo (opcional)
php artisan db:seed
```

### Iniciar el Servidor

```bash
# Opción 1: Servidor Laravel simple
php artisan serve --port=8001

# Opción 2: Entorno completo de desarrollo
composer run dev
```

🎉 **¡Listo!** Visita `http://localhost:8001` en tu navegador

### Credenciales por Defecto

```
📧 Email: admin@example.com
🔑 Password: password
```

---

## 🏗️ Stack Tecnológico

### Backend
- **[Laravel 12.x](https://laravel.com)** - Framework PHP moderno y elegante
- **[Livewire 3.6](https://livewire.laravel.com)** - Componentes dinámicos sin JavaScript
- **[Spatie Permission](https://spatie.be/docs/laravel-permission)** - Sistema de roles y permisos

### Frontend
- **[TailwindCSS 3.x](https://tailwindcss.com)** - Framework CSS utility-first
- **[Alpine.js](https://alpinejs.dev)** - JavaScript minimalista y reactivo
- **[Vite](https://vitejs.dev)** - Build tool ultrarrápido

### Base de Datos
- **[SQLite](https://sqlite.org)** - Base de datos ligera y portable
- Compatible con **MySQL/PostgreSQL** para producción

---

## 📁 Estructura del Proyecto

```
sistema-pos/
├── 📱 app/
│   ├── Livewire/          # Componentes Livewire
│   ├── Models/            # Modelos Eloquent
│   ├── Http/Controllers/  # Controladores
│   └── Helpers/           # Funciones auxiliares
├── 🗄️ database/
│   ├── migrations/        # Migraciones de BD
│   └── seeders/           # Datos de prueba
├── 🎨 resources/
│   ├── views/             # Plantillas Blade
│   ├── css/               # Estilos
│   └── js/                # Scripts
├── 🛣️ routes/
│   ├── web.php            # Rutas web
│   └── auth.php           # Rutas de autenticación
└── ⚙️ config/             # Configuración
```

---

## 📚 Documentación

### Guías Especializadas

- 📄 **[Sistema Tributario](SISTEMA_TRIBUTARIO_README.md)** - Facturación electrónica y configuración fiscal
- 🚫 **[Sistema de Anulaciones](SISTEMA_ANULACION_README.md)** - Control de cancelaciones y auditoría

### Módulos Principales

#### 🛒 Punto de Venta
```bash
Ruta: /pos
Componente: PosIndex.php
Descripción: Interfaz principal para realizar ventas
```

#### 📦 Gestión de Productos
```bash
Ruta: /productos
Componente: ProductManager.php
Características: CRUD completo, categorías, precios, stock
```

#### 📊 Reportes de Ventas
```bash
Ruta: /ventas
Componente: SaleManager.php
Características: Historial, filtros, anulaciones, reimprimir
```

#### 📈 Dashboard
```bash
Ruta: /dashboard
Vista: dashboard.blade.php
Características: KPIs, gráficos, metas, ventas del día
```

---

## 🔧 Configuración Avanzada

### 🤖 Análisis de Productos con IA

El sistema incluye una funcionalidad innovadora de **análisis de imágenes con Inteligencia Artificial** que te permite agregar productos tomándoles una foto:

#### ¿Cómo funciona?

1. **📸 Toma una foto** del producto desde tu celular o sube una imagen
2. **🤖 La IA analiza** la imagen y extrae:
   - Nombre del producto
   - Descripción detallada
   - Categoría sugerida
   - Precio estimado
   - Código de barras (si es visible)
3. **✨ Auto-completa** todos los campos del formulario automáticamente

#### Configuración

Para habilitar esta funcionalidad, necesitas una API key de OpenAI:

```bash
# 1. Obtén tu API key en: https://platform.openai.com/api-keys
# 2. Agrega la clave en tu archivo .env:

OPENAI_API_KEY=sk-your-api-key-here
```

#### Características

- **📱 Compatible con móviles** - Usa la cámara directamente desde tu dispositivo
- **⚡ Rápido y preciso** - Resultados en segundos usando GPT-4 Vision
- **💰 Económico** - Usa el modelo `gpt-4o-mini` optimizado para costos
- **🎯 Inteligente** - Identifica productos, marcas, tamaños y características
- **✅ Nivel de confianza** - Te indica qué tan segura es la identificación

#### Ejemplo de Uso

```php
// El sistema automáticamente:
// - Identifica: "Coca-Cola 1.5L"
// - Categoriza: "Bebidas"
// - Estima precio: $3,500
// - Extrae código de barras si es visible
// - Genera descripción: "Bebida gaseosa sabor cola, botella PET 1.5 litros"
```

> **💡 Tip**: Funciona mejor con fotos claras, buena iluminación y el producto bien centrado en la imagen.

---

### Variables de Entorno

```env
# Aplicación
APP_NAME="Sistema POS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8001

# Base de Datos
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Configuración de Negocio
BUSINESS_NAME="Tu Negocio"
BUSINESS_TAX_ID="20123456789"
BUSINESS_ADDRESS="Tu Dirección"
```

### Comandos Útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Crear usuario administrador
php artisan make:filament-user

# Backup de base de datos
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# Ejecutar tests
php artisan test

# Formatear código
./vendor/bin/pint
```

---

## 🎯 Casos de Uso

### 🏪 Retail y Comercio
- Tiendas de abarrotes
- Farmacias
- Librerías
- Ferreterías

### 🍽️ Restaurantes y Cafeterías
- Punto de venta rápido
- Control de inventario
- Reportes de ventas

### 👗 Boutiques y Moda
- Gestión de catálogo
- Control de tallas
- Promociones

---

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Si deseas mejorar el sistema:

1. 🍴 Fork el proyecto
2. 🌿 Crea una rama feature (`git checkout -b feature/AmazingFeature`)
3. 💾 Commit tus cambios (`git commit -m 'Add: nueva característica'`)
4. 📤 Push a la rama (`git push origin feature/AmazingFeature`)
5. 🔃 Abre un Pull Request

---

## 📝 Roadmap

- [ ] 📱 App móvil nativa (iOS/Android)
- [ ] 🌐 API REST completa
- [ ] 📊 Dashboard con gráficos avanzados (Chart.js)
- [ ] 🔔 Notificaciones en tiempo real
- [ ] 📧 Envío de tickets por email
- [ ] 🏦 Integración con pasarelas de pago
- [ ] 📦 Sistema de órdenes de compra
- [ ] 👥 CRM de clientes
- [ ] 📱 WhatsApp Business integration

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

---

## 👨‍💻 Autor

**Desarrollado con ❤️ por [Davidkm03](https://github.com/Davidkm03)**

---

<div align="center">

### ⭐ Si este proyecto te fue útil, considera darle una estrella

**[⬆ Volver arriba](#-sistema-pos)**

</div>
