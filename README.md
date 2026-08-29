# Sistema de Gestión de Inventario (PHP + MySQL)

### Requisitos
- Servidor web Apache con soporte PHP (XAMPP, WAMP o MAMP).
- Servidor MySQL/MariaDB.

### Instrucciones de Instalación
1. Clonar o descomprimir la carpeta dentro del directorio `htdocs` de XAMPP.
2. Crear una base de datos en phpMyAdmin (ej: `sistema_stock`).
3. Importar el archivo `db.sql` incluido en el paquete.
4. Ajustar los credenciales de conexión en `conexion.php` si fuera necesario.
5. Acceder desde el navegador a `http://localhost/panel_control_stock`.

### Funcionalidades
- CRUD completo de productos (Agregar, Editar, Eliminar, Listar).
- Manejo dinámico de stock con indicadores visuales por estado.
- Búsqueda multi-palabra sobre campos de nombre y categoría.
- Interfaz adaptable y limpia sin dependencias externas pesadas.