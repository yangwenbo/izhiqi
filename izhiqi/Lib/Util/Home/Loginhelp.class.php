<?php
class Loginhelp 
{
	static public function userLogin($uid){
        session(C('USER_SESSION_NAME'),$uid);   
	}
	static public function userLogout(){
        session(C('USER_SESSION_NAME'),null);
	}
	static public function enterpriseLogin($eid){
		session(C('ENTERPRISE_USER_SESSION_NAME'),$eid);
	}
	static public function enterpriseLogout(){
	    session(C('ENTERPRISE_USER_SESSION_NAME'),null);	
	}
	static public function getUserUid(){
		$eid=session(C('USER_SESSION_NAME'));
		if(!$eid){
           $eid=0;
		}
		return $eid;   
	}
	static public function getEid(){
		$eid=session(C('ENTERPRISE_USER_SESSION_NAME'));
		if(!$eid){
           $eid=0;
		}
		return $eid;   
	}
	static public function isUserLogin(){
        if(Loginhelp::getUserUid()){
             return true;
        }
        return false;    
	}
	static public function isEnterpriseLogin(){
        if(Loginhelp::getEid()){
             return true;
        }
        return false;    
	}
	static public function encrypt($password)
	{
        return md5($password.'aizhi!#@123');
	}

}
?>