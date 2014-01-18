<?php
class UsersAction extends Action
{
	/**
    预览用户信息
	*/
	public function showUser()
	{
		$uid=intval($this->_param('uid'))?intval($this->_param('uid')):0;
        $userinfo=Userhelp::getUserinfoByUid($uid);
        if(!$userinfo){
            $this->error('用户不存在');
        }
        $this->display();
	}
}

?>