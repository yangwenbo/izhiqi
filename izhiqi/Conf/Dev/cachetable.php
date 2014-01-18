<?php
/*
 'DATA_CACHE_TIME': 默认情况下， 全局数据缓存时间
 如果CACHETABLE中设定了 'expire', 采用 'expire'作为缓存时间
*/
return array(
    	// Cache 映射表
		'CACHETABLE' => array(
            'Userinfo' => array('cacheKeyFormat'=>"Userinfo:%d", 'expire'=>10),
        )
	);
?>
