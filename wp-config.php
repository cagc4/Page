<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'nabuWS');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'G>/M8e]`>n@Yfzvl9j:Vo+svD@vJ<v!vYK|WR?=TgEJXj=0xJty*ir+ml)3?uLH}');
define('SECURE_AUTH_KEY', '}g``+#ZVh1)}JTFtm`7w#m]f_=Q60{YIeO~Dy[.<}5Y_E2GnBSKL>Ao2_4{<>e(J');
define('LOGGED_IN_KEY', '%kdoL#]#.nQ.8 ]]8Dyj0]87(|;fc+$c4MAA99SZLe@ihf`^j94f!V}tv%lG-Rw_');
define('NONCE_KEY', '(Oe2e.MkY<%5y6cN3lIcL!eA2ra}UI2D?p!),OTA^+ac^}Yq[23kLXW0uUNfp:7~');
define('AUTH_SALT', '{Ay+uR:$0f;(QhCi5Ay~yE2lo%_EPN9Z[7aKTf&k${_YH<K5:WfK/wGOV-_ >D#3');
define('SECURE_AUTH_SALT', 'pVP;,W&^)<e@|lqa.2p04D>7-3WBn.Ib!8GBK;C^~RbdSt@eCKhbW[`k%c{ EMC0');
define('LOGGED_IN_SALT', 'kM2udeBB@5WNQbsItpZ%y0x8<p!U`d-AMz-I.3SwF#YPzZ$Cpbwm2= H#C%C1U<-');
define('NONCE_SALT', 't)>b<0t2)~%UqD/}RraIh!SItJ}^P8aA1kv*=-.*[[byT`E@M+PJ2A4W -f^IfRu');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'nabu_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


/*
AUTOR  CAGC 
ID  001
FECHA =19-10-2016
Descripcion = Para poder Instalar temas y plugins desde Localhost
*/
define('FS_METHOD','direct');
/*
FIN CAGC 001
*/

