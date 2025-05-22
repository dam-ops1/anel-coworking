📄 README.md (actualizado con sección sobre CodeIgniter)
markdown
Copiar
Editar
# 🏢 Intraconecta

Esta es una aplicación web desarrollada en PHP usando el framework **CodeIgniter**, diseñada para gestionar un espacio de coworking. Permite a los usuarios registrarse, iniciar sesión y realizar reservas de salas.

## 🚀 Funcionalidades

- **Registro de usuarios**
- **Inicio de sesión**
- **Gestión de autenticación**
- **Reserva de salas**
- **Listado de salas disponibles**
- **Paneles de usuario**

## 🛠️ Tecnologías utilizadas

- **Backend**: PHP (CodeIgniter)
- **Base de datos**: MySQL
- **Framework**: CodeIgniter 4
- **Arquitectura**: MVC (Modelo - Vista - Controlador)

## 🧭 ¿Cómo funciona CodeIgniter?

CodeIgniter 4 es un framework MVC (Modelo - Vista - Controlador) que organiza el código en tres capas principales:

- **Modelos (`app/Models`)**: Se encargan de interactuar con la base de datos.
- **Controladores (`app/Controllers`)**: Contienen la lógica del negocio y gestionan las solicitudes HTTP.
- **Vistas (`app/Views`)**: Archivos HTML que presentan la información al usuario.

### Estructura típica de una solicitud

1. El usuario accede a una URL (por ejemplo: `/rooms`).
2. CodeIgniter busca en `app/Config/Routes.php` para ver qué controlador debe manejarla.
3. El controlador correspondiente ejecuta su método (por ejemplo, `RoomController::index()`).
4. Si es necesario, el controlador usa un modelo (como `RoomModel`) para obtener datos.
5. Luego pasa esos datos a una vista (`Views/rooms/index.php`) para mostrarlos.

### Archivos clave

- `public/index.php`: punto de entrada de la aplicación.
- `.env`: configuración sensible (baseURL, base de datos).
- `app/Config/Database.php`: configuración de la conexión a la base de datos.
- `app/Config/Routes.php`: definición de rutas personalizadas.

Puedes aprender más en la [guía oficial de CodeIgniter](https://codeigniter.com/user_guide/).

## 📁 Estructura del proyecto

```plaintext
app/
├── Config/           # Configuración del framework
├── Controllers/      # Lógica de negocio y rutas
├── Models/           # Acceso a la base de datos
├── Views/            # Plantillas HTML
```
## 🔧 Controladores principales
| Controlador         | Propósito                                              |
|---------------------|--------------------------------------------------------|
| `AuthController`    | Registro, inicio de sesión y cierre de sesión         |
| `BookingController` | Crear y gestionar reservas de salas                   |
| `DashboardController` | Panel principal del usuario                         |
| `RoomController`    | Gestión de salas (crear, listar, editar, eliminar)    |
| `UserController`    | Administración de usuarios (perfil, edición, etc.)    |
| `ProfileController` | Modificación de información del usuario               |
| `EmailController`   | Envío de correos electrónicos                         |
| `MessageController` | Sistema de mensajería (contacto interno o externo)    |

## 🗄️ Modelos de datos
| Modelo       | Tabla relacionada | Descripción                        |
|--------------|-------------------|----------------------------------|
| UserModel    | users             | Información de los usuarios       |
| RoomModel    | rooms             | Detalles de las salas             |
| BookingModel | bookings          | Reservas realizadas por los usuarios |

## ⚙️ Instalación y configuración
1. Clona o descarga el proyecto.
2. Coloca los archivos en el directorio raíz de tu servidor (por ejemplo, `htdocs` o `www`).
3. Copia el archivo `env` a `.env` y ajusta el `baseURL` y la base de datos.
4. Configura la conexión a la base de datos en `app/Config/Database.php`.
5. Crea las tablas en tu base de datos MySQL según el modelo anterior.
6. Asegúrate de apuntar tu servidor web a la carpeta `public`.
7. Accede desde tu navegador en `http://localhost/tu_proyecto/public`.

## 📌 Rutas comunes (ejemplo)
| Ruta           | Método HTTP | Acción                          |
|----------------|-------------|--------------------------------|
| /login         | GET/POST   | Mostrar formulario / Iniciar sesión |
| /register      | GET/POST   | Registro de usuario             |
| /dashboard     | GET        | Panel principal del usuario     |
| /rooms         | GET        | Listar salas                   |
| /rooms/create  | POST       | Crear nueva sala               |
| /bookings      | GET/POST   | Ver o crear reservas           |
| /logout        | GET        | Cerrar sesión                  |


## 🔒 Seguridad
- **Validación de formularios del lado del servidor**

- **Protección CSRF incluida por CodeIgniter**

- **Gestión de sesiones**

## 🧪 Estado actual del proyecto
Actualmente implementado:

- Registro/login de usuarios

- Listado y reserva de salas

- Futuras mejoras (sugeridas):

- Roles de administrador

- Gestión de disponibilidad de salas

- Notificaciones por correo

- Integración con calendarios

## 👨‍💻 Autores
Este proyecto fue desarrollado como solución para gestionar un espacio de coworking moderno, enfocado en la simplicidad y eficiencia del uso compartido de recursos.
