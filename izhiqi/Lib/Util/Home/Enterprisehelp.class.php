<?php
class Enterprisehelp
{
    //-----------------------Enterprise
	public static function insertEnterprise($data){
        D('Enterprise')->data($data)->add();
        return D('Enterprise')->getLastInsID();
	}
	public static function updateEnterprise($where,$data){
        return D('Enterprise')->where($where)->save($data);
	}
	public static function getEnterprise($where,$order){
        return D('Enterprise')->where($where)->order($order)->find();
	}
	public static function getEnterpriseList($where,$order,$limit,$field){
        return D('Enterprise')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getEnterpriseCount($where){
        return D('Enterprise')->where($where)->count();
	}
 	//-----------------------Enterprise_address
	public static function insertEnterprise_address($data){
        D('Enterprise_address')->data($data)->add();
        return D('Enterprise_address')->getLastInsID();
	}
	public static function updateEnterprise_address($where,$data){
        return D('Enterprise_address')->where($where)->save($data);
	}
	public static function getEnterprise_address($where,$order){
        return D('Enterprise_address')->where($where)->order($order)->find();
	}
	public static function getEnterprise_addressList($where,$order,$limit,$field){
        return D('Enterprise_address')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getEnterprise_addressCount($where){
        return D('Enterprise_address')->where($where)->count();
	}
    //-----------------------Enterpriseext
	public static function insertEnterpriseext($data){
        D('Enterpriseext')->data($data)->add();
        return D('Enterpriseext')->getLastInsID();
	}
	public static function updateEnterpriseext($where,$data){
        return D('Enterpriseext')->where($where)->save($data);
	}
	public static function getEnterpriseext($where,$order){
        return D('Enterpriseext')->where($where)->order($order)->find();
	}
	public static function getEnterpriseextList($where,$order,$limit,$field){
        return D('Enterpriseext')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getEnterpriseextCount($where){
        return D('Enterpriseext')->where($where)->count();
	}
    //-----------------------Enterpriselogin
	public static function insertEnterpriselogin($data){
        D('Enterpriselogin')->data($data)->add();
        return D('Enterpriselogin')->getLastInsID();
	}
	public static function updateEnterpriselogin($where,$data){
        return D('Enterpriselogin')->where($where)->save($data);
	}
	public static function getEnterpriselogin($where,$order){
        return D('Enterpriselogin')->where($where)->order($order)->find();
	}
	public static function getEnterpriseloginList($where,$order,$limit,$field){
        return D('Enterpriselogin')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getEnterpriseloginCount($where){
        return D('Enterpriselogin')->where($where)->count();
	}
	//-----------------------Enterprisecoins
	public static function insertEnterprisecoins($data){
        D('Enterprisecoins')->data($data)->add();
        return D('Enterprisecoins')->getLastInsID();
	}
	public static function updateEnterprisecoins($where,$data){
        return D('Enterprisecoins')->where($where)->save($data);
	}
	public static function getEnterprisecoins($where,$order){
        return D('Enterprisecoins')->where($where)->order($order)->find();
	}
	public static function getEnterprisecoinsList($where,$order,$limit,$field){
        return D('Enterprisecoins')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getEnterprisecoinsCount($where){
        return D('Enterprisecoins')->where($where)->count();
	}
    /**
    通过邮箱获取企业信息
    */
    public static function getEnterpriseloginByEmail($email){
		$where="email='".$email."'";
        $result=Enterprisehelp::getEnterpriselogin($where);
        return $result;
	}
	/**
    通过企业id获取企业信息
	*/
	public static function getEnterpriseinfo($eid)
	{
        $where="id=".$eid;
        $Enterpriselogin=Enterprisehelp::getEnterpriselogin($where);
        $where="eid=".$eid;
        $Enterprise=Enterprisehelp::getEnterprise($where);
        $Enterpriseext=Enterprisehelp::getEnterpriseext($where);
        $Enterprisecoins=Enterprisehelp::getEnterprisecoins($where);
        $Enterprise_address=Enterprisehelp::getEnterprise_address($where);

        $joblist=Jobhelp::getJoblistByEid($eid);

        $result=array();
        if($Enterpriselogin)
        { 
            $result=array_merge($result,$Enterpriselogin);
        }
        if($Enterprise)
        {
            $result=array_merge($result,$Enterprise);
        }
        if($Enterpriseext)
        {
            $result=array_merge($result,$Enterpriseext);
        }
        if($Enterprisecoins)
        {
            $result=array_merge($result,$Enterprisecoins);
        }
        if($Enterprise_address)
        {
            $result=array_merge($result,$Enterprise_address);
        }
        if($joblist)
        {
            $result['joblist']=$joblist;
        }
        return $result;
	}
        /**
         * 公司名称
         * @param type $eid
         * @return type
         */
        public static function getCompanyname($eid)
        {
            $where="eid=".$eid;
            $arrEnterprise=Enterprisehelp::getEnterprise($where);
            return $arrEnterprise['companyname'];
        }
        
}
?>