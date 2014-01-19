<?php
	class CheckAction extends Action {
		public function userEmail() {
			if( Userhelp::getUserloginByEmail($_POST['param'])) {
				$this->ajaxReturn(0, '邮箱已注册', 'n');
			}
			else {
				$this->ajaxReturn(0, '邮箱未注册', 'y');
			}
		}

		public function enterpriseEmail() {
			if( Enterprisehelp::getEnterpriseloginByEmail($_POST['param'])) {
				$this->ajaxReturn(0, '邮箱已注册', 'n');
			}
			else {
				$this->ajaxReturn(0, '邮箱未注册', 'y');
			}
		}
	}
?>
