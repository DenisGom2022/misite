# Mi Blog Personal - PHP

Un blog simple y funcional creado con PHP y MySQL.

## Características

- ✅ Página principal con listado de posts
- ✅ Visualización individual de posts
- ✅ Panel de administración para crear/eliminar posts
- ✅ Diseño responsivo
- ✅ Base de datos MySQL
- ✅ Interfaz limpia y moderna

## Estructura del Proyecto

```
misite/
├── index.php          # Página principal del blog
├── post.php           # Visualización individual de posts
├── admin.php          # Panel de administración
├── config.php         # Configuración de la base de datos
├── styles.css         # Estilos CSS
├── database.sql       # Script de la base de datos
└── README.md          # Este archivo
```

## Instalación

### Prerrequisitos
- Servidor web con PHP (XAMPP, WAMP, LAMP, etc.)
- MySQL/MariaDB
- PHP 7.0 o superior

### Pasos de instalación

1. **Copia los archivos** al directorio de tu servidor web
2. **Configura la base de datos:**
   - Abre phpMyAdmin o tu cliente MySQL preferido
   - Importa el archivo `database.sql` o ejecuta su contenido
3. **Configura la conexión** (si es necesario):
   - Edita `config.php` con tus datos de conexión a la base de datos
4. **¡Listo!** Visita tu sitio web

## Uso

### Página Principal
- Visita `index.php` para ver el blog
- Los posts más recientes aparecen primero
- Haz clic en cualquier título para ver el post completo

### Panel de Administración
- Visita `admin.php` para gestionar el blog
- Crear nuevos posts con título y contenido
- Ver y eliminar posts existentes

## Configuración

### Base de Datos
Edita `config.php` para cambiar la configuración:

```php
define('DB_HOST', 'localhost');     // Servidor de base de datos
define('DB_NAME', 'blog_db');       // Nombre de la base de datos
define('DB_USER', 'root');          // Usuario de la base de datos
define('DB_PASS', '');              // Contraseña de la base de datos
```

### Sitio Web
También puedes cambiar el nombre y URL del sitio:

```php
define('SITE_NAME', 'Mi Blog Personal');
define('SITE_URL', 'http://localhost/misite');
```

## Características Técnicas

- **Frontend**: HTML5, CSS3 (Responsive Design)
- **Backend**: PHP 7+
- **Base de Datos**: MySQL con PDO
- **Seguridad**: Preparación de consultas SQL para prevenir inyección SQL
- **Validación**: Sanitización de datos de entrada y salida

## Funcionalidades Futuras

Puedes extender este blog añadiendo:
- Sistema de comentarios
- Categorías y etiquetas
- Sistema de usuarios y autenticación
- Editor WYSIWYG
- Búsqueda de posts
- Paginación
- Subida de imágenes
- SEO optimizado

## Personalización

### Estilos
Edita `styles.css` para cambiar:
- Colores del tema
- Tipografía
- Layout y espaciado
- Efectos y animaciones

### Funcionalidad
- Añade nuevos campos a la tabla `posts`
- Crea nuevas páginas PHP
- Implementa nuevas características

## Solución de Problemas

### Error de conexión a la base de datos
1. Verifica que MySQL esté ejecutándose
2. Confirma los datos de conexión en `config.php`
3. Asegúrate de que la base de datos existe

### Página en blanco
1. Activa la visualización de errores PHP
2. Revisa los logs del servidor
3. Verifica los permisos de archivos

## Soporte

Si tienes alguna pregunta o problema:
1. Revisa este README
2. Verifica la configuración de tu servidor
3. Consulta la documentación de PHP y MySQL

¡Disfruta de tu nuevo blog! 🎉