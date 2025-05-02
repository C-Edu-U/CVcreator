# CVcreator

**CVcreator** es una aplicación web desarrollada en PHP orientado a objetos, con base de datos MariaDB y una interfaz moderna con Bootstrap. Permite a los usuarios generar currículums profesionales con formato tipo Harvard, exportarlos a PDF y organizarlos por secciones dinámicas.

---

## 🚀 Funcionalidades principales

- Gestión de usuarios con roles (admin y usuario normal)
- Creación de CVs personalizados con:
  - Título
  - Datos de contacto
  - Página web o LinkedIn
  - Personal statement
- Agregado de secciones organizadas (educación, experiencia, proyectos, etc.)
- Generación automática de bullets con fechas
- Visualización en HTML y exportación a PDF
- Lector RSS para historial de usuarios (solo admins)
- Acceso individual o global según el rol

---

## 🧰 Tecnologías utilizadas

- **PHP 8.x** (Programación Orientada a Objetos)
- **MariaDB** o MySQL
- **Bootstrap 5**
- **SweetAlert2**
- **DataTables**
- **Dompdf** (para exportación a PDF)
- **Docker** (opcional)
- **Composer** (gestión de dependencias)

---

## ⚙️ Instalación

1. **Clona el repositorio**

```bash
git clone https://github.com/C-Edu-U/CVcreator.git
cd CVcreator
````

2. **Instala dependencias con Composer**

```bash
composer install
```

3. **Configura la conexión a tu base de datos**

Edita el archivo `db.php` y modifica los parámetros de conexión:

```php
$host = 'localhost';
$dbname = 'cv_generator';
$username = 'root';
$password = 'tu_contraseña';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
```

---

## 🗃️ Estructura de la base de datos

### Tabla `users`

| Campo         | Tipo         | Detalles                     |
| ------------- | ------------ | ---------------------------- |
| id\_user      | INT          | PRIMARY KEY, AUTO\_INCREMENT |
| nickname      | VARCHAR(50)  | Nombre de usuario único      |
| password      | VARCHAR(255) | Contraseña (hash opcional)   |
| account\_type | VARCHAR(50)  | 'admin' o 'user'             |
| email         | VARCHAR(100) | Correo del usuario           |
| id\_role      | INT          | FK hacia `roles`             |

---

### Tabla `roles`

| Campo    | Tipo        | Detalles                       |
| -------- | ----------- | ------------------------------ |
| id\_role | INT         | PRIMARY KEY, AUTO\_INCREMENT   |
| nombre   | VARCHAR(50) | Nombre del rol ('admin', etc.) |

---

### Tabla `cvs`

| Campo               | Tipo         | Detalles                     |
| ------------------- | ------------ | ---------------------------- |
| id\_cv              | INT          | PRIMARY KEY, AUTO\_INCREMENT |
| title               | VARCHAR(150) | Título del CV                |
| id\_user            | INT          | FK hacia `users`             |
| contacto            | TEXT         | Información de contacto      |
| website             | VARCHAR(255) | Página web / LinkedIn        |
| personal\_statement | TEXT         | Perfil profesional           |
| fecha\_creacion     | DATETIME     | DEFAULT CURRENT\_TIMESTAMP   |

---

### Tabla `sections`

| Campo           | Tipo        | Detalles                             |
| --------------- | ----------- | ------------------------------------ |
| id\_section     | INT         | PRIMARY KEY, AUTO\_INCREMENT         |
| type            | VARCHAR(50) | Tipo de sección (educacion, etc.)    |
| content         | TEXT        | Contenido estructurado de la sección |
| position\_order | INT         | Orden opcional                       |
| id\_cv          | INT         | FK hacia `cvs`                       |

---

## 📦 Docker (opcional)

Puedes configurar el sistema con Docker y MariaDB editando un `docker-compose.yml` personalizado.

---

## 📄 Licencia

Este proyecto está bajo licencia MIT. Eres libre de usarlo, modificarlo y distribuirlo.

---

## 👨‍💻 Autor

Carlos Eduardo
[GitHub: @C-Edu-U](https://github.com/C-Edu-U)

