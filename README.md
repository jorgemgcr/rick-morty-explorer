# 🚀 Rick & Morty Character Explorer

Una aplicación web moderna desarrollada en Laravel que permite explorar el universo de Rick & Morty con un sistema completo de búsqueda, filtros, paginación e internacionalización.

## 🌐 Demo en Vivo

**🔗 [Ver aplicación desplegada](https://rick-morty-explorer.onrender.com/characters)**

La aplicación está desplegada en Render y lista para usar. ¡Explora el multiverso de Rick & Morty ahora mismo!

## ✨ Características

- 🌍 **Bilingüe**: Soporte completo para Español e Inglés
- 🔍 **Búsqueda avanzada**: Filtros por nombre, estado y especie
- 📱 **Responsive**: Diseño adaptativo para todos los dispositivos
- 🎨 **UI Moderna**: Interfaz profesional con Tailwind CSS
- ⚡ **API Integration**: Consumo de la API oficial de Rick & Morty
- 🔄 **Paginación**: Navegación eficiente entre páginas
- 🎭 **Modal de detalles**: Vista completa de cada personaje
- 🎯 **Filtros dinámicos**: Especies cargadas automáticamente
- 💾 **Persistencia**: Datos almacenados en base de datos local

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12.28.1
- **Frontend**: Blade Templates + Tailwind CSS v4
- **Base de datos**: SQLite
- **JavaScript**: Vanilla JS (ES6+)
- **API**: Rick and Morty API
- **Servidor**: PHP 8.3.16

## 📋 Requisitos Previos

- PHP >= 8.1
- Composer
- Node.js & NPM
- SQLite (incluido en PHP)

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/jorgemgcr/rick-morty-explorer.git
cd prueba-tecnica
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar variables de entorno

```bash
cp .env.example .env
```

Edita el archivo `.env` y configura:

```env
APP_NAME="Rick & Morty Explorer"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Crear base de datos SQLite

```bash
# En Windows (Laragon)
New-Item -Path "database\database.sqlite" -ItemType File -Force

# En Linux/Mac
touch database/database.sqlite
```

### 7. Ejecutar migraciones

```bash
php artisan migrate
```

### 8. Compilar assets frontend

```bash
npm run build
```

## 🗄️ Configuración de Base de Datos

### Estructura de la tabla `characters`

La aplicación utiliza una tabla `characters` con los siguientes campos:

- `id` - ID único del personaje
- `name` - Nombre del personaje
- `status` - Estado (Alive, Dead, unknown)
- `species` - Especie (Human, Alien, etc.)
- `type` - Tipo específico
- `gender` - Género (Male, Female, Genderless, unknown)
- `origin_name` - Nombre del origen
- `origin_url` - URL del origen
- `location_name` - Nombre de la ubicación
- `location_url` - URL de la ubicación
- `image_url` - URL de la imagen
- `episode_urls` - Array de URLs de episodios
- `url` - URL única del personaje en la API
- `created_at_api` - Fecha de creación en la API
- `created_at` - Fecha de creación local
- `updated_at` - Fecha de actualización
- `deleted_at` - Soft delete

## 📊 Poblar Datos

### Opción 1: Desde la interfaz web

1. Inicia el servidor: `php artisan serve`
2. Ve a `http://localhost:8000/characters`
3. Haz clic en el botón **"Obtener Personajes"**
4. Los datos se cargarán automáticamente desde la API

### Opción 2: Desde la línea de comandos

```bash
# Obtener todos los personajes
curl -X GET "http://localhost:8000/characters/fetch"

# Obtener personajes con paginación
curl -X GET "http://localhost:8000/characters/api?page=1&per_page=20"
```

## 🌐 Endpoints de la API

### Personajes

| Método | Endpoint | Descripción | Parámetros |
|--------|----------|-------------|------------|
| `GET` | `/characters` | Vista principal de la aplicación | - |
| `GET` | `/characters/fetch` | Obtener personajes de la API de Rick & Morty | - |
| `GET` | `/characters/api` | Listar personajes con paginación y filtros | `page`, `per_page`, `search`, `status`, `species` |
| `GET` | `/characters/{id}` | Obtener detalles de un personaje específico | `id` |

### Idioma

| Método | Endpoint | Descripción | Parámetros |
|--------|----------|-------------|------------|
| `POST` | `/language/change` | Cambiar idioma de la aplicación | `lang` (es/en) |
| `GET` | `/language/translations` | Obtener traducciones para JavaScript | - |

### Ejemplos de uso

```bash
# Obtener personajes con filtros
curl -X GET "http://localhost:8000/characters/api?search=rick&status=Alive&species=Human&page=1&per_page=10"

# Cambiar idioma a inglés
curl -X POST "http://localhost:8000/language/change" \
  -H "Content-Type: application/json" \
  -d '{"lang": "en"}'

# Obtener traducciones
curl -X GET "http://localhost:8000/language/translations"
```

## 🚀 Iniciar Servidor Local

### Opción 1: Servidor de desarrollo de Laravel

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

### Opción 2: Con puerto personalizado

```bash
php artisan serve --port=8080
```

### Opción 3: Con host específico

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## 🌍 Internacionalización

La aplicación soporta dos idiomas:

- **Español** (por defecto)
- **Inglés**

### Archivos de traducción

- `resources/lang/es/messages.php` - Traducciones en español
- `resources/lang/en/messages.php` - Traducciones en inglés

### Cambiar idioma

El idioma se puede cambiar desde:
1. **Interfaz web**: Selector de idioma en la esquina superior derecha
2. **API**: Endpoint `POST /language/change`

## 🎨 Personalización

### Estilos

Los estilos se encuentran en:
- `resources/css/app.css` - Estilos principales con Tailwind CSS
- `tailwind.config.js` - Configuración de Tailwind

### Traducciones

Para agregar nuevas traducciones:

1. Edita los archivos en `resources/lang/{idioma}/messages.php`
2. Agrega las claves necesarias en el controlador `LanguageController`
3. Recarga la página para ver los cambios

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

## 📁 Estructura del Proyecto

```
prueba-tecnica/
├── app/
│   ├── Http/Controllers/
│   │   ├── CharacterController.php
│   │   └── LanguageController.php
│   ├── Models/
│   │   └── Character.php
│   └── Helpers/
│       └── TranslationHelper.php
├── database/
│   ├── migrations/
│   └── database.sqlite
├── resources/
│   ├── lang/
│   │   ├── es/messages.php
│   │   └── en/messages.php
│   ├── views/
│   │   └── characters.blade.php
│   └── css/app.css
├── routes/
│   └── web.php
└── README.md
```

## 🐛 Solución de Problemas

### Error: "Database file not found"

```bash
# Crear archivo de base de datos
New-Item -Path "database\database.sqlite" -ItemType File -Force

# Verificar permisos
php artisan config:clear
php artisan cache:clear
```

### Error: "Class not found"

```bash
# Regenerar autoload
composer dump-autoload
```

### Error: "Translation not found"

```bash
# Limpiar cache de configuración
php artisan config:clear
php artisan cache:clear
```

## 🚀 Deployment

### Aplicación en Producción

La aplicación está desplegada en **Render** y disponible en:

**🔗 [https://rick-morty-explorer.onrender.com/characters](https://rick-morty-explorer.onrender.com/characters)**

### Características del Deploy

- ✅ **HTTPS habilitado** para seguridad
- ✅ **Docker containerizado** para consistencia
- ✅ **Base de datos SQLite** persistente
- ✅ **Assets compilados** automáticamente
- ✅ **Carga automática** de datos de la API
- ✅ **Cache optimizado** para mejor performance

### Tecnologías de Deploy

- **Plataforma**: Render
- **Containerización**: Docker
- **Base de datos**: SQLite
- **Servidor web**: PHP built-in server
- **Assets**: Vite + Tailwind CSS

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor Jorge Mugica Coria

Desarrollado como prueba técnica para demostrar habilidades en:
- Laravel Framework
- Frontend moderno con Tailwind CSS
- Integración de APIs
- Internacionalización
- Arquitectura de software

---

**¡Disfruta explorando el multiverso de Rick & Morty!** 🚀👽