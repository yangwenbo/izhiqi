<?php

class LoginAction extends Action {

    /**
      用户注册
     */
    public function userRegister() {
        if ($_POST) {
            $realname = $this->_param('realname');
            $email = $this->_param('email');
            $verifycode = $this->_param('verifycode');
            $password = $this->_param('password');
            $repassword = $this->_param('repassword');
            $referer_uid = $this->_param('referer_uid') ? $this->_param('referer_uid') : 0;
            if (empty($realname) || $realname == '真实姓名') {
                $this->error('请输入真实姓名！');
            }
            if (empty($email) || !isEmail($email)) {
                $this->error('请输入正确的email地址！');
            }
            if (empty($verifycode) || $verifycode == '验证码') {
                $this->error('请填写邮箱验证码');
            }
            if (empty($password) || $password == '密码') {
                $this->error('请输入密码');
            }
            if (empty($repassword) || $repassword == '确认密码') {
                $this->error('请输入确认密码');
            }
            if (!checkPassword($password, $repassword)) {
                $this->error('两次的密码不一致.');
            }
            // if(!Verifyhelp::checkVerifycode($verifycode)){
            //     $this->error('验证码错误.');
            // }
            if (Userhelp::getUserloginByEmail($email)) {
                $this->error('该邮箱已存在，请选择其他邮箱');
            }
            $time = date('Y-m-d H:i:s');
            $data = array(
                'email' => $email,
                'password' => Loginhelp::encrypt($password),
                'register_time' => $time,
                'login_time_last' => $time,
                'referer_uid' => $referer_uid
            );
            $uid = Userhelp::insertUserlogin($data);
            if (!$uid) {
                $this->error('注册用户失败！');
            }
            $data = array(
                'uid' => $uid,
                'realname' => $realname,
                'displayname' => $realname,
                'createtime' => $time,
                'updatetime' => $time,
            );
            $res = Userhelp::insertUserinfo($data);
            if (!$res) {
                $this->error('注册用户信息失败');
            }
            $data = array(
                'uid' => $uid,
                'updatetime' => $time,
            );
            $res1 = Userhelp::insertUserinfoext($data);
            if (!$res1) {
                $this->error('注册用户扩展信息失败');
            }
            $data = array(
                'uid' => $uid
            );
            $res2 = Userhelp::insertUsercoins($data);
            if (!$res) {
                $this->error('注册用户金币信息失败');
            }
            Loginhelp::userLogin($uid);
            $this->redirect('login/addUserinfo');
        }

        $this->display();
    }

    /**
      注册的第二步骤：用户信息
     */
    public function addUserinfo() {
        $uid = Loginhelp::getUserUid();
        if (!$uid) {
            $this->redirect('Login/userLogin');
        }
        if ($_POST) {
            $schoolname = $this->_param('schoolname');
            $major = $this->_param('major');
            $schoolstartdate = $this->_param('schoolstartdate');
            $schooladdress = $this->_param('schooladdress');
            $sex = $this->_param('sex');
            $age = intval($this->_param('age')) ? intval($this->_param('age')) : 0;

            if (empty($schoolname) || $schoolname == '毕业院校') {
                $this->error('请输入毕业院校');
            }
            if (empty($major) || $major == '专业') {
                $this->error('请输入所学专业');
            }
            if (empty($schoolstartdate) || $schoolstartdate == '入学日期') {
                $this->error('请选择入学日期');
            }
            if (empty($schooladdress) || $schooladdress == '地址（学校）') {
                $this->error('请选择学校地址');
            }
            if (empty($age) || $age == '年龄' || $age < 1) {
                $this->error('请选择正确的年龄');
            }
            //------上传文件 start
            $avartar = "";
            if ($_FILES['avartar']['error'] == 0) {
                $arrAvartar = Uploadhelp::uploadAvartar($_FILES['avartar']);
                if ($arrAvartar['code'] == 100) {
                    $avartar = $arrAvartar['info']['savepath']  . $arrAvartar['info']['savename'];
                } else {
                    $this->error($arrAvartar['info']);
                }
            }else{
                $this->error('你忘记上传照片了:(');
            }
            //------上传文件 end
            $time = date('Y-m-d H:i:s');
            $where = "uid=" . $uid;
            $data = array(
                'avartar' => $avartar,
                'sex' => $sex,
                'age' => $age,
                'updatetime' => $time
            );
            $res = Userhelp::updateUserinfo($where, $data);
            if (!$res) {
                $this->error('修改用户信息失败');
            }

            $data = array(
                'uid' => $uid,
                'schoolname' => $schoolname,
                'major' => $major,
                'schoolstartdate' => $schoolstartdate,
                'schooladdress' => $schooladdress,
                'createtime' => $time,
                'updatetime' => $time,
            );
            $res1 = Userhelp::insertUsereducation($data);
            if (!$res1) {
                $this->error('修改用户教育信息失败');
            }
            $this->success('注册成功', U('Search/job'));
            exit;
        }
        $this->display();
    }

    /**
      用户登录
     */
    public function userLogin() {
        if (Loginhelp::isUserLogin()) {
            $this->redirect('User/userinfo');
        }
        if ($_POST) {
            $email = $this->_param('email');
            $password = $this->_param('password');
            if (empty($email)) {
                $this->error('请输入您的邮箱');
            }
            if (!isEmail($email)) {
                $this->error('请输入格式正确的邮箱');
            }
            if (empty($password)) {
                $this->error('请输入密码');
            }
            $result = Userhelp::getUserloginByEmail($email);
            if (!$result) {
                $this->error('该账户不存在');
            }
            if (strcasecmp($result['password'], Loginhelp::encrypt($password)) !== 0) {
                $this->error('密码错误,请重新输入');
            }
            Loginhelp::userLogin($result['id']);
            $this->redirect('Search/job');
        }
        $this->assign('head', 'login');
        $this->display();
    }

    /**
      用户登出
     */
    public function userLogout() {
        Loginhelp::userLogout();
        if (Loginhelp::isUserLogin()) {
            $this->redirect('/');
        }
        $this->error('登出异常');
    }

//企业用户注册  添加企业信息  登录， 登出
    /**
      企业用户注册
     */
    public function enterpriseRegister() {

        if ($_POST) {
            $linkman = $this->_param('linkman');
            $companyname = $this->_param('companyname');
            $phone = $this->_param('phone');
            $email = $this->_param('email');
//            $verifycode = $this->_param('verifycode');
            $password = $this->_param('password');
            $repassword = $this->_param('repassword');
            $referer_uid = $this->_param('referer_uid');
            if (empty($linkman) || $linkman =='联系人姓名') {
                $this->error('请输入联系人姓名');
            }
            if (empty($companyname) || $companyname =='公司名称') {
                $this->error('请输入公司名称');
            }
            if (empty($phone) || $phone == '联系电话') {
                $phone=0;
            }
            if(!empty($phone)&&!is_numeric($phone))
            {
                $this->error('输入的联系电话格式有误');
            }
            if (empty($email) || $email == '邮箱地址') {
                $this->error('请输入邮箱地址');
            }
            if (!isEmail($email)) {
                $this->error('邮箱格式不正确');
            }
            if (empty($password) || $password == '') {
                $this->error('请输入密码');
            }

            if (!checkPassword($password, $repassword)) {
                $this->error('两次的密码不一致.');
            }
//            if (!Verifyhelp::checkRegisterVerifycode($verifycode)) {
//                $this->error('验证码错误.');
//            }
            if (Enterprisehelp::getEnterpriseloginByEmail($email)) {
                $this->error('该邮箱已存在，请选择其他邮箱');
            }
            $time = date('Y-m-d H:i:s');
            $data = array(
                'email' => $email,
                'password' => Loginhelp::encrypt($password),
                'register_time' => $time,
                'login_time_last' => $time
            );
            $eid = Enterprisehelp::insertEnterpriselogin($data);
            if (!$eid) {
                $this->error('注册企业用户失败！');
            }
            $data = array(
                'eid' => $eid,
                'linkname' => $linkname,
                'companyname' => $companyname,
                'updatetime' => $time,
            );
            $res = Enterprisehelp::insertEnterprise($data);
            if (!$res) {
                $this->error('注册企业信息失败');
            }
            $data = array(
                'eid' => $eid,
                'updatetime' => $time,
            );
            $res1 = Enterprisehelp::insertEnterpriseext($data);
            if (!$res1) {
                $this->error('注册企业扩展信息失败');
            }
            Loginhelp::enterpriseLogin($eid);
            $this->redirect('Login/addEnterprise');
        }

        $this->display();
    }

    /**
      企业用户注册   企业信息
     */
    public function addEnterprise() {
        $eid = Loginhelp::getEid();
        if (!$eid) {
            $this->redirect('Login/enterpriseLogin');
        }
        if ($_POST) {
            $companydesc = $this->_param('companydesc');
            $scale = $this->_param('scale');
            
            if (empty($companydesc) || $companydesc =='公司简介') {
                $this->error('请输入公司简介');
            }
             //------上传文件 start
             $logo = "";
             $img_business_license = ""; //企业执照
             $img_organization_code = ""; //组织代码
             $img_tax_registration_certificate = ""; //税务登记
            //上传logo
            if ($_FILES['logo']['error'] == 0) {
                $uploadinfo = Uploadhelp::uploadLogo($_FILES['logo']);
                if ($uploadinfo['code'] == 100) {
                    $logo = $uploadinfo['info']['savepath'] . $uploadinfo['info']['savename'];
                } else {
                    $this->error('企业LOGO：'.$uploadinfo['info']);
                }
            }
            //企业执照
            if ($_FILES['img_business_license']['error'] == 0) {
                $uploadinfo = Uploadhelp::uploadCertificate($_FILES['img_business_license']);
                if ($uploadinfo['code'] == 100) {
                    $img_business_license = $uploadinfo['info']['savepath']  . $uploadinfo['info']['savename'];
                } else {
                    $this->error('企业执照：'.$uploadinfo['info']);
                }
            }
            //组织代码
            if ($_FILES['img_organization_code']['error'] == 0) {
                $uploadinfo = Uploadhelp::uploadCertificate($_FILES['img_organization_code']);
                if ($uploadinfo['code'] == 100) {
                    $img_organization_code = $uploadinfo['info']['savepath'] . $uploadinfo['info']['savename'];
                } else {
                    $this->error('组织代码：'.$uploadinfo['info']);
                }
            }
            //税务登记
            if ($_FILES['img_tax_registration_certificate']['error'] == 0) {
                $uploadinfo = Uploadhelp::uploadCertificate($_FILES['img_tax_registration_certificate']);
                if ($uploadinfo['code'] == 100) {
                    $img_tax_registration_certificate = $uploadinfo['info']['savepath'] . $uploadinfo['info']['savename'];
                } else {
                    $this->error('税务登记：'.$uploadinfo['info']);
                }
            }
            //------上传文件 end
            $where = "eid=" . $eid;
            $data = array(
                'scale' => $scale,
                'logo'=>$logo
            );
            $res = Enterprisehelp::updateEnterprise($where, $data);
            if (!$res) {
                $this->error('修改企业信息失败');
            }
            $data = array(
                'companydesc' => $companydesc,
                'img_business_license' => $img_business_license,
                'img_organization_code' => $img_organization_code,
                'img_tax_registration_certificate' => $img_tax_registration_certificate
            );
            $res1 = Enterprisehelp::updateEnterpriseext($where, $data);
            if (!$res1) {
                $this->error('修改企业扩展信息失败');
            }
            $this->success('注册成功！',U('Enterprise/userSearch'));
        }
        $arrScale=C('COMPANY_SCALE');
        $this->assign('arrScale',$arrScale);
        $this->display();
    }

    /**
      企业登录
     */
    public function enterpriseLogin() {
        if (Loginhelp::isEnterpriseLogin()) {
            $this->redirect('Enterprise/enterpriseinfo');
        }
        if ($_POST) {
            $email = $this->_param('email');
            $password = $this->_param('password');
            $result = Enterprisehelp::getEnterpriseloginByEmail($email);
            if (!$result) {
                $this->error('该账户不存在');
            }
            if (strcasecmp($result['password'], Loginhelp::encrypt($password)) !== 0) {
                $this->error('密码错误,请重新输入');
            }
            Loginhelp::enterpriseLogin(($result['id']));
            $this->redirect('Enterprise/userSearch');
        }
        $this->display();
    }

    /**
      企业登出
     */
    public function EnterpriseLogout() {
        Loginhelp::EnterpriseLogout();
        if (Loginhelp::isEnterpriseLogin()) {
            $this->redirect('/');
        }
        $this->error('登出异常');
    }

}

?>