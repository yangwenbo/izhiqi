<?php

/**
  此类只限于登录的普通用户或者管理员使用。
 */
Class UserAction extends Action {

    public function _initialize() {
        if (!Loginhelp::isUserLogin()) {
            $this->redirect('Login/userLogin');
        }
        $this->uid = Loginhelp::getUserUid();
    }

    public function index() {
        $this->redirect('Job/index');
    }

    /**
      当前用户收到的职位邀请
     */
    public function invitedList() {
        $page=intval($this->_param('p'))?intval($this->_param('p')):1;
        $perpage=10;
        $start=($page-1)*$perpage;
        $result = Jobhelp::UserinvitedList($this->uid,$start,$perpage);
        if(intval($result['count'])>0)
        {
            import('ORG.Util.Page');
            $objPage=new Page($result['count'],$perpage,$page);
            $this->assign('page',$objPage->show());
        }
        $this->assign('result', $result);
        $this->display();
    }

    /**
      当前用户已经申请的职位
     */
    public function appliedList() {
        $page=intval($this->_param('p'))?intval($this->_param('p')):1;
        $perpage=10;
        $start=($page-1)*$perpage;

        $result = Jobhelp::UserApplyList($this->uid,$start,$perpage);
        if(intval($result['count'])>0)
        {
            import('ORG.Util.Page');
            $objPage=new Page($result['count'],$perpage,$page);
            $this->assign('page',$objPage->show());
        }
        $this->assign('result', $result);
        $this->display();
    }

    /**
      处理职位邀请
     */
    public function doInvitedJob() {
        $result = intval($this->_param('result'));
        $jobid = intval($this->_param('jobid'));
        $where = "uid=" . $this->uid . " and jobid=" . $jobid;
        $data = array(
            'result' => $result,
            'operatetime' => date('Y-m-d H:i:s')
        );
        if (Jobhelp::updateJobrelation($where, $data)) {
            $this->success('操作成功');
        }
        $this->error('操作失败');
    }

    /**
      申请工作
     */
    public function applyJob() {
        $jobid = $this->_param('jobid');
        $uid = $this->uid;
        $type = 0; //申请
        $sendtime = date('Y-m-d H:i:s');
        //查询是否已经申请过
        $where = "uid=" . $uid . " and jobid=" . $jobid;
        if (Jobhelp::getJobrelation($where)) {
            $this->error('您已经申请过该职位');
        }
        $jobinfo = Jobhelp::checkJob($jobid);
        if (!$jobinfo) {
            $this->error('该职位不存在或过期');
        }
        $data = array(
            'eid' => $jobinfo['eid'],
            'jobid' => $jobid,
            'uid' => $uid,
            'type' => $type,
            'sendtime' => $sendtime
        );
        if (Jobhelp::insertJobrelation($data)) {
            $this->success('操作成功');
        }
        $this->error('操作失败');
    }

    /**
      推荐朋友
     */
    public function recFriend() {
        $jobid = intval($this->_param('jobid')) ? intval($this->_param('jobid')) : 0;
        if (!$jobid) {
            $this->error('您尚未选择推荐的工作', U('Search/job'));
        }
        if ($_POST) {
            $uid = $this->uid;
            $name = $this->_param('name');
            $email = $this->_param('email');
            $phone = $this->_param('phone');
            $createtime = date('Y-m-d H:i:s');
            if (empty($name) || $name == '朋友姓名') {
                $this->error('请输入朋友姓名');
            }
            if (empty($phone) || $phone == '朋友联系电话') {
                $this->error('请输入朋友联系电话');
            }

            if (empty($email) || $email == '朋友常用邮箱地址') {
                $this->error('朋友常用邮箱地址');
            }
            if (!isEmail($email)) {
                $this->error('请输入正确的邮箱格式');
            }
            $data = array(
                'uid' => $uid,
                'name' => $name,
                'email' => $email,
                'jobid' => $jobid,
                'phone' => $phone,
                'createtime' => $createtime
            );
            if (Recommendhelp::insertRecommend($data)) {
                $this->success('推荐成功');
            }
            var_dump(D('Recommend')->_sql());
            exit;
            $this->error('推荐失败');
        }
        $this->assign('jobid', $jobid);
        $this->display();
    }

    /**
      用户信息
     */
    public function userinfo() {
        $uid = Loginhelp::getUserUid();
        $userinfo = Userhelp::getUserinfoByUid($uid);
        if (!$userinfo) {
            $this->error('用户不存在');
        }
        if($_POST)
        {
            $doType=$this->_param('doType')?$this->_param('doType'):'all';
            
            switch($doType)
            {
                case 'delUserWork':
                    $this->delUserWork();
                    break;
                case 'addUserWork':
                    $this->addUserWork();
                    break;
                case 'updateWork':
                    $this->updateWork();
                    break;
                case  'updateSkill':
                    $this->updateSkill();
                    break;
                case  'updateDesc':
                    $this->updateDesc();
                    break;
                default :
                    $this->updateUserinfo();
                    $companyname=$this->_param('companyname');
                    if(empty($companyname)||$companyname=='公司名称')
                    {
                        $companyname='';
                    }
                    if(!empty($companyname))
                    {
                        $this->addUserWork();
                    }
                    $this->updateSkill();
                    $this->updateDesc();
            }
            $this->success('用信息更新成功');
        }
        $this->assign('userinfo',$userinfo);
        $this->assign('tab','info');
        $this->display();
    }
    public  function updateUserinfo()
    {
        if($_POST)
        {
            $schoolname=$this->_param('schoolname');
            $major=$this->_param('major');
            $age=intval($this->_param('age'))?intval($this->_param('age')):0;
            $height=intval($this->_param('height'))?intval($this->_param('height')):0;
            $phone=$this->_param('phone')?$this->_param('phone'):'';
            $qq=$this->_param('qq')?$this->_param('qq'):'';
            if($phone&&!is_numeric($phone))
            {
               $this->error('手机号格式不正确');
            }
            if($qq&&!is_numeric($qq))
            {
                $this->error('qq号格式不正确');
            }
            $time=date('Y-m-d H:i:s');
            $where="uid=".$this->uid;
            $data=array(
                'age'=>$age,
                'height'=>$height,
                'phone'=>$phone,
                'qq'=>$qq,
                'updatetime'=>$time
                );
            if(!Userhelp::updateUserinfo($where,$data))
            {
                $this->error('更新用户信息失败');
            }
            $data=array(
                'schoolname'=>$schoolname,
                'major'=>$major,
                'updatetime'=>$time
                );
            Userhelp::updateUsereducation($where,$data);
        }
    }
    /**
      删除用户工作经验
     */
    public  function delUserWork() {
        if ($_POST) {
            $id = $this->_param('id');
            $where = "uid=" . $this->uid . " and id=" . $id;
            $data = array(
                'status' => 0,
                'updatetime'=>date('Y-m-d H:i:s')
            );

            if (!Userhelp::updateUserwork($where, $data)) {
                $this->success('删除失败');
            }
            $this->error('删除成功');
        }
    }

    /**
      添加用户工作经验
     */
    public function addUserWork() {
        if ($_POST) {
            $companyname = $this->_param('companyname');
            $jobname = $this->_param('jobname');
            $workstartdate = $this->_param('workstartdate');
            $workenddate = $this->_param('workenddate');
            $workcontent = $this->_param('workcontent');
            
            if(empty($companyname)||$companyname=='公司名称')
            {
                $this->error('请输入公司名称');
            }
            if(empty($jobname)||$jobname=='职位')
            {
                 $this->error('请输入职位');
            }
            if(empty($workenddate)||$workenddate=='入职日期')
            {
                $this->error('请选择入职日期');
            }
            if (empty($workenddate)||$workenddate=='离职日期') {
                $workenddate='0000-00-00';
            }
            
            $createtime=date('Y-m-d H:i:s');

            $data = array(
                'uid' => $this->uid,
                'companyname' => $companyname,
                'workstartdate' => $workstartdate,
                'workenddate' => $workenddate,
                'workcontent' => $workcontent,
                'jobname' => $jobname,
                'createtime' => $createtime
            );
            Userhelp::insertUserwork($data);
            
        }
    }

    /**
      修改用户工作经验
     */
    public function updateWork() {
        if ($_POST) {
            $id = intval($this->_param('id'));
            $companyname = $this->_param('companyname');
            $workstartdate = $this->_param('workstartdate');
            $workenddate = $this->_param('workenddate');
            $workcontent = $this->_param('workcontent');
            $jobname = $this->_param('jobname');
            $updatetime=date('Y-m-d H:i:s');

            $where = "uid=" . $this->uid . " and id=" . $id;
            $data = array(
                'companyname' => $companyname,
                'workstartdate' => $workstartdate,
                'workenddate' => $workenddate,
                'workcontent' => $workcontent,
                'jobname' => $jobname,
                'updatetime' => $updatetime
            );
            Userhelp::updateUserwork($where, $data);
        }
    }

    /**
      修改个人技能
     */
    public function updateSkill() {
        if ($_POST) {
            $skill = $this->_param('skill');
            $where = "uid=" . $this->uid;
            $data = array(
                'skill' => $skill,
                'updatetime'=>$updatetime
            );
            Userhelp::updateUserinfoext($where, $data);
            
        }
    }

    /**
      修改自我描述
     */
    public function updateDesc() {
        if ($_POST) {
            $desc = $this->_param('desc');
            $where = "uid=" . $this->uid;
            $data = array(
                'desc' => $desc,
                'updatetime'=>$updatetime
            );
            Userhelp::updateUserinfoext($where, $data);
            
        }
    }

    /**
      修改密码
     */
    public function setting() {
        if ($_POST) { 
            $oldpassword = $this->_param('oldpassword');
            $newpassword = $this->_param('newpassword');
            $renewpassword = $this->_param('renewpassword');
            $uid = $this->uid;
            $where = "id=" . $uid;
            $user = Userhelp::getUserlogin($where);
            $oldpassword=Loginhelp::encrypt($oldpassword);
            if(strcasecmp($user['password'],$oldpassword)!==0){
                $this->error('旧密码输入错误,请输入正确的密码');
            }
            if (strcasecmp($newpassword, $renewpassword)!== 0) {
                $this->error('新密码和确认密码不一致，请重新输入');
            }

            $data = array(
                'password' => Loginhelp::encrypt($newpassword)
            );
            if (Userhelp::updateUserlogin($where, $data)) {
                $this->success('修改密码成功');
            }
            $this->error('修改密码失败');
        }
        $this->assign('tab','setting');
        $this->display();
    }

}

?>