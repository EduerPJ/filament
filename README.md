# Filament tutorial

## Descripción del Proyecto

Este proyecto es un sistema completo de gestión de pacientes para una clínica veterinaria desarrollado con Laravel y Filament. Permite gestionar pacientes (gatos, perros, conejos), sus propietarios y tratamientos médicos, incluyendo un dashboard con estadísticas y gráficos de tendencias.

**Demo en vivo**: [https://chirper-main-wf4bpr.laravel.cloud/login](https://chirper-main-wf4bpr.laravel.cloud/login)


## Requisitos Previos

- **Docker** y **Docker Compose** instalados en tu sistema local
- **Git** para clonar el repositorio

## Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/EduerPJ/filament.git
cd filament
```

### 2. Instalar Dependencias de PHP

Si tienes **Composer** instalado localmente:
```bash
composer install
```

Si **NO** tienes Composer instalado, usa este contenedor temporal:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd)":/var/www/html \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    bash -c "composer install --ignore-platform-reqs"
```

### 3. Configurar Archivos de Entorno

```bash
cp .env.example .env
cp .env.testing.example .env.testing
```

### 4. Iniciar Laravel Sail

```bash
vendor/bin/sail up -d
```

### 5. Instalar Dependencias de Node.js y Configurar Husky

```bash
vendor/bin/sail npm install
```

### 6. Generar Clave de la Aplicación

```bash
vendor/bin/sail artisan key:generate
```

### 7. Ejecutar Migraciones

```bash
vendor/bin/sail artisan migrate
```

### 8. Compilar Assets Frontend

```bash
vendor/bin/sail npm run dev
```

### 9. Acceder a la Aplicación

Abre tu navegador y ve a: **http://localhost**

¡Regístrate e interactúa con la aplicación!

## Herramientas de Desarrollo

Este proyecto incluye un conjunto de herramientas para mantener la calidad del código:

### Pre-commit con Husky
- **Laravel Pint**: Formateador de código PHP (configuración por defecto)
- **Larastan (PHPStan)**: Análisis estático de código PHP (Nivel 5)
- **Gitmoji**: El repositorio está automatizado para solicitar un emoji descriptivo en cada commit usando gitmoji
- Se ejecuta automáticamente en cada commit

> 📝 **Nota sobre Gitmoji**: Al hacer commit, el sistema te solicitará automáticamente seleccionar un emoji que represente el tipo de cambio (✨ para features, 🐛 para fixes, 📝 para documentación, etc.). Esto mejora la legibilidad del historial de commits.

### Comandos Útiles

```bash
# Ejecutar todas las pruebas
vendor/bin/sail test

# Formatear código con Laravel Pint
vendor/bin/sail pint

# Análisis estático con Larastan
vendor/bin/sail php vendor/bin/phpstan analyse

# Detener contenedores
vendor/bin/sail down

# Ver logs en tiempo real
vendor/bin/sail logs -f

```

## Funcionalidades Principales

- ✅ **Autenticación de usuarios** (registro, login, logout)
- ✅ **Gestión completa de pacientes** (crear, editar, eliminar, listar)
  - Tipos de animales: gatos, perros, conejos
  - Filtros por tipo de animal
  - Búsqueda avanzada en tablas
  - Validación de fechas de nacimiento
- ✅ **Gestión de propietarios**
  - Creación inline desde formulario de pacientes
  - Información de contacto (nombre, email, teléfono)
  - Relación uno a muchos con pacientes
- ✅ **Gestión de tratamientos médicos**
  - Asociados a cada paciente
  - Registro de costos con manejo de moneda
  - Notas y descripciones detalladas
- ✅ **Dashboard con widgets estadísticos**
  - Resumen por tipo de paciente (contadores)
  - Gráfico de tendencias de tratamientos por mes
  - Widgets personalizables de Filament
- ✅ **Panel de administración Filament** completo
  - Interfaz moderna y responsiva
  - Navegación intuitiva
  - Acciones masivas (bulk actions)

## Estructura del Proyecto

### **Backend y Framework**
- **Backend**: Laravel 12 con PHP 8.4+
- **Panel Admin**: Filament 3.3
- **Base de datos**: MySQL (via Docker)
- **Autenticación**: Laravel Breeze integrado con Filament

### **Modelos y Entidades**
- **`Patient`**: Entidad central con tipos (cat/dog/rabbit), pertenece a Owner, tiene muchos Treatments
- **`Owner`**: Información de propietarios, tiene muchos Patients
- **`Treatment`**: Tratamientos médicos, pertenece a Patient, usa MoneyCast para precios
- **`User`**: Autenticación para acceso al panel administrativo

### **Arquitectura Filament**
- **Recursos**: `PatientResource` con páginas (List, Create, Edit) y relation managers
- **Widgets**:
  - `PatientTypeOverview`: Estadísticas por tipo de animal
  - `TreatmentsChart`: Gráfico de tendencias de tratamientos
- **Relation Managers**: `TreatmentsRelationManager` para gestionar tratamientos desde pacientes
- **Custom Casts**: `MoneyCast` para manejo correcto de precios en centavos

### **Testing y Calidad de Código**
- **Testing**: Pest PHP con soporte para Livewire
- **Factories**: Completas para todos los modelos (Patient, Owner, Treatment, User)
- **Tests Feature**: Cobertura completa de recursos y widgets
- **Calidad de código**: Laravel Pint + Larastan + Husky
- **Pre-commit con Husky**:
  - **Laravel Pint**: Formateador de código PHP (configuración por defecto)
  - **Larastan (PHPStan)**: Análisis estático de código PHP (Nivel 5)
  - **Gitmoji**: El repositorio está automatizado para solicitar un emoji descriptivo en cada commit usando gitmoji
  - Se ejecuta automáticamente en cada commit

> 📝 **Nota sobre Gitmoji**: Al hacer commit, el sistema te solicitará automáticamente seleccionar un emoji que represente el tipo de cambio (✨ para features, 🐛 para fixes, 📝 para documentación, etc.). Esto mejora la legibilidad del historial de commits.

## Licencia

Este proyecto está bajo la licencia MIT.
