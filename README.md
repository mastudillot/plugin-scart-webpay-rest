[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=mastudillot_plugin-scart-webpay-rest&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=mastudillot_plugin-scart-webpay-rest)

# Plugin Webpay Plus para e-commerce S-Cart

Esta versión del plugin fue probada con el e-commerce S-Cart versión 8.1, para más información del la tienda visitar su [web oficial](https://s-cart.org/en)

## Dependencias

- [Transbank SDK Oficial](https://github.com/TransbankDevelopers/transbank-sdk-php): Se requiere la versión 4.x.
- PHP 7.4 o mayor. 

## Instalación

### Instalación del plugin

Para realizar la instalación, se debe subir el plugin desde la opción `Import plugin` desde la sección de `Payment` en `Extensions->Plugins`.

Se debe seleccionar el archivo y pulsar el botón import.

### Generar una nueva versión

Para generar una nueva versión, se debe generar un PR con el título "Release X.Y.Z", con los valores que correspondan para `X`, `Y` y `Z`, se debe seguir el estándar semver para determinar si se incrementa el valor de `X` (si hay cambios no retrocompatibles), `Y` (para mejoras retrocompatibles) o `Z` (si sólo hubo correcciones a bugs).

Los pasos son los siguientes:

1. Se debe crear una rama release, con destino a la rama `main`.
2. Se debe modificar el archivo `config.json` con la versión a liberar.
3. Se debe modificar el archivo `CHANGELOG.md` con los cambios que incluye la versión.
4. Una vez aprobado el PR se debe mezclar inmediatamente a la rama `main` y generar un nuevo release.
5. En la descripción del release se deben incluir los cambios más relevantes de la versión.
