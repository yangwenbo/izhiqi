<?php

if(!$_SESSION['izhiqi_admin']['aname']) Header('Location:' . __APP__ .'/Admin/Common/Login');

class IndexAction extends Action {
    public function index(){
		$this->assign('admin', $_SESSION['izhiqi_admin']['aname']);
		$this->display();
	}

	public function welcome() {
		$this->display();
	}
}
