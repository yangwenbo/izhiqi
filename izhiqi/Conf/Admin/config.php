<?php
return $config = array(
    'DEFAULT_FILTER' => 'htmlspecialchars',
    'LOG_RECORD' => true,
    'LOG_LEVEL' => 'EMERG, ALERT, CRIT, ERR',
	
	'TMPL_PARSE_STRING' => array(
		'__JSURL__'		=> WEBSITE . 'Public/js',
		'__CSSURL__'	=> WEBSITE . 'Public/css',
		'__IMGURL__'	=> WEBSITE . 'Public/images',
	),
);
?>
