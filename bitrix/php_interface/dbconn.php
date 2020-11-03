<? define("SHORT_INSTALL_CHECK", true);?><?
define("DBPersistent", false);
$DBType = "mysql";
$DBHost = "localhost";
$DBLogin = "bitrix0";
$DBPassword = "%zO?vSQje!us&mQ]qWY8";
$DBName = "sitemanager";
$DBDebug = false;
$DBDebugToFile = false;

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_FILE_PERMISSIONS", 0664);
define("BX_DIR_PERMISSIONS", 0775);
@umask(~BX_DIR_PERMISSIONS);

define("MYSQL_TABLE_TYPE", "INNODB");
define("SHORT_INSTALL", true);
define("VM_INSTALL", true);

define("BX_UTF", true);


if(!(defined("CHK_EVENT") && CHK_EVENT===true))
   define("BX_CRONTAB_SUPPORT", true);

define("BX_CACHE_TYPE", "memcache");
define("BX_CACHE_SID", $_SERVER["DOCUMENT_ROOT"]."#01");
define("BX_MEMCACHE_HOST", "unix:///tmp/memcached.sock");
define("BX_MEMCACHE_PORT", "0");

define("BX_USE_MYSQLI", true);
?>
