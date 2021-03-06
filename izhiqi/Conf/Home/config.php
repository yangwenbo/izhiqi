<?php
$config = array(
    'DEFAULT_FILTER' => 'htmlspecialchars',
    'LOG_RECORD' => true,
    'LOG_LEVEL' => 'EMERG, ALERT, CRIT, ERR',

    'USER_SESSION_NAME' => 'user_session_name',
    'ENTERPRISE_USER_SESSION_NAME' => 'enterprise_user_session_name',

    'URL_CASE_INSENSITIVE' => true,

    'APP_AUTOLOAD_PATH' => '@.Util.Home',

);

$site_config = require CONF_PATH.'/Dev/siteconfig.php';
$map_config = require CONF_PATH.'/Dev/map_config.php';

return array_merge($config, $site_config, $map_config);
?>
