# Integración de framework y entrega final: Roséa Boutique
**Asignatura:** Desarrollo de Aplicaciones Web (ISW-306) · **Actividad:** Práctica 4 · **Institución:** UAPA

**Integrantes:** [COMPLETAR NOMBRES REALES]
**Fecha:** [FECHA DE ENTREGA]

## 1. Introducción y objetivo

Roséa Boutique es una aplicación comercial dedicada a la exhibición y venta de ropa y accesorios de estilo coquette. Durante las primeras tres fases se trabajó en la interfaz, la interacción con JavaScript y posteriormente la persistencia mediante PHP y MySQL. Esta fase busca aplicar el resultado de aprendizaje RA6 mediante un framework que estandarice la organización del código y permita una instalación reproducible.

El objetivo consiste en reorganizar la aplicación bajo el patrón Modelo–Vista–Controlador (MVC), mantener autenticación, sesiones, operaciones CRUD y pedidos, y presentar la documentación necesaria para que otra persona configure el proyecto en XAMPP siguiendo el README.

## 2. Elección y justificación del framework

Se seleccionó CodeIgniter 4, escrito en PHP, debido a la compatibilidad con el lenguaje y la base de datos utilizados anteriormente. Frente a reescribir la aplicación en otro lenguaje, esta opción permite trasladar reglas de negocio y consultas existentes, reduciendo el esfuerzo de migración. También incorpora enrutamiento, controladores, modelos, validación de solicitudes y servicios como sesiones y conectividad con la base de datos.

En el proyecto, `app/Config/Routes.php` concentra el enrutamiento y asocia rutas a métodos de controladores; `app/Controllers` separa la lógica de autenticación, catálogo, pedidos y administración; `app/Models` organiza el acceso a usuarios, productos y pedidos; y `app/Views` contiene las pantallas. CodeIgniter proporciona el objeto Request/Response a los controladores y se emplean servicios compartidos como `session()` y `db_connect()`.

## 3. Cambios realizados respecto de la Fase 3

La Fase 3 utilizaba páginas PHP independientes, por ejemplo `login.php`, `registro.php`, `productos.php`, `crear_pedido.php` y páginas administrativas. Para la Fase 4 se construyó una capa de aplicación con controladores para atender las peticiones y modelos para comunicar con la base de datos. La ruta `/productos` presenta la colección, `/login` y `/registro` administran credenciales y `/pedidos` concentra la compra.

El proceso de pedido se mantiene como una transacción que consulta el precio y la disponibilidad desde MySQL, bloquea la fila del producto, crea el pedido y descuenta existencias. El panel administrativo contempla crear, modificar y ocultar productos (sin borrar referencias históricas), revisar pedidos, confirmar pagos y cancelar pedidos con reposición de existencias. Se incluyeron verificaciones de permisos para que solo los usuarios autenticados consulten sus pedidos y solo los administradores gestionen la tienda.

La antigua exportación de base de datos contenía datos de personas y pedidos reales. Para la entrega reproducible se creó `database/rosea_demo.sql` con tablas equivalentes y cuentas ficticias con claves hash. Los comprobantes se trasladaron conceptualmente a `writable/comprobantes` y se sirven a través de un controlador con autorización.

## 4. Instalación, pruebas y dificultades

La instalación prevista requiere XAMPP con PHP 8.2 o superior, Composer 2 y MySQL/MariaDB. Se suministra `INSTALAR_XAMPP.ps1`, que crea una copia nueva basada en el App Starter de CodeIgniter, integra los archivos migrados y crea la configuración local `.env`. Después se importa el SQL desde phpMyAdmin y se accede por Apache.

**Registro de pruebas pendiente:** [COMPLETAR CON FECHA, CAPTURAS Y RESULTADOS REALES DE: registro, login, sesiones, CRUD, compra, stock, administración, comprobantes, instalación en otro equipo]. El análisis estático del código no equivale a verificar su funcionamiento completo en XAMPP.

**Dificultades y soluciones reales:** [COMPLETAR DESPUÉS DE LAS PRUEBAS]. Como consideraciones técnicas se contemplan la versión de PHP requerida, la separación entre recursos públicos y archivos privados, el ajuste de `app.baseURL`, y el uso de CSRF en los formularios POST.

## 5. Aportes de los integrantes

[PERSONA 1] — [FUNCIONES CONCRETAS; RUTAS/CONTROLLERS Y COMMITS].

[PERSONA 2] — [FUNCIONES CONCRETAS; BASE DE DATOS, README Y COMMITS].

[PERSONA 3, SI APLICA] — [PRUEBAS, DOCUMENTACIÓN Y COMMITS].

Estas contribuciones deben coincidir con el trabajo desarrollado y las evidencias de GitHub/ClickUp.

## 6. Conclusión y reflexión

La migración propuesta permite dar un paso desde una colección de scripts PHP hacia una aplicación más organizada. MVC facilita localizar la lógica de cada función y desarrollar nuevas características sin mezclar de forma excesiva HTML y consultas de base de datos. La configuración separada, la base de datos con registros de prueba y el README procuran que cualquier miembro del equipo reproduzca el ambiente local.

La integración solo podrá declararse culminada después de ejecutar las pruebas indicadas, corregir posibles errores y completar el PR, el tablero ClickUp y el video. Como aprendizaje, destacan el valor de separar responsabilidades, validar cada interacción y manejar cuidadosamente la información privada de los usuarios.

## Referencias

CodeIgniter Foundation. (2026). *CodeIgniter 4 User Guide*. https://codeigniter.com/user_guide/

Composer. (s. f.). *Introduction to Composer*. https://getcomposer.org/doc/00-intro.md

PHP Documentation Group. (s. f.). *Password hashing*. https://www.php.net/manual/en/book.password.php
