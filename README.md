# 🌸 Roséa Boutique — Fase 4 (ISW-306)

## Descripción del proyecto

**Roséa Boutique** es una tienda virtual de ropa y accesorios de estilo coquette, desarrollada como proyecto académico de la asignatura **Desarrollo de Aplicaciones Web (ISW-306)** de la Universidad Abierta para Adultos (UAPA).

En esta cuarta fase se integró el framework **CodeIgniter 4**, utilizando PHP y MySQL/MariaDB para desarrollar una aplicación web dinámica basada en la arquitectura MVC (Modelo, Vista y Controlador).

El sistema permite administrar usuarios, productos, inventario, compras y pedidos mediante una interfaz moderna, intuitiva y adaptable.

**Importante:** Este repositorio contiene el proyecto Roséa Boutique integrado con CodeIgniter 4. Las dependencias externas se instalan mediante Composer, por lo que no es necesario crear nuevamente el proyecto utilizando App Starter.

La aplicación fue ejecutada y probada localmente con XAMPP. La instalación desde una copia limpia del repositorio se encuentra pendiente de comprobación final.

---

## 1. Tecnologías utilizadas

| Tecnología | Función |
|---|---|
| HTML5 | Estructura de las páginas |
| CSS3 | Diseño de la interfaz |
| JavaScript | Interactividad |
| PHP 8.2 | Desarrollo del servidor |
| CodeIgniter 4 | Framework MVC |
| MySQL/MariaDB | Base de datos |
| XAMPP | Servidor local |
| Composer | Gestión de dependencias |
| Visual Studio Code | Editor de código |
| Git y GitHub | Control de versiones |

### Requisitos del sistema

- Windows 10 u 11.
- XAMPP con Apache y MySQL/MariaDB.
- PHP 8.2 o superior, compatible con las dependencias del proyecto.
- Composer instalado.
- Git instalado.
- Navegador web actualizado.
- Extensiones PHP `intl`, `mbstring`, `mysqli` y `fileinfo`.
- Módulo `mod_rewrite` habilitado en Apache.

Para verificar PHP y Composer:

```powershell
php -v
composer --version
php -m
```

---

## 2. Instalación del proyecto en XAMPP

### Paso 1. Preparar XAMPP

Instalar XAMPP y comprobar que Apache y MySQL funcionan correctamente.

La carpeta de los proyectos web normalmente se encuentra en:

`C:\xampp\htdocs`

### Paso 2. Clonar el repositorio

Abrir PowerShell y ejecutar:

```powershell
cd C:\xampp\htdocs
git clone --branch fase-4-despliegue https://github.com/XxSHIROKINGxX/fase-1-maquetacion.git rosea_ci4
cd rosea_ci4
```

Este procedimiento descarga la rama correspondiente a la Fase 4, una vez publicada en GitHub.

**Nota:** Si ya existe la carpeta `rosea_ci4`, debe conservarse y respaldarse. No se debe sobrescribir la instalación original que funciona en XAMPP.

### Paso 3. Instalar dependencias

Desde la carpeta principal del proyecto ejecutar:

```powershell
composer install
```

Composer instalará las dependencias definidas en los archivos `composer.json` y `composer.lock`.

La carpeta `vendor` se genera durante la instalación y no necesita publicarse en GitHub.

### Paso 4. Configurar el archivo .env

En una instalación nueva, ejecutar:

```powershell
Copy-Item .env.example .env
```

Abrir el archivo `.env` y configurar los datos correspondientes al servidor local:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/rosea_ci4/public/'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = rosea_boutique_fase4
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

Estos valores corresponden a una configuración habitual de XAMPP.

Si MySQL utiliza otra contraseña o puerto, deben modificarse según la configuración del equipo.

**Seguridad:** No publicar el archivo `.env` en GitHub porque puede contener información privada.

### Paso 5. Preparar los directorios de CodeIgniter

Ejecutar en PowerShell:

```powershell
New-Item -ItemType Directory -Force -Path writable,writable\cache,writable\logs,writable\session,writable\uploads,writable\debugbar,writable\comprobantes | Out-Null
```

Estos directorios permiten almacenar sesiones, archivos temporales, registros y comprobantes.

La carpeta `writable` se mantiene excluida de Git por seguridad.

### Paso 6. Importar la base de datos

1. Abrir XAMPP Control Panel.
2. Iniciar Apache y MySQL.
3. Entrar a `http://localhost/phpmyadmin`.
4. Seleccionar la opción **Importar**.
5. Buscar el archivo `database/rosea_demo.sql`.
6. Ejecutar la importación.
7. Comprobar que se creó la base de datos `rosea_boutique_fase4`.

Las tablas principales del sistema son:

- `usuarios`
- `productos`
- `pedidos`
- `detalle_pedido`

El archivo contiene datos ficticios destinados exclusivamente a demostraciones académicas.

La importación debe realizarse preferiblemente en una base de datos nueva para evitar conflictos con registros existentes.

### Paso 7. Ejecutar la aplicación

Con Apache y MySQL activos, abrir:

`http://localhost/rosea_ci4/public/`

Para visualizar directamente el catálogo:

`http://localhost/rosea_ci4/public/productos`

Si existen problemas con las rutas, comprobar la configuración de `.htaccess`, `mod_rewrite` y Apache.

### Servidor alternativo de CodeIgniter

También es posible ejecutar:

```powershell
php spark serve
```

En ese caso, configurar temporalmente la URL base como `http://localhost:8080/` y acceder desde el navegador, manteniendo MySQL activo.

---

## 3. Arquitectura del sistema

Roséa Boutique utiliza la arquitectura **MVC (Modelo, Vista y Controlador)**.

### Modelos

Administran las consultas y operaciones de la base de datos.

Ubicación: `app/Models`

### Vistas

Contienen la interfaz visual, los formularios y las páginas utilizadas por los clientes y administradores.

Ubicación: `app/Views`

### Controladores

Procesan las solicitudes del usuario, aplican validaciones, gestionan permisos y coordinan las operaciones de la aplicación.

Ubicación: `app/Controllers`

### Rutas

Definen las direcciones de acceso a las funcionalidades.

Archivo: `app/Config/Routes.php`

La aplicación también utiliza los servicios integrados de CodeIgniter para administrar sesiones, conexiones de bases de datos y respuestas HTTP.

---

## 4. Estructura del proyecto

```text
rosea_ci4/
│
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Filters/
│
├── public/
│   ├── assets/
│   │   └── css/
│   ├── img/
│   └── index.php
│
├── database/
│   └── rosea_demo.sql
│
├── documentacion/
│   ├── GUIA_PRUEBAS.md
│   ├── GUION_VIDEO.md
│   └── INFORME_BORRADOR.md
│
├── tests/
├── writable/
├── vendor/
│
├── composer.json
├── composer.lock
├── .env.example
├── .gitignore
├── README.md
└── spark
```

Las dependencias de `vendor` se instalan localmente y los directorios privados de `writable` se preparan durante la configuración.

El repositorio conserva también los archivos HTML, CSS y JavaScript correspondientes a las fases anteriores.

---

## 5. Catálogo de productos

La tienda cuenta con un catálogo dinámico conectado a MySQL.

Los productos se muestran con fotografías, descripciones, precios y disponibilidad.

### Productos utilizados durante las pruebas

| Producto | Categoría | Precio |
|---|---|---|
| Lazo Clásico | Accesorios | RD$250.00 |
| Falda Romance | Faldas | RD$1,200.00 |
| Blusa Rosé | Blusas | RD$850.00 |
| Vestido Rosa Elegance | Vestidos | RD$1,900.00 |
| Top Rosalie | Blusas | RD$800.00 |

Las imágenes están almacenadas en `public/img/`.

El panel administrativo permite agregar productos, editar información, modificar precios y actualizar las existencias.

El archivo SQL de demostración incluye las instrucciones del catálogo y el producto Top Rosalie. Su importación completa debe verificarse en una base de datos nueva.

---

## 6. Gestión de usuarios y autenticación

El sistema permite registrar usuarios mediante nombre, apellido, correo electrónico y contraseña.

Las contraseñas se almacenan mediante hashes en lugar de texto plano.

### Inicio de sesión

Los usuarios registrados pueden acceder a la aplicación utilizando su correo electrónico y contraseña.

### Perfil de usuario

Cada cliente puede visualizar sus datos personales y consultar el historial de pedidos.

### Roles

**Cliente:** puede consultar productos, realizar compras y visualizar sus propios pedidos.

**Administrador:** puede gestionar productos, usuarios, pedidos y estados de pago.

La aplicación incorpora comprobaciones de autorización en el servidor para restringir las operaciones administrativas.

---

## 7. Cuentas ficticias de demostración

| Rol | Correo electrónico | Contraseña |
|---|---|---|
| Administrador | admin.demo@rosea.test | RoseaDemo2026! |
| Cliente | cliente.demo@rosea.test | ClienteDemo2026! |

Estas cuentas pertenecen exclusivamente al entorno académico de demostración.

No deben utilizarse con información real ni en un sitio público de producción.

---

## 8. Gestión de compras e inventario

El sistema permite registrar compras realizadas por usuarios autenticados.

### Proceso de compra

1. Iniciar sesión.
2. Seleccionar un producto.
3. Elegir la cantidad.
4. Completar los datos de entrega.
5. Seleccionar el método de pago.
6. Confirmar el pedido.
7. Registrar la compra en la base de datos.

### Control de inventario

Antes de registrar una compra, el sistema debe verificar la disponibilidad del producto.

Cuando una compra se registra correctamente, se actualizan las existencias.

Los pedidos y sus productos asociados se almacenan en las tablas correspondientes.

### Historial de compras

Los clientes pueden consultar los pedidos realizados, incluyendo su fecha, estado, productos, cantidades y total.

Los pedidos se encuentran asociados al usuario propietario.

---

## 9. Panel de administración

El sistema incorpora un panel para gestionar las funciones administrativas.

### Administración de productos

Permite:

- Agregar productos.
- Modificar nombres y descripciones.
- Cambiar precios.
- Actualizar el stock.
- Incorporar fotografías.
- Ocultar productos sin eliminar su historial.

### Administración de pedidos

Permite visualizar pedidos y actualizar sus estados:

- Pendiente.
- Procesando.
- Enviado.
- Entregado.
- Cancelado.

También permite consultar y modificar el estado de pago.

### Administración de usuarios

Permite visualizar los usuarios registrados y modificar sus roles según los permisos establecidos.

---

## 10. Rutas principales

| Ruta | Acceso | Funcionalidad |
|---|---|---|
| `/` | Público | Página principal |
| `/productos` | Público | Catálogo |
| `/registro` | Público | Registro de usuario |
| `/login` | Público | Inicio de sesión |
| `/perfil` | Autenticado | Perfil e historial |
| `/pedidos/{id}` | Usuario autorizado | Detalles del pedido |
| `/checkout/{id}` | Autenticado | Proceso de compra |
| `POST /pedidos` | Autenticado | Registrar compra |
| `POST /pedidos/{id}/comprobante` | Propietario autorizado | Enviar comprobante |
| `/admin` | Administrador | Panel principal |
| `/admin/productos` | Administrador | Gestión de productos |
| `/admin/pedidos` | Administrador | Gestión de pedidos |
| `/admin/usuarios` | Administrador | Gestión de usuarios |

Las rutas se interpretan a partir de la URL base configurada en el proyecto.

---

## 11. Seguridad de la aplicación

La aplicación utiliza mecanismos de seguridad y autorización como:

- Autenticación mediante sesiones.
- Separación de permisos por roles.
- Restricción de acceso al panel administrativo.
- Comprobación de propiedad de los pedidos.
- Almacenamiento seguro de contraseñas mediante hashes.
- Validación de información en los formularios.
- Protección CSRF en los formularios configurados.
- Separación entre archivos públicos y privados.
- Configuración local de credenciales mediante `.env`.

### Archivos excluidos del repositorio

El archivo `.gitignore` incorpora reglas para impedir la publicación de datos privados:

```gitignore
/.env
/.env.*
!/.env.example
/vendor/
/writable/
/public/uploads/
/public/comprobantes/
```

Antes de publicar deben revisarse los archivos incluidos para evitar divulgar contraseñas reales, información personal, comprobantes privados o respaldos confidenciales.

**Nota:** `.gitignore` no elimina información que ya haya sido publicada en commits anteriores.

---

## 12. Pruebas de funcionamiento

Durante el desarrollo se realizaron pruebas manuales utilizando XAMPP y MySQL.

### Funciones comprobadas mediante pruebas y capturas

- [x] Visualización del catálogo y fotografías.
- [x] Registro de cuentas.
- [x] Inicio de sesión.
- [x] Visualización del perfil.
- [x] Registro de compras.
- [x] Consulta del historial de pedidos.
- [x] Visualización de detalles de pedidos.
- [x] Actualización de existencias después de una compra.
- [x] Registro de nuevos productos.
- [x] Modificación de productos y precios.
- [x] Ocultamiento de productos.
- [x] Cambio del estado de pedidos.
- [x] Actualización del estado de pago.
- [x] Gestión de roles de usuarios.
- [x] Restricción de acceso administrativo para clientes.
- [x] Restricción de consulta de pedidos ajenos.

### Pruebas pendientes de validación final

- [ ] Rechazo de credenciales incorrectas.
- [ ] Comprobación del acceso al perfil después de cerrar sesión.
- [ ] Rechazo de compras con stock insuficiente.
- [ ] Restitución del inventario al cancelar una compra.
- [ ] Carga y protección de comprobantes.
- [ ] Revisión integral de CSRF.
- [ ] Instalación desde un clon limpio del repositorio.
- [ ] Importación del SQL en una base de datos nueva.
- [ ] Auditoría final de archivos privados.

Las pruebas pendientes deben realizarse antes de considerar completamente validada la entrega.

---

## 13. Repositorio GitHub

El proyecto utiliza GitHub para conservar el historial de las diferentes fases del desarrollo.

**Repositorio original:**

https://github.com/XxSHIROKINGxX/fase-1-maquetacion

### Ramas del proyecto

- `fase-1-maquetacion`
- `fase-2-javascript`
- `main`
- `fase-4-despliegue`

La rama destinada a esta entrega es:

`fase-4-despliegue`

Esta rama se creó a partir de `main` y contiene la integración del proyecto desarrollado con CodeIgniter 4.

Antes del Pull Request debe revisarse que `main` conserve los avances anteriores que corresponda integrar, incluida la Fase 3.

### Verificación del repositorio

Desde PowerShell:

```powershell
git branch --show-current
git status
```

### Preparación del commit

Después de revisar los archivos y confirmar que no se incluyen datos privados:

```powershell
git add .
git diff --cached --stat
git status
```

### Registro de cambios

Una vez revisados los archivos preparados:

```powershell
git commit -m "Fase 4: integracion de Rosea Boutique con CodeIgniter 4"
```

### Publicación en GitHub

```powershell
git push -u origin fase-4-despliegue
```

Estos comandos deben ejecutarse únicamente después de completar la revisión de privacidad y comprobar el contenido de la entrega.

### Pull Request

Una vez publicada la rama, se debe crear un Pull Request desde `fase-4-despliegue` hacia `main`.

El Pull Request debe describir las funcionalidades implementadas, las pruebas realizadas y los cambios introducidos.

Cada integrante del equipo debe registrar sus aportes reales. No se deben atribuir commits a personas que no hayan realizado dichos trabajos.

### Versión final

Después de aprobar y fusionar el Pull Request:

```powershell
git switch main
git pull --ff-only origin main
git tag v4.0
git push origin v4.0
```

La etiqueta de versión se creará únicamente después de completar y aprobar la integración.

---

## 14. Organización y herramientas colaborativas

### GitHub

Utilizado para mantener el historial del código, registrar cambios y organizar la integración de las diferentes fases.

### ClickUp

Herramienta de gestión de tareas para organizar las responsabilidades y el progreso del equipo.

### Documentación

Los archivos de documentación permiten registrar los procedimientos realizados y recopilar evidencias del funcionamiento de la aplicación.

---

## 15. Información académica

**Universidad:** Universidad Abierta para Adultos (UAPA).

**Asignatura:** Desarrollo de Aplicaciones Web.

**Código:** ISW-306.

**Actividad:** Actividad Práctica 4.

**Fase:** Fase 4 — Integración del Framework.

**Proyecto:** Roséa Boutique.

**Tecnología principal:** CodeIgniter 4 con PHP y MySQL/MariaDB.

### Integrantes del equipo

Pendiente de completar con los nombres reales y responsabilidades de cada participante.

### Enlaces de entrega

**Repositorio:** https://github.com/XxSHIROKINGxX/fase-1-maquetacion

**Pull Request:** pendiente.

**Tablero ClickUp:** pendiente.

**Informe PDF definitivo:** pendiente.

**Video de demostración:** pendiente.

---

## 16. Documentación complementaria

El proyecto contiene los siguientes documentos:

**documentacion/GUIA_PRUEBAS.md**

Describe las pruebas necesarias y las evidencias que deben recopilarse.

**documentacion/GUION_VIDEO.md**

Presenta un guion de demostración de 5 a 8 minutos con participación de al menos dos integrantes.

**documentacion/INFORME_BORRADOR.md**

Contiene el borrador académico que debe completarse con los resultados obtenidos, aportes y evidencias reales del equipo.

---

## 17. Conclusión

La cuarta fase de Roséa Boutique permitió integrar CodeIgniter 4 como framework de desarrollo web, organizando el sistema mediante una arquitectura MVC.

Esta integración contribuyó a estructurar las funcionalidades de autenticación, gestión de usuarios, administración de productos, compras, pedidos e inventario.

Las pruebas realizadas en XAMPP permitieron comprobar el funcionamiento de las operaciones principales, incluyendo las restricciones de acceso y la administración de pedidos.

El uso de GitHub facilita la conservación del historial del proyecto y la integración de los avances mediante ramas y Pull Requests.

Las comprobaciones pendientes de seguridad e instalación reproducible deberán completarse antes del cierre definitivo de la entrega.

Este proyecto representa una aplicación práctica de los conocimientos adquiridos en la asignatura Desarrollo de Aplicaciones Web, integrando programación, bases de datos, arquitectura MVC, seguridad y control de versiones.

---

## 18. Referencias bibliográficas

**CodeIgniter.** Documentación oficial.

https://codeigniter.com/user_guide/

**PHP.** Manual oficial.

https://www.php.net/manual/es/

**Composer.** Documentación oficial.

https://getcomposer.org/doc/

**Git.** Documentación oficial.

https://git-scm.com/doc

**GitHub.** Documentación oficial.

https://docs.github.com/

**Apache Friends.** XAMPP.

https://www.apachefriends.org/

---

**Roséa Boutique — Fase 4**

**Desarrollo de Aplicaciones Web (ISW-306) — UAPA**

*Detalles delicados, estilo inolvidable. 🌸*