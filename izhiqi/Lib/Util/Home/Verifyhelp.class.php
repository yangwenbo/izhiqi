<?php
class Verifyhelp{
    /**
     * 创建注册验证码
     * @return number
     */
	static public function createVerifycode(){
	    $code=genRandomString(4);
	    session(C('VERIFY_CODE'),$code);
		return $code;
	}
 
	/**
	 * 检查注册验证码
	 * @param unknown $verifycode
	 * @return boolean
	 */
	static public function checkVerifycode($verifycode){
		if(strcasecmp(Verifyhelp::getVerifycode(),$verifycode)===0){
			Verifyhelp::cleanVerifycode();
            return true;
		}
		return false;
	}
 
	/**
	 * 清除注册验证码
	 */
	static public function cleanVerifycode(){
        session(C('VERIFY_CODE'),null);
	}
 
	/**
	 * 获取注册验证码
	 * @return Ambigous <mixed, NULL>
	 */
	static public function getVerifycode(){
        return session(C('VERIFY_CODE'));
	}
 
}
?>