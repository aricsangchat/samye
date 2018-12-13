<?php
# Database Configuration
define( 'DB_NAME', 'wp_samyedharma' );
define( 'DB_USER', 'samyedharma' );
define( 'DB_PASSWORD', 'KY510pC4v9MLYQMfTF9O' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'C2LP?*`^!p@]We+S8*CI/v}K6jV)1f-dRuhE776I{_WejC7Z|;:4JIj{0w>d6+L`');
define('SECURE_AUTH_KEY',  'bZPa>H847P6c3T+m|XE8i`Chriwj^zp~Q6`i-hoR)JvJP=9dGVk2|_& :4LKNE$Z');
define('LOGGED_IN_KEY',    '~u_OZ2cHVSCZ?+Gm[!1~;#+Y:c47L||8dUOR!;a)v@sEl1%M84FCLC7j<Iih&c-@');
define('NONCE_KEY',        'z,=%mSTabN6^RrwN|JGMr2iv{_aW&!|wj@%OC~eGmBJPB6Ly-Ndf@KRz4_6k RbG');
define('AUTH_SALT',        'Fd<`+=uI:2{D#@hO9pg+)/SHpd{MyN{TA2C)JYC.9u`jbS@QOV6s67~IPT5yX3O(');
define('SECURE_AUTH_SALT', '6e*A&T;-t@u(5%u&tzDtsA0y~7ey=z/E^sK<98KBQFj|o(^-Kh.sN!,aTqfU+c6A');
define('LOGGED_IN_SALT',   'v!AoKbt=^0mugXsg&vt9ayB)kQh4=IF_iCW1%F4mw];sA0BxOZycuAP:hmF7z6[1');
define('NONCE_SALT',       'dgB+*#6#&uflF@k6-FM@afR?Rq9>i=~iAa,=PXL8|iApH?5G[4{p=?mU-8;..p,f');


# Localized Language Stuff


define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'samyedharma' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '5c9728bad573b5ea72a5d4159b9dc32694d9fa39' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '120078' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'samyedharma.org', 1 => 'samyedharma.wpengine.com', 2 => 'www.samyedharma.org', 3 => 'samyeinstitute.com', 4 => 'samyeinstitute.org', );

$wpe_varnish_servers=array ( 0 => 'pod-120078', );

$wpe_special_ips=array ( 0 => '146.148.77.200', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );

define( 'WP_SITEURL', 'https://samyedharma.org' );

define( 'WP_HOME', 'https://samyedharma.org' );

define( 'WP_CACHE', TRUE );
define('WPLANG', '');
# WP Engine ID


# WP Engine Settings






define('WP_DEBUG', false);
define('WP_MEMORY_LIMIT', '128M');

# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
