# _Sistema_Inventario_mini

> **LÉAME — Sistema de Inventario y Facturación (`_Sistema_Inventario_mini`)**  
> Sistema web modular desarrollado en **PHP 8 con PDO** sobre la base de datos relacional **`db_inventory`** (**16 tablas**) en entorno **XAMPP** (**Apache** y **MariaDB/MySQL**), diseñado para la gestión de inventarios, catálogos, existencias, facturación, impresión de comprobantes, reportes, administración de usuarios/roles y personalización de marca.

---

## Datos del Desarrollador y Repositorio

* **Desarrollador:** **Omar Pinto**
* **Correo de soporte:** `op2020pk@gmail.com`
* **WhatsApp:** **+58 412-367-9938**
* **Ubicación:** La Victoria, Estado Aragua, Venezuela
* **Repositorio:** `git@github.com:op2020pk-ux/_Sistema_Inventario_mini.git`

---

## Arquitectura General y Base de Datos

El sistema utiliza una arquitectura **PHP** ligera con conexión centralizada mediante **PDO** en `config.php`, consultas preparadas, escape de salida con `e()`, manejo de mensajes flash en sesión, control de acceso por roles/permisos en base de datos y una estructura de **16 tablas** en `db_inventory` (`DB/inventory.sql`).

### Notas / Detalles del Módulo (Arquitectura y Base de Datos)

> **Colocar aquí:** *(Ruta local del proyecto, notas de `DB/inventory.sql`, observaciones de XAMPP o ajustes propios de tu instalación)*

---

## Acceso al Sistema y Seguridad (`auth.php`)

Gestiona el inicio de sesión (`login`), el cierre de sesión (`logout`) y el cambio de contraseña (`?accion=password`). Valida credenciales con `password_verify()`, almacena la sesión del usuario (`user_id`, `user_name`, `role_id`, `role_name`, `branch_id`) y presenta el panel de acceso alineado a la derecha con soporte para imagen de fondo a pantalla completa (`uploads/login_bg.*`).

### Notas / Detalles del Módulo (`auth.php`)

> **Colocar aquí:** *(Correo/usuario de prueba o administrador, captura/referencia de `auth.php`, observaciones de `uploads/login_bg.*`)*

---

## Panel Principal y Menú Dinámico (`index.php` y `config.php`)

Renderiza el panel principal tras iniciar sesión y construye el menú lateral según los permisos asignados al rol del usuario en `menus` y `permissions`. La función `ruta_a_archivo()` en `config.php` asocia cada `menu_url` con su archivo PHP o pestaña correspondiente.

### Notas / Detalles del Módulo (`index.php` y `config.php`)

> **Colocar aquí:** *(Rutas mapeadas en `ruta_a_archivo()`, IDs de menú en `db_inventory`, capturas o notas del panel)*

---

## Personas, Roles, Permisos y Empresa (`personas_config.php`)

Módulo multipestaña que centraliza la administración operativa y de configuración del sistema:

* **Clientes (`?tab=clientes`):** Registro, consulta y eliminación de clientes.
* **Usuarios (`?tab=usuarios`):** Creación de usuarios con contraseña cifrada (`PASSWORD_BCRYPT`), asignación de rol/sucursal y cambio de rol de usuarios existentes.
* **Roles y Permisos (`?tab=roles`):** Creación de roles y asignación de accesos a menús mediante checkboxes guardados en `permissions`.
* **Empresa (`?tab=empresa`):** Edición de datos de la empresa (`companies`), carga/reemplazo/eliminación de la imagen de fondo para `auth.php` (`uploads/login_bg.*`) y vista previa.

### Notas / Detalles del Módulo (`personas_config.php`)

> **Colocar aquí:** *(Notas de cada pestaña: Clientes, Usuarios, Roles y Permisos, Empresa; permisos de la carpeta `uploads/`)*

---

## Catálogos: Categorías y Proveedores (`catalogos.php`)

Administra los catálogos base del sistema para clasificar los artículos del inventario y registrar los proveedores que abastecen el negocio:

* **Categorías (`?tab=categorias`):** Organización de productos por categoría.
* **Proveedores (`?tab=proveedores`):** Registro y gestión de proveedores.

### Notas / Detalles del Módulo (`catalogos.php`)

> **Colocar aquí:** *(Categorías iniciales, datos de proveedores, observaciones o capturas del módulo)*

---

## Productos (`productos.php`)

Permite administrar el catálogo maestro de productos: registrar nuevos artículos, editar información, vincular categoría/proveedor, gestionar precios y consultar la lista de productos disponibles.

### Notas / Detalles del Módulo (`productos.php`)

> **Colocar aquí:** *(Campos clave del producto, criterios de precios, notas o capturas de `productos.php`)*

---

## Existencias / Stock (`existencias.php`)

Permite consultar y controlar las cantidades disponibles de cada producto en inventario/sucursal, apoyar el seguimiento de entradas, salidas y ajustes, y evitar ventas sin disponibilidad.

### Notas / Detalles del Módulo (`existencias.php`)

> **Colocar aquí:** *(Reglas de control de stock, sucursales/almacenes, notas o capturas de `existencias.php`)*

---

## Facturación e Impresión (`facturacion.php` e `imprimir.php`)

* **Facturación (`facturacion.php`):** Permite seleccionar cliente, agregar productos y cantidades, calcular totales y registrar la operación descontando las existencias correspondientes.
* **Impresión (`imprimir.php`):** Genera la vista de comprobante o factura lista para imprimir o revisar.

### Notas / Detalles del Módulo (`facturacion.php` e `imprimir.php`)

> **Colocar aquí:** *(Formato de comprobante, comportamiento de descuento de stock, capturas o notas de impresión)*

---

## Reportes (`reportes.php`)

Proporciona consultas, listados y resúmenes operativos para revisar el movimiento del sistema, ventas, existencias o registros almacenados en `db_inventory`.

### Notas / Detalles del Módulo (`reportes.php`)

> **Colocar aquí:** *(Filtros o reportes utilizados con mayor frecuencia, notas o capturas de `reportes.php`)*

---

## Información — Desarrollador (`desarrollador.php`)

Muestra la **Ficha Profesional** del desarrollador **Omar Pinto**, su perfil técnico, stack tecnológico, canales de contacto directo (correo `op2020pk@gmail.com` y WhatsApp `+58 412-367-9938`), ubicación en **La Victoria** (**Estado Aragua, Venezuela**), bloque de reconocimiento y datos del entorno/catálogo.

### Notas / Detalles del Módulo (`desarrollador.php`)

> **Colocar aquí:** *(Datos de contacto, enlaces personalizados, notas o capturas de `desarrollador.php`)*