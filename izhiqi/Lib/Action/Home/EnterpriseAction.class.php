<?php

class EnterpriseAction extends Action {

    public function _initialize() {
        if (!Loginhelp::isEnterpriseLogin()) {
            $this->redirect('Login/enterpriseLogin');
        }
        $this->eid = Loginhelp::getEid();

    }
    /**
    邀请列表
    */
    public function invitedList()
    {
        $page=intval($this->_param('p'))?intval($this->_param('p')):1;
        $perpage=10;
        $start=($page-1)*$perpage;
        $result=Jobhelp::EnterpriseinvitedList($this->eid,$start,$perpage);
        
        if(intval($result['count'])>0)
        {
            import('ORG.Util.Page');
            $objPage=new Page($result['count'],$perpage,$page);
            $this->assign('page',$objPage->show());
        }
        $this->assign('result',$result);
        $this->display();
    }
    /**
    收到的申请的列表
    */
    public function appliedList()
    { 
        $page=intval($this->_param('p'))?intval($this->_param('p')):1;
        $perpage=20;
        $start=($page-1)*$perpage;

        $result=Jobhelp::EnterpriseApplyList($this->eid,$start,$perpage);
        if(intval($result['count'])>0)
        {
            import('ORG.Util.Page');
            $objPage=new Page($result['count'],$perpage,$page);
            $this->assign('page',$objPage->show());
        }
        $this->assign('result',$result);
        $this->display();
    }
    /**
    处理申请
    */
    public function doApply()
    {
        $id=intval($this->_param('id'))?intval($this->_param('id')):0;
        $result=intval($this->_param('result'))?intval($this->_param('result')):0;
        if(empty($id))
        {
            $this->error("非法操作");
        }
        $where="id=".$id;
        $jobrelation=Jobhelp::getJobrelation($where);
        if(!$jobrelation)
        {
            $this->error('非法操作(1)');
        }
        if($jobrelation['eid']!=$this->eid)
        {
            $this->error('非法操作(2)');
        }
        if($_POST)
        {
            $interview_time = $this->_param('interview_time');
            $interview_address = $this->_param('interview_address');
            if(empty($interview_time)||$interview_time=="面试时间")
            {
                $this->error("请选择面试的时间");
            }
            if(empty($interview_address)||$interview_address=="面试地址")
            {
                $this->error("请输入面试地址");
            }

            $data = array(
                'id'=>$id,
                'interview_time' => $interview_time,
                'interview_address' => $interview_address,
                'result'=>1,
                'operatetime'=>date('Y-m-d H:i:s')
            ); 
            if(Jobhelp::updateJobrelation($where,$data))
            {
               $this->success("发送面试通知成功",'appliedList');
            }
            $this->error("发送面试通知失败");   
        }else
        {
            $jobrelation['jname']=Jobhelp::getJobname($jobrelation['jobid']);
            if($result==2)
            {
                $data=array(
                  'result'=>$result,
                  'operatetime'=>date('Y-m-d H:i:s')
                ); 
                if(Jobhelp::updateJobrelation($where,$data))
                {
                   $this->success("“拒绝”的操作成功");
                }
                $this->error("“拒绝”的操作失败");
            }else
            {
                 $this->assign('jobrelation',$jobrelation);
                 $this->display();
            } 
        }
    }
    //邀请
    public function invite() {
        $uid = $this->_param('uid');
        if ($_POST) {
            $jobid=$this->_param('jobid');
            $interview_time = $this->_param('interview_time');
            $interview_address = $this->_param('interview_address');
            if(empty($jobid))
            {
                $this->error("请选择职位");
            }
            if(empty($interview_time)||$interview_time=="面试时间")
            {
                $this->error("请选择面试的时间");
            }
            if(empty($interview_address)||$interview_address=="面试地址")
            {
                $this->error("请输入面试地址");
            }

            $type = 1; //邀请

            $sendtime = date('Y-m-d H:i:s');
            $data = array(
                'eid' => $this->eid,
                'uid' => $uid,
                'jobid' => $jobid,
                'interview_time' => $interview_time,
                'interview_address' => $interview_address,
                'type' => $type,
                'sendtime' => $sendtime
            );
            if (Jobhelp::insertJobrelation($data)) {
                $this->success('邀请成功！');
            }
            $this->error('邀请失败!');
        }
        $time = date('Y-m-d H:i:s');
        $where = "eid=" . $this->eid . " and jstatus=1";
        $arrjob = Jobhelp::getJobList($where);

        $this->assign('arrjob', $arrjob);

        $this->assign('uid',$uid);
        $this->display();
    }

    //企业信息
    public function enterpriseinfo() {
        $info = Enterprisehelp::getEnterpriseinfo($this->eid);
        if ($_POST) {
            $btntype=$this->_param("btntype");//  1，只保存部分，2全保存
            $jname=$this->_param('jname');
            $date=date('Y-m-d H:i:s');
            if(empty($jname)||$jname=='职位名称')
            {
                $jname='';
            }
            if($btntype==1||!empty($jname))
            {
                $jobclassid=intval($this->_param('jobclassid'))?intval($this->_param('jobclassid')):0;
                $jcontent = trim($this->_param('jcontent'));
                $reward = intval($this->_param('reward'))?intval($this->_param('reward')):0;
                $jobnum = intval($this->_param('jobnum'))?$this->_param('jobnum'):0;
                
                if(empty($jname)||$jname=='职位名称')
                {
                    $this->error("请输入职位名称");
                }
                if(!$jobclassid)
                {
                    $this->error("职位类别没有选择全");
                }
                if(empty($jcontent)||$jcontent=='职位职责')
                {
                    $this->error("请输入职位职责");
                }
                if(!$jobnum)
                {
                    $this->error("请输入招聘人数");
                }
                $data = array(
                    'eid'=>$this->eid,
                    'jname' => $jname,
                    'jcontent' => $jcontent,
                    'jobclass_id'=>$jobclassid,
                    'reward' => $reward,
                    'jobnum' => $jobnum,
                    'updatetime'=>$date
                );
                if (!Jobhelp::insertJob($data)) {
                    $this->error('添加职位失败');
                }
                if($btntype==1){
                    $this->error('添加职位成功');
                }
            }
            
            // $province = $this->_param('province');
            // $city = $this->_param('city');
            $address = $this->_param('address')?$this->_param('address'):'';
            $website = $this->_param('website')?$this->_param('website'):'';
            $scale = intval($this->_param('scale'))?intval($this->_param('scale')):0;
            $companydesc = $this->_param('companydesc')?$this->_param('companydesc'):'';
            $data = array(
                // 'province' => $province,
                // 'city' => $city,
                'address' => $address,
                'website' => $website,
                'scale' => $scale,
                'updatetime'=>$updatetime
            );
            //更新企业信息
            $where = "eid=" . $this->eid;
            Enterprisehelp::updateEnterprise($where, $data);

            //更新企业扩展信息
            $data = array(
                'companydesc' => $companydesc,
                'updatetime'  =>$updatetime
                );
            Enterprisehelp::updateEnterpriseext($where, $data);
            
            
            $this->success('更企业信息成功');
        }
        
        $this->assign('info', $info);
        
        $arrScale=C('COMPANY_SCALE');
        $this->assign('arrScale',$arrScale);

        $jobclass_first=Jobhelp::getLevel1();
        $this->assign('jobclass_first',$jobclass_first);
        $this->assign('tab','info');
        $this->display();
    }

    /**
      修改密码
     */
    public function setting() {
        $info = Enterprisehelp::getEnterpriseinfo($this->eid);
        if ($_POST) {
            $oldpassword = $this->_param('oldpassword');
            $newpassword = $this->_param('newpassword');
            $renewpassword = $this->_param('renewpassword');
            $eid = $this->eid;
            $where = "id=" . $eid;
            $ep = Enterprisehelp::getEnterpriselogin($where);
            $oldpassword=Loginhelp::encrypt($oldpassword);
            if(strcasecmp($ep['password'],$oldpassword)!==0){
                $this->error('旧密码输入错误,请输入正确的密码');
            }
            if (strcasecmp($newpassword, $renewpassword)!== 0) {
                $this->error('新密码和确认密码不一致，请重新输入');
            }

            $data = array(
                'password' => Loginhelp::encrypt($newpassword)
            );
            if (Enterprisehelp::updateEnterpriselogin($where, $data)) {
                $this->success('修改密码成功');
            }
            $this->error('修改密码失败');
        }
        $this->assign('info',$info);
        $this->assign('tab','setting');
        $this->display();
    }
    
    /**
     * 用户搜索
     */
    public function userSearch()
    {
        $arrEducation=C('EDUCATION');
        $this->assign('arrEducation',$arrEducation);
        $this->assign('tab','search');
        $this->display();
    }
    /**
      用户搜索列表
     */
    public function userSearchList() {
        $page = intval($this->_param('p')) ? intval($this->_param('p')) : 1;
        $perpage = 10;
        $start = ($page - 1) * $perpage;

        $keyword = $this->_param('keyword');
//        $companyname = $this->_param('companyname');
        $education = intval($this->_param('education'))?intval($this->_param('education')):0;
        $sex = intval($this->_param('sex'))?intval($this->_param('sex')):0;
        $age = intval($this->_param('age'))?intval($this->_param('age')):0;
        $height = intval($this->_param('height'))?intval($this->_param('height')):0;
//        $language = $this->_param('language');
        //验证输入的条件
        if($keyword=='关键字')
        {
            $keyword=null;
        }
        $map = array();
        if ($keyword) {
            $map['jobintension'] = array('like', '%' . $keyword . '%');
        }
//        if ($companyname) {
//            $map['companyname'] = array('like', '%' . $companyname . '%');
//        }
        if ($education) {
            $map['education'] = array('eq', $education);
        }
        if ($sex) {
            $map['sex'] = array('eq', $sex);
        }
        if ($age > 0) {
            $map['age'] = array('eq', $age);
        }
        if ($height > 0) {
            $map['height'] = array('eq', $height);
        }
//        if ($language) {
//            $map['language'] = array('eq', $language);
//        }
        $result['count'] = Userhelp::getUserinfoCount($map);
        if (intval($result['count']) > 0) {
            $order = 'updatetime desc';
            $limit = $start . ',' . $perpage;
            $result['list'] = Userhelp::getUserinfoList($map, $order, $limit);

            import('ORG.Util.Page');
            $objPage=new Page($result['count'],$perpage,$page);
            $this->assign('page',$objPage->show());
        }
        $this->assign('result', $result);
        $this->assign('tab','search');
        $this->display();
    }
    /**
     浏览用户信息
    */
    public function userInfo()
    {
        $uid=intval($this->_param('uid'))?intval($this->_param('uid')):0;
        $userinfo = Userhelp::getUserinfoByUid($uid);
        // var_dump($userinfo); 
        if (!$userinfo) {
            $this->error('用户不存在');
        }
        $this->assign('userinfo',$userinfo);
        $this->display();
    }
    /**
     删除工作
    */
    public function delJob($jobid)
    {
        $where="id=".$jobid;
        $jobinfo=Jobhelp::getJob($where);
        if(!$jobinfo||$jobinfo['eid']!=$this->eid)
        {
            $this->error("非法操作");
        }
        $data=array(
               "jstatus"=>0
            );
        if(Jobhelp::updateJob($where,$data))
        {
            $this->success("删除成功");
        }
        $this->error("删除失败");
    }
}

?>