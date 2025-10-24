## Control de Gastos Personales

Aplicación web desarrollada en PHP siguiendo la arquitectura MVC para gestionar ingresos, gastos y metas financieras. El frontend utiliza Tailwind CSS con una paleta basada en tonos azules y componentes personalizables. Incluye autenticación segura, configuración de perfil financiero, registro de movimientos, reportes dinámicos y alertas inteligentes.

### Requerimientos previos

- PHP 8.1 o superior con extensión PDO y soporte para MySQL
- Servidor web (Apache recomendado) con `mod_rewrite` activo
- MySQL 5.7+ o MariaDB 10.2+ (por compatibilidad con tipo JSON)
- Node.js 18+ (opcional si se decide compilar Tailwind de forma local)

### Instalación

1. Clonar el repositorio en el directorio del servidor (`htdocs` para XAMPP).
2. Crear la base de datos y ejecutar el script `database/schema.sql`.
3. Configurar credenciales de conexión en `config/config.php`.
4. Configurar un host virtual apuntando a `public/` o acceder via `http://localhost/App-Control-Gastos/public`.

### Estructura principal

- `app/core`: Clases base (Router, Controller, Session, Database, Auth).
- `app/controllers`: Controladores de cada módulo.
- `app/models`: Modelos y repositorios de datos.
- `app/services`: Servicios como envío de correos, generación de reportes y alertas.
- `views`: Vistas con componentes reutilizables y layouts.
- `public`: Punto de entrada (`index.php`), assets, y `.htaccess`.
- `storage`: Archivos generados como logs y correos almacenados localmente.

### Roadmap de desarrollo

- [x] Configuración inicial, bootstrap y layout principal con Tailwind.
- [ ] Módulo de autenticación y recuperación de contraseñas.
- [ ] Configuración y gestión del perfil financiero.
- [ ] Registro y control de transacciones.
- [ ] Reportes, estadísticas y exportación de datos.
- [ ] Alertas, recordatorios y pulido final de experiencia de usuario.
