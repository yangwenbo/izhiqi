<?php 
class UserinfoModel extends Model
{
    protected $_validate=array(
            array('realname','require','真实姓名不能为空')
    );
	
}

?>