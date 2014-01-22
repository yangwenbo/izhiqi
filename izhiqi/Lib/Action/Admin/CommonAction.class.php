<?php

	class CommonAction extends Action {
		public function logout() {
			unset($_SESSION['izhiqi_admin']);
			$this->redirect('Common/Login');
		}

		public function login() {
			$this->display();
		}

		public function adminLogin() {
			if ($_POST) {
            	$aname = $this->_param('aname');
            	$password = $this->_param('password');
            	if (empty($aname)) {
              	  $this->error('请输入您的用户名');
           	 	}
            	if (empty($password)) {
                	$this->error('请输入密码');
            	}
            	$admin = M('admin');
				$condition = array('aname' => $aname);
				
				/* 把查询条件传入查询方法 */
				$adminData = $admin->where($condition)->select(); 
				
				/* 判断返回是否有数据 */
				if(true == empty($adminData)) {
					$this->error('用户名或者密码错误！');
				}
				
				/* 下面判断是否等于 */
				if($aname == $adminData[0]['aname']) {
					$adminnamestatu = true;
				} else {
					$this->error('用户名或者密码错误！');
				}
				
				if(md5($password .'aizhi!#@123') != $adminData[0]['apwd']) {
					$this->error('用户名或者密码错误！');
				}
	
				$_SESSION['izhiqi_admin']['aname'] = $adminData[0]['aname'];
				$this->redirect('Index/index');
        	}
        	$this->display();		
		}

	}
?>
