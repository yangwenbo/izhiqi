<?php
class UserloginModel extends Model
{
    protected $_validate=array(
            array('verifycode','require','验证码不能为空'),
            array('email','require','email不能为空'),
            array('email','email','email的格式不正确'),
            array('email','','email已存在',0,'unique',1),
            array('password','require','密码不能为空'),
            array('repassword','password','两次输入的密码不一致',0,'confirm')
    );
}
?>