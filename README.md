[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=mastudillot_plugin-scart-webpay-rest&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=mastudillot_plugin-scart-webpay-rest)

# Plugin Webpay Plus para e-commerce S-Cart

Esta versión del plugin fue probada con el e-commerce Scart versión 6.9, para más información del la tienda visitar su [web oficial](https://s-cart.org/en)

## Dependencias

- [Transbank SDK Oficial](https://github.com/TransbankDevelopers/transbank-sdk-php): Se requiere la versión 2.x.
- PHP 7.4 o mayor. 

## Instalación

Antes de instalar el plugin, es necesario configurar algunos archivos del servidor.

### Cookies

Para el correcto funcionamiento del plugin y con el fin de evitar perdidas de sesión en algunas respuestas de Transbank es necesario configurar lo siguiente:

- Establecer `same_site` a `none` en el archivo `config/session.php` del sitio.
- Añadir la variable `SESSION_SECURE_COOKIE=true` al archivo `.env`.

### Instalación del plugin

Para realizar la instalación, se debe subir el plugin desde la opción `Import plugin` desde la sección de `Payment` en `Extensions->Plugins`.

Se debe seleccionar el archivo y pulsar el botón import.

### Generar una nueva versión

Para generar una nueva versión se debe crear una `variable de entorno` con la versión a generar y luego ejecutar el archivo `package.sh`.

```bash
export TAG="1.0.0"
./package.sh
```


