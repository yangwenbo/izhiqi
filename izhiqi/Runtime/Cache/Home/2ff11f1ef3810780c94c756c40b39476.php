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
        <em class="mc f14 ml20">个人信息</em>
    </div>
    <!-- } title -->
    <form id="register" action="<?php echo U(Login/addUserinfo);?>" method="POST" enctype="multipart/form-data">
    <!-- 内容 { -->
    <div class="wrap p30">
    	
        <ul class="reg_list">
        
            <li class="clearfix">
            	<div class="fl photo p5">
                	<img src="../images/hero-2.jpg" width="120" height="120" border="0" />
                </div>
                <div class="fl ml30">
                	<p class="f14 c8 pt40 pb20">照片</p>
                    <div class="btn1 pr">
                        <a href="javascript:;" class="text_bg tc cf">上传照片</a>
                	    <input type="file" name="avartar" alt="上传照片" class="pa" style="top:0;left:0;width:130px;height:45px;filter:alpha(opacity:0);opacity:0;cursor:pointer" onchange="$(this).parent().parent().parent().find('.more_info').html($(this).val());" />
                    </div>
                </div>
                <p class="fr more_info pt90">提示：上传文件</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='毕业院校') {this.value='';this.style.color='';}" value="毕业院校" name="schoolname" datatype="s4-50" nullmsg="请输入毕业学校"/>
                </div>
                <p class="fr more_info pt10">必填(毕业学校)</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='专业') {this.value='';this.style.color='';}" value="专业" name="major" datatype="s2-50" nullmsg="请输入专业">
                </div>
                <p class="fr more_info pt10">必填(专业)</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='入学日期') {this.value='';this.style.color='';}" value="入学日期" name="schoolstartdate" onClick="WdatePicker()" />
                </div>
                <p class="fr more_info">选填(入学日期)</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='地址（学校）') {this.value='';this.style.color='';}" value="地址（学校）" name="schooladdress"   />
                </div>
                <p class="fr more_info">选填(学校地址)</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg2">
                    <span class="text_bg2 cc" >性别</span>
                </div>
                <div class="fl ml20 input_bg2">
                    <div class="text_bg2 tc">
                    	<input type="radio" name="sex" value='1' datatype="*" sucmsg="通过验证"/> 男
                        <input type="radio" name="sex" value='2' datatype="*" sucmsg="通过验证"/> 女
                    </div>
                </div>
                <p class="fr more_info pt10">必填(性别)</p>
            </li>
            
            <li class="clearfix">
                <div class="fl input_bg1">
                    <input type="text" class="text_bg1 cc" onfocus="if (this.value=='年龄') {this.value='';this.style.color='';}" value="年龄" name="age" datatype="n1-2" nullmsg="请输入年龄"/>
                </div>
                <p class="fr more_info">必填(年龄)</p>
            </li>
            
        </ul>
        
    </div>
    <!-- } 内容 -->
    
    <div class="bg_f pt20 pb10">
    	<div class="bc btn2">
        	<a href="javascript:" id="btnRegister"class="text_bg tc cf">完成注册</a>
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

<script src="__JSURL__/My97DatePicker/WdatePicker.js"></script>
<script src="__JSURL__/Validform_v5.3.2.js" type="text/javascript" charset="UTF-8"></script>
<script>
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
});
</script>
</html>