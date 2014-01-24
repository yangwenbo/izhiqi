<?php

class Jobhelp {

    //-----------------------Job
    public static function insertJob($data) {
        D('Job')->data($data)->add();
        return D('Job')->getLastInsID();
    }

    public static function updateJob($where, $data) {
        return D('Job')->where($where)->save($data);
    }

    public static function getJob($where, $order) {
        return D('Job')->where($where)->order($order)->find();
    }

    public static function getJobList($where, $order, $limit, $field) {
        return D('Job')->where($where)->order($order)->field($field)->limit($limit)->select();
    }

    public static function getJobCount($where) {
        return D('Job')->where($where)->count();
    }

    //-----------------------Jobrelation
    public static function insertJobrelation($data) {
        D('Jobrelation')->data($data)->add();
        return D('Jobrelation')->getLastInsID();
    }

    public static function updateJobrelation($where, $data) {
        return D('Jobrelation')->where($where)->save($data);
    }

    public static function getJobrelation($where, $order) {
        return D('Jobrelation')->where($where)->order($order)->find();
    }

    public static function getJobrelationList($where, $order, $limit, $field) {
        return D('Jobrelation')->where($where)->order($order)->field($field)->limit($limit)->select();
    }

    public static function getJobrelationCount($where) {
        return D('Jobrelation')->where($where)->count();
    }

    //-----------------------Jobclass
    public static function insertJobclass($data) {
        D('Jobclass')->data($data)->add();
        return D('Jobclass')->getLastInsID();
    }

    public static function updateJobclass($where, $data) {
        return D('Jobclass')->where($where)->save($data);
    }

    public static function getJobclass($where, $order) {
        return D('Jobclass')->where($where)->order($order)->find();
    }

    public static function getJobclassList($where, $order, $limit, $field) {
        return D('Jobclass')->where($where)->order($order)->field($field)->limit($limit)->select();
    }

    /**
      获取一级分类
     */
    static public function getLevel1() {
        $result = Jobhelp::getJobclassListById(0);
        return $result;
    }
    /**
     * 通过类别id 获取职位类别信息
     * @param type $id
     * @return type
     */
    static public function getJobclassById($id)
    {
        $where="id=".$id;
        $result=Jobhelp::getJobclass($where);
        return $result;
    }
   /**
    * 通过上级分类id 获取下级分类列表
    * @param type $jobclass_id
    * @return type
    */
    static public function getJobclassListById($jobclass_id) {
        $where = "parent_id=" . $jobclass_id . " and status=1";
        $order = "id asc";
        $result = Jobhelp::getJobclassList($where, $order);
        return $result;
    }

    /**
      通过职位类别id，获取它所有的子类别
     */
    static public function getJobclassListAll($jobclass_id) {
        $result = Jobhelp::getJobclassListById($jobclass_id);
        if (!$result) {
            return false;
        }
        foreach ($result as $key => $value) {
            $sons = Jobhelp::getJobclassListById($value['id']);
            $result[$key]['sons'] = $sons;
        }
        return $result;
    }

    /**
      通过职位获取职位列表
     */
    static public function getJobsByJobclassid($jobclass_id, $start = 0, $perpage = 20) {
        $where = "jstatus=1 and jobclass_id=".$jobclass_id;
        $order = "updatetime desc";
        $limit = $start . "," . $perpage;
        $result = array();
        $result['count'] = Jobhelp::getJobCount($where);
        if (intval($result['count']) > 0) {
            $result['list'] = Jobhelp::getJobList($where, $order, $limit);
            foreach($result['list'] as $key=>$val)
            {
                $val['companyname']=  Enterprisehelp::getCompanyname($val['eid']);
                $result['list'][$key]=$val;
            }
        }
        return $result;
    }

    /**
      通过用户uid 和type 分别获取被邀请和申请的信息
     */
    static public function getJobrelationsByUid($uid, $type) {
        $where = "uid=" . $uid . " and type=" . $type;
        $order = "sendtime desc";
        $result['list'] = Jobhelp::getJobrelationList($where, $order);

        foreach($result['list'] as $key=>$val){
            $val['companyname']=  Enterprisehelp::getCompanyname($val['eid']);
            $val['jname']=  Jobhelp::getJobname($val['jobid']);
            $result['list'][$key]=$val;
        }
        return $result;
    }

    /**
      用户被邀请的列表
     */
    static public function UserinvitedList($uid) {
        $result = Jobhelp::getJobrelationsByUid($uid, 1);
        return $result;
    }
    static public function UserinvitedListSize($uid) {
        $where = "uid=" . $uid . " and type=1";
        //dump($where);

        $result = Jobhelp::getJobrelationCount($where);
        //dump($result);
        return $result;
    }

    /**
      用户申请的列表
     */
    static public function UserApplyList($uid) {
        $result = Jobhelp::getJobrelationsByUid($uid, 0);
        return $result;
    }
    static public function UserApplyListSize($uid) {
        $where = "uid=" . $uid . " and type=0";

        $result = Jobhelp::getJobrelationCount($where);
        return $result;
    }
    /**
      企业已邀请列表
    */
    static public function EnterpriseinvitedList($eid,$start=0,$perpage=20)
    {
         $result=Jobhelp::getJobrelationsByEid($eid,1,$start,$perpage);
         if(intval($result['count'])>0)
         {
             
             foreach($result['list'] as $key=>$val){
               
                $val['companyname']=  Enterprisehelp::getCompanyname($val['eid']);
                $val['jname']=  Jobhelp::getJobname($val['jobid']);
                $val['realname']=Userhelp::getRealname($val['uid']);
                $result['list'][$key]=$val;
            }   
            $result['list']=Jobhelp::groupJobrelations($result['list']);    
         }
         return $result;
    }
    /**
      企业收到申请的列表
    */
    static public function EnterpriseApplyList($eid,$start=0,$perpage=20)
    {
         $result=Jobhelp::getJobrelationsByEid($eid,0,$start,$perpage);
         if(intval($result['count'])>0)
         {
             
             foreach($result['list'] as $key=>$val){
               
                $val['companyname']=  Enterprisehelp::getCompanyname($val['eid']);
                $val['jname']=  Jobhelp::getJobname($val['jobid']);
                $val['realname']=Userhelp::getRealname($val['uid']);
                $result['list'][$key]=$val;
            }      
         }
         return $result;     
    }
    /**
      通过用户eid 和type 分别获取被邀请和申请的信息
     */
    static public function getJobrelationsByEid($eid, $type, $start = 0, $perpage = 20) {
        $where = "eid=" . $eid . " and type=" . $type;
        $order = "sendtime desc";
        $limit = $start . "," . $perpage;
        $result['count'] = Jobhelp::getJobrelationCount($where);
        if (intval($result['count'])) {
            $result['list'] = Jobhelp::getJobrelationList($where, $order,$limit);
        }
        return $result;
    }
    /**
       检查工作是否已经过期
    */
    static public function checkJob($jobid){
        $where="id=".$jobid;
        $jobinfo=  Jobhelp::getJob($where);
        if(!$jobinfo||$jobinfo['jstatus']==0){
            return false;
        }
        if($jobinfo['isexpire']==1){
            $time=time();
            $starttime=strtotime($jobinfo['starttime']);
            $endtime=strtotime($jobinfo['endtime']);
            if($time<$starttime||$time>$endtime){
                return false;
            }
        }
        return $jobinfo;
    }
    /**
     获取工作名
    */
    static public function getJobname($jobid){
        $where="id=".$jobid;
        $result=  Jobhelp::getJob($where);
        return $result['jname'];
    }
    /**
       分组招聘信息
    */
    static public function groupJobrelations($JobrelationList)
    {
        $result=array();
        if($JobrelationList)
        {
            foreach($JobrelationList as $key=>$val)
            {
                $result[$val['jobid']][]=$val;
            }
        }
        return $result;
    }
    static public function getJobListByEid($eid)
    {
        $where="eid=".$eid;
        $order="updatetime desc";
        $result=Jobhelp::getJobList($where,$order);
        foreach($result as $key=>$val)
        {
             if($val['jstatus']==0)
             {
                 unset($result[$key]);              
             }
        }
        return $result;
    }
}

?>
