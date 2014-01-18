<?php
class VerifyemailAction extends Action
{
    public function sendEmail()
    {
        $email=$this->_param('email');
        if(!$this->verifyEmail($email))
        {
            $this->ajaxReturn(array('status'=>101,'info'=>$this->errorMsg),'JSON');
        }
        $code=Verifyhelp::createVerifycode();
        if($this->sendVerifyEmail($email, $code)){
            $this->ajaxReturn(array('status'=>100,'info'=>"验证码已经发你邮箱,请查收，并将验证填入对应的文本框中。"),'JSON');    
        }
    }
    /**
     * 发送验证邮件
     */
    private function sendVerifyEmail($email,$token){
        if(empty($email) || empty($token)){
            return false;
        }
        $content = "尊敬的用户：<br>
        您进行了邮箱验证操作，请复制下面的验证码到指定的位置：<br>
        <b>$token</b> 链接将在2小时后失效，请及时完成验证。<br><br>
        注：此邮箱由系统自动发出，请勿回复。";
        $subject = '爱职网邮箱验证';
        SendEmail::send_simple_mail($email, $subject, $content);
        return true;
    }
    /**
     * 验证邮箱格式
     * @param string $email
     */
    private function verifyEmail($email){
        if(empty($email)){
            $this->errorMsg = '邮箱不能为空';
            return false;
        }
        if(!isEmail($email)){
            $this->errorMsg = '邮箱格式不正确';
            return false;
        }
        return  true;
    }
}