<?php
/**
 * Configuración básica de WordPress.
 *
 * El script de creación utiliza este fichero para la creación del fichero wp-config.php durante el
 * proceso de instalación. Usted no necesita usarlo en su sitio web, simplemente puede guardar este fichero
 * como "wp-config.php" y completar los valores necesarios.
 *
 * Este fichero contiene las siguientes configuraciones:
 *
 * * Ajustes de MySQL
 * * Claves secretas
 * * Prefijo de las tablas de la Base de Datos
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solcite esta información a su proveedor de alojamiento web. ** //
/** El nombre de la base de datos de WordPress */
define('DB_NAME', 'maletek1');

/** Nombre de usuario de la base de datos de MySQL */
define('DB_USER', 'root');

/** Contraseña del usuario de la base de datos de MySQL */
define('DB_PASSWORD', '');

/** Nombre del servidor de MySQL (generalmente es localhost) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para usar en la creación de las tablas de la base de datos. */
define('DB_CHARSET', 'utf8');

/** El tipo de cotejamiento de la base de datos. Si tiene dudas, no lo modifique. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autenticación y salts.
 *
 * ¡Defina cada clave secreta con una frase aleatoria distinta!
 * Usted puede generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress.org}
 * Usted puede cambiar estos valores en cualquier momento para invalidar todas las cookies existentes. Esto obligará a todos los usuarios a iniciar sesión nuevamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Vd?vnCd-_GNn%Y1QWO<O)}|~Te:K)rwJ;bQQ+gk$ mT$aEx[8c1t!ls+W0+5)](j');
define('SECURE_AUTH_KEY',  'fqoR|T7gtp`OTVet|G3q-4+w2oA<YQIMbv/9gc2W^3R{Y1iHH2*1|Y8tMNDv%t$}');
define('LOGGED_IN_KEY',    'no7QuXpx@^!F$IT>:Xb/!mi5(utB4*6N0E9{lo-UXdn[E.omGL)7c2(I25O8,4,|');
define('NONCE_KEY',        '*</yS[p>zywWub4vkk~v%kFq3AoF3U^uuQG3YR#(on;WE{uW-guv<_Ct%VpwmO,a');
define('AUTH_SALT',        '4.IX~pxn>@AG8/,)Jt7Nu.3z.^>qg8`24,9q4g;.dZ8lq|7SHReyMOF3Sm)QCl5w');
define('SECURE_AUTH_SALT', 'hM(Ljnz0TK}@} wxWQ(>|q.,q;$-xES0k`Vr+pH&w5M0bWWg3s9iVD^I;/>(5+|o');
define('LOGGED_IN_SALT',   'mFY{Px<H3_<ZZyGwS(Na:|(d&h9wT+TBFnZ!/~-,6hL@@HlQU7i|gsTrO%!0zK?8');
define('NONCE_SALT',       '70buD++3f;{u{+-&<FzmZ#-PA<8Fq|Ny0.+RHH1V;r]s2 <IZ|6o9u2#7axaTn%^');

/**#@-*/

/**
 * Prefijo de las tablas de la base de datos de WordPress.
 *
 * Usted puede tener múltiples instalaciones en una sóla base de datos si a cada una le da 
 * un único prefijo. ¡Por favor, emplee sólo números, letras y guiones bajos!
 */
$table_prefix  = 'wp_';

/**
 * Para los desarrolladores: modo de depuración de WordPress.
 *
 * Cambie esto a true para habilitar la visualización de noticias durante el desarrollo.
 * Se recomienda encarecidamente que los desarrolladores de plugins y temas utilicen WP_DEBUG
 * en sus entornos de desarrollo.
 *
 * Para obtener información acerca de otras constantes que se pueden utilizar para la depuración, 
 * visite el Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deje de editar! Disfrute de su sitio. */

/** Ruta absoluta al directorio de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Establece las vars de WordPress y los ficheros incluidos. */
require_once(ABSPATH . 'wp-settings.php');
