<?php
$config = array(
    'DEFAULT_FILTER' => 'htmlspecialchars',
    'LOG_RECORD' => true,
    'LOG_LEVEL' => 'EMERG, ALERT, CRIT, ERR',

    'USER_SESSION_NAME' => 'user_session_name',
    'URL_CASE_INSENSITIVE' => true,

    'APP_AUTOLOAD_PATH' => '@.Util.Home',

);

$site_config = require CONF_PATH.'/Home/Dev/siteconfig.php';
$db_config = require CONF_PATH.'/Home/Dev/db_config.php';

return array_merge($config, $site_config, $db_config);
?>
