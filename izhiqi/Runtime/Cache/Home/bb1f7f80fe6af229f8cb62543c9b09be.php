<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
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

<form id='loginform' method="POST">
<!-- wrapper { -->
<div class="frame_s">
	<div class="bg_top"></div>
    <!-- title { -->
    <div class="title">
    	<em class="c8 f14 ml30">登录职启</em>
    </div>
    <!-- } title -->
    
    <!-- 内容 { -->
    <div class="wrap p30">
    	
        <ul class="reg_list">
        
            <li class="clearfix">
                <p class="ml40 fl w100 pt10 f14 c8">邮箱地址</p>
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1" value="" name="email" id="email" />
                </div>
            </li>
            
            <li class="clearfix">
                <p class="ml40 fl w100 pt10 f14 c8">密码</p>
                <div class="fl input_bg1">
                    <input type="password" class="text_bg1" value="" name="password" id="password"/>
                </div>
            </li>
            
        </ul>
        
    </div>
    <!-- } 内容 -->
    
    <div class="bg_f pt20 pb10 pr">
    	<div class="bc btn2">
        	<!--<a href="javascript:jQuery('#loginform').submit()" class="text_bg tc cf">登录</a>-->
                <input type="submit" value="登录"  class="text_bg tc cf"/>
        </div>
        
        <!--<a href="javascript:;" class="f12 c8 unl pa forgot_pwd">忘记密码</a>-->
    </div>  
    
    <div class="bg_bottom"></div>
</div>
<!-- } wrapper -->
</form>

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
</body>
</html>