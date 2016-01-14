# simplesamlphp-guarani3-auth-module

Modulo de autenticación contra base [SIU-Guaraní 3](http://www.siu.edu.ar/siu-guarani/) para [SimpleSAMLphp](https://simplesamlphp.org/).

Esto esta basado fuertemente en modulo que desarrolle hace un tiempo para el SIU-Guaraní 2 (https://github.com/gpilla/simplesamlphp-guarani2-auth-module)

## Instalación

Subir la carpeta "guarani3" a {ruta al simplesamlphp}/modules/

## Configuración básica

En el archivo {ruta al simplesamlphp}/config/authsources.php, agregar las siguientes lineas:

```php
<?php

$config = array(

    // ... algo antes ...

    'guarani3' => array(
       'guarani3:GuaraniAuth',
       'user' => 'unUsu4r10MuyS3gur0',
       'password' => 'un4Cl4v3MuyS3gur4',
       'dbname' => 'guarani3',
       'host' => '192.168.0.2',
       'port' => '5432',
   ),

    // ... algo despues ...

);

```

Nota: Esta ultima configuración puede repetirse para tener multilpes instancias de Guarani (en caso de facultades, o en casos de ambientes de producción y testing)

Probar la autenticación desde la pantalla de tests, y configurar en {ruta al simplesamlphp}/metadata/saml20-idp-hosted.php

```php
<?php

    $metadata['__DYNAMIC:1__'] = array(

        // ... algo antes ...

        /*
         * Authentication source to use. Must be one that is configured in
         * 'config/authsources.php'.
         */
        'auth' => 'guarani3',

        // ... algo despues ...

    );

```
