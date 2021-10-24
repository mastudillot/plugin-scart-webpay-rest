# Plugin Webpay Plus para e-commerce S-Cart

## Dependencias

- Transbank SDK: Se requiere la versión 2.x.

## Instalación

Antes de instalar el plugin, es necesario configurar algunos archivos del servidor.

### Cookies

Para el correcto funcionamiento del plugin y con el find e evitar perdidas de sesión en algunas respuestas de Transbank es configurar lo siguiente:

- Establecer `same_site` a `none` en el archivo `config/session.php`.
- Añadir la variable `SESSION_SECURE_COOKIE=true` al archivo `.env`.

### Instalación del plugin

Antes de instalar el plugin es necesario instalar el SDK de Transbank con el comando `composer require transbank/transbank-sdk:^2.0`. Luego es posible instalar el plugin desde el archivo .zip desde el panel de administración.
