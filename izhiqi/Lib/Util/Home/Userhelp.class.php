<?php
/**
    用户相关信息的增删改
*/
class Userhelp{
    //-----------------------Usercoins
	public static function insertUsercoins($data){
        D('Usercoins')->data($data)->add();
        return D('Usercoins')->getLastInsID();
	}
	public static function updateUsercoins($where,$data){
        return D('Usercoins')->where($where)->save($data);
	}
	public static function getUsercoins($where,$order){
        return D('Usercoins')->where($where)->order($order)->find();
	}
	public static function getUsercoinsList($where,$order,$limit,$field){
        return D('Usercoins')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
    //----------------------------Usereducation
	public static function insertUsereducation($data){
        D('Usereducation')->data($data)->add();
        return D('Usereducation')->getLastInsID();
	}
	public static function updateUsereducation($where,$data){
        return D('Usereducation')->where($where)->save($data);
	}
	public static function getUsereducation($where,$order){
        return D('Usereducation')->where($where)->order($order)->find();
	}
	public static function getUsereducationList($where,$order,$limit,$field){
        return D('Usereducation')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	//-----------------------Userinfo
	public static function insertUserinfo($data){
        D('Userinfo')->data($data)->add();
        return D('Userinfo')->getLastInsID();
	}
	public static function updateUserinfo($where,$data){
        return D('Userinfo')->where($where)->save($data);
	}
	public static function getUserinfo($where,$order){
        return D('Userinfo')->where($where)->order($order)->find();
	}
	public static function getUserinfoList($where,$order,$limit,$field){
        return D('Userinfo')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getUserinfoCount($where){
        return D('Userinfo')->where($where)->count();
	}
	//-----------------------Userinfoext
	public static function insertUserinfoext($data){
        D('Userinfoext')->data($data)->add();
        return D('Userinfoext')->getLastInsID();
	}
	public static function updateUserinfoext($where,$data){
        return D('Userinfoext')->where($where)->save($data);
	}
	public static function getUserinfoext($where,$order){
        return D('Userinfoext')->where($where)->order($order)->find();
	}
	public static function getUserinfoextList($where,$order,$limit,$field){
        return D('Userinfoext')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	//-------------------------Userlogin
	public static function insertUserlogin($data){
        D('Userlogin')->data($data)->add();
        return D('Userlogin')->getLastInsID();
	}
	public static function updateUserlogin($where,$data){
        return D('Userlogin')->where($where)->save($data);
	}
	public static function getUserlogin($where,$order){
        return D('Userlogin')->where($where)->order($order)->find();
	}
	public static function getUserloginList($where,$order,$limit,$field){
        return D('Userlogin')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	//--------------------------Userstat
	public static function insertUserstat($data){
        D('Userstat')->data($data)->add();
        return D('Userstat')->getLastInsID();
	}
	public static function updateUserstat($where,$data){
        return D('Userstat')->where($where)->save($data);
	}
	public static function getUserstat($where,$order){
        return D('Userstat')->where($where)->order($order)->find();
	}
	public static function getUserstatList($where,$order,$limit,$field){
        return D('Userstat')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	//--------------------------Userwork
	public static function insertUserwork($data){
        D('Userwork')->data($data)->add();
        return D('Userwork')->getLastInsID();
	}
	public static function updateUserwork($where,$data){
        return D('Userwork')->where($where)->save($data);
	}
	public static function getUserwork($where,$order){
        return D('Userwork')->where($where)->order($order)->find();
	}
	public static function getUserworkList($where,$order,$limit,$field){
        return D('Userwork')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	//---------------------------

	public static function getUserloginByEmail($email){
		 $where="email='".$email."'";
         $result=Userhelp::getUserlogin($where);
         return $result;
	}
	//获取用户信息
	public static function getUserinfoByUid($uid)
	{
         $where="uid=".$uid;
         $userlogin=Userhelp::getUserlogin("id=".$uid);
         $userinfo=Userhelp::getUserinfo($where);
         $userinfoext=Userhelp::getUserinfoext($where);
         $usercoins=Userhelp::getUsercoins($where);
         $userstat=Userhelp::getUserstat($where);
         

         $order="schoolstartdate desc";
         $usereducation=Userhelp::getUsereducationList($where,$order);
         
         $order="workstartdate desc";
         $userwork=Userhelp::getUserworkList($where,$order);
         
         
         $result=array();
         
         if($userlogin&&$userinfo&&$userinfoext&&$usercoins)
         {
         	$result=array_merge($userlogin,$userinfo,$userinfoext,$usercoins);
         }
         if($userstat)
         {
             $result=  array_merge($result,$userstat);
         }
         if($usereducation)
         {
             //$result['educationinfo']=$usereducation;
             $result = array_merge($result, $usereducation);
         }
         if($userwork)
         {   
             foreach ($userwork as $key => $value) {
                 if($value['status']==0)
                 {
                     unset($userwork[$key]);
                 }
             }
             $result['workinfo']=$userwork;
         }
         return $result;
	} 
    
    static public function getRealname($uid)
    {
        $where="UID=".$uid;
        $result=Userhelp::getUserinfo($where);
        if($result)
        {
            return $result['realname'];
        }
        return "";
    }
}
?>
