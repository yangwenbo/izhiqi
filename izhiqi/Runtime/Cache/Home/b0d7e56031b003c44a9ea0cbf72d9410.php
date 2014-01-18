<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册</title>
<link href="__CSSURL__/base.css" rel="stylesheet" type="text/css" />
<link href="__CSSURL__/common.css" rel="stylesheet" type="text/css" />
<link href="__CSSURL__/personal.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- 公用 personal header { -->
<div class="header">
	<div class="header_wrap clearfix">
    	<h1 class="fl logo mt30">
        	<a href="/">LOGO</a>
        </h1>
        <?php if($head == 'login'): ?><ul class="fr nav mt30 f14 fb tc">
            <li><a href="<?php echo U('Login/userRegister');?>">注册</a></li>
            <li><a href="<?php echo U('Login/userLogin');?>" class="on">登录</a></li>
        </ul>
          <?php else: ?>
        <ul class="fr nav mt30 f14 fb tc">
            <li><a href="<?php echo U('Search/job');?>" <?php if($tab == 'search'): ?>class="on"<?php endif; ?>>职位</a></li>
            <li><a href="<?php echo U('User/userinfo');?>" <?php if($tab == 'info'): ?>class="on"<?php endif; ?>>简历</a></li>
            <li><a href="###" <?php if($tab == 'usercenter'): ?>class="on"<?php endif; ?>>帐号</a></li>
            <li><a href="<?php echo U('User/setting');?>" <?php if($tab == 'setting'): ?>class="on"<?php endif; ?>>个人设置</a></li>
        </ul><?php endif; ?>
    </div>
</div>

<!-- } 公用 personal header -->


<!-- wrapper { -->
<div class="frame_s">
	<div class="bg_top"></div>
    <!-- title { -->
    <div class="title">
    	<em class="c8 f14 ml30">注册职启</em>
        <em class="mc f14 ml20">注册帐号</em>
    </div>
    <!-- } title -->
    <form id="register" action="<?php echo U(Login/userRegister);?>" method="POST">
    <!-- 内容 { -->
    <div class="wrap p30">
    	
        <ul class="reg_list">
        
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='真实姓名') {this.value='';this.style.color='';}" value="真实姓名" name="realname" datatype="zh2-4" nullmsg="请输入真实姓名" errormsg="真实姓名请输入2-4位中文汉字"/>
                </div>
                <p class="fr more_info">您的个人信息会展示在最终的简历当中，并受隐私协议保护，请务必使用真实姓名注册</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='电子邮箱') {this.value='';this.style.color='';}" value="电子邮箱" name="email" id="email" datatype="e" nullmsg="请输入您的邮箱" errormsg="您输入的邮箱格式不正确，请重新输入"/>
                </div>
                <p class="fr more_info">请正确填写邮箱，用于密码找回！</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg2">
                    <input type="text" class="text_bg2 cc" onfocus="if (this.value=='验证码') {this.value='';this.style.color='';}" value="验证码" name="verifycode" datatype="s5-8" nullmsg="请输入邮箱中验证码" errormsg="输入的验证码有误"/>
                </div>
                <div class="fl ml30 btn1">
                    <a class="text_bg tc cf" href="javascript:;" id="email_verifycode">获取邮箱验证码</a>
                </div>
                <p class="fr more_info">验证您的邮箱！</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="password" class="text_bg1 cc" value="" name="password" datatype="*6-20" nullmsg="请输入6-20个字符的密码"/>
                </div>
                <p class="fr more_info">请正确填写密码</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                <input type="password" class="text_bg1 cc" value="" name="repassword" datatype="*" recheck="password" nullmsg="请输入确认密码"/>
                </div>
                <p class="fr more_info">请正确填写确认密码</p>
            </li>
            
        </ul>
        
    </div>
    <!-- } 内容 -->
    
    <div class="bg_f pt20 pb10">
    	<div class="bc btn2">
        	<a href="javascript:" id="btnRegister" class="text_bg tc cf">下一步，填写个人信息</a>
        </div>
    </div>    
</form>
    <div class="bg_bottom"></div>
</div>
<!-- } wrapper -->


<!-- 公用 footer { -->
<!-- 公用 footer { -->
<div class="cl footer">
	<div class="f_cont clearfix">
        <div class="fr f_nav tr pt40">
            <a href="javascript:;" class="f14 c8 pl20 pr20">用户协议</a>
            <a href="javascript:;" class="f14 c8 pl20 pr20">加入我们</a>
        </div>
    </div>
</div>
<!-- } 公用 footer -->
<script src="__JSURL__/jquery-1.8.2.min.js"></script>
<!-- } 公用 footer -->
<script src="__JSURL__/Validform_v5.3.2.js" type="text/javascript" charset="UTF-8"></script>


<script>
var sendVerifycodeUrl="<?php echo U('Verifyemail/sendEmail');?>";
$(function(){
    var vf=$("#register").Validform({
        btnSubmit:"#btnRegister", 
        tiptype:3, 
        label:".label",
        showAllError:true,
        datatype:{
            "zh1-6":/^[\u4E00-\u9FA5\uf900-\ufa2d]{1,6}$/
        }
    });


    $('#email_verifycode').click(function(){ 
         var email=$('#email').val();
         if(email.length<1)
         {
             alert('邮箱不能为空');
             return;
         }
         url=sendVerifycodeUrl+"?email="+email;
         $.ajax({
             url:url,
             type:'get',
             dateType:'JSON',
             success:function(data){
                 if(data.status==100){
                     alert(data.info);
                 }else{
                     $('#email').focus();
                     alert(data.info);
                     return;
                 }

             }
         });

    });
});
</script>
</body>
</html>