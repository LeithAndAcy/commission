<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="/commission/Public/jquery-1.11.1.min.js"></script>
		<script src="/commission/Public/bootstrap/js/bootstrap.min.js"></script>
		<link href="/commission/Public/bootstrap/css/bootstrap.css" rel="stylesheet">
		
		<script type="text/javascript" src="/commission/Public/plugins/Validate/jquery.validationEngine-en.js"></script>
		<script type="text/javascript" src="/commission/Public/plugins/Validate/jquery.validationEngine.js"></script>
        <link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Validate/validationEngine.jquery.css">
         
        <link rel="stylesheet" type="text/css" href="/commission/Public/plugins/DataTables/jquery.dataTables.css">
		<script type="text/javascript" src="/commission/Public/plugins/DataTables/jquery.dataTables.js"></script>
      	
      	<style>
      		a{cursor: pointer}
      	</style>
        <title>Commission Project</title>
    </head>
    <body style="padding-top: 70px;min-width: 900px">
    	<div style="height: 20%">
    		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			  <div class="container-fluid">
			  <a class="navbar-brand" href="#">
			  	导航栏
			  </a>
			   <ul class="nav navbar-nav">
			   	<li li_function="SourceData"><a href="#">基本数据</a></li>
			   	<li li_function="CaculateWage"><a href="#">薪资计算</a></li>
			   	<li li_function="BusinessPercent"><a href="#">业务提成</a></li>
			   	
			   	<!-- <li li_function="EditData"><a href="#">修改数据</a></li> -->
			   	<li li_function="SearchData"><a href="#">搜索数据</a></li>
			   	<!-- <li li_function="Report"><a href="#">报表</a></li> -->
			   	<!-- <li><a href="#">功能5</a></li> -->
			   	<li li_function="SystemConfig"><a href="#">系统配置</a></li>
			  </ul>
			    
			  <button id="btn_logout" class="btn btn-danger navbar-btn navbar-right">退出</button>
			  </div>
			</nav>
    	</div>
        <div class="container-fluid">
        	<div>
        		<div style="position:fixed; top:60px;left:10px;width: 170px;z-index: 100;">
        			
        		</div>
        		<div id="container" class="row" style="padding-left:190px;padding-top: 5px">
        			
	<div>
		<div style="position:fixed; top:60px;left:10px;width: 150px;z-index: 100;">
			<ul id="myTab" class="nav nav-pills nav-stacked" role="tablist">
			  <li role="presentation" class="active">
			  	<a href="#changePWD" id="changePWD-tab" role="tab" data-toggle="tab" aria-controls="changePWD" aria-expanded="true">修改密码</a>
			  </li>
			  <li role="presentation">
			  	<a href="#addUser" role="tab" id="addUser-tab" data-toggle="tab" aria-controls="addUser" aria-expanded="false">添加用户</a>
			  </li>
			</ul>
		</div>
		
		<div id="myTabContent" class="tab-content row " style="padding-left:170px;padding-top: 50px">
			<div role="tabpanel" class="tab-pane fade in active col-xs-12" id="changePWD" aria-labelledby="changePWD-tab">
       	 		<form id="changePWD_form" class="form-horizontal" role="form" method="post" action="/commission/index.php/Home/SystemConfig/changePWD">
       	 			<div class="form-group col-xs-10">
       	 				<label class="col-sm-3 control-label">当前密码:</label>
       	 				 <div class="col-sm-5">
       	 					<input name="currentPWD" id="currentPWD" type="password" class="form-control validate[required,ajax[ajaxUserCurrentPWD]]" placeholder="请输入当前密码"/>
       	 				</div>
       	 			</div>
       	 			<div class="form-group col-xs-10">
       	 				<label class="col-sm-3 control-label">新密码:</label>
       	 				 <div class="col-sm-5">
       	 					<input name="newPWD" id="newPWD" type="password" class="form-control validate[required]" placeholder="请输入新密码"/>
       	 				</div>
       	 			</div>
       	 			<div class="form-group col-xs-10">
       	 				<label class="col-sm-3 control-label">重复新密码:</label>
       	 				 <div class="col-sm-5">
       	 					<input name="newerPWD" id="newerPWD" type="password" class="form-control validate[required,equals[newPWD]]" placeholder="请重复输入新密码"/>
       	 				</div>
       	 			</div>
       	 			<div class="form-group col-xs-10">
       	 				<input type="reset" class="btn btn-info col-sm-offset-4" value="重置" />
       	 				<input type="submit" class="btn btn-primary col-sm-offset-1" value="提交" />
       	 			</div>
       	 		</form>
      		</div>
      		<div role="tabpanel" class="tab-pane fade col-xs-12" id="addUser" aria-labelledby="profile-tab">
				<form id="addUser_form" class="form-horizontal" role="form" method="post" action="/commission/index.php/Home/SystemConfig/addUser">
       	 			<div class="form-group col-xs-10">
       	 				<label class="col-sm-3 control-label">用户名称:</label>
       	 				 <div class="col-sm-5">
       	 					<input name="newUserName" id="newUserName" type="text" class="form-control validate[required,ajax[ajaxNameCall]]" placeholder="请输入用户账号"/>
       	 				</div>
       	 			</div>
       	 			<div class="form-group col-xs-10">
       	 				<label class="col-sm-3 control-label">用户密码:</label>
       	 				 <div class="col-sm-5">
       	 					<input name="newUserPWD" id="newUserPWD" type="password" class="form-control validate[required]" placeholder="请输入用户密码"/>
       	 				</div>
       	 			</div>
       	 			<div class="form-group col-xs-10">
       	 				<input type="reset" class="btn btn-info col-sm-offset-4" value="重置" />
       	 				<input type="submit" class="btn btn-primary col-sm-offset-1" value="提交" />
       	 			</div>
       	 		</form>
	      	</div>
		</div>
	</div>
	
	


        		</div>
        	</div>
        	
        	
        </div>
    </body>
</html>
<script>
	$(function(){
		$("ul.navbar-nav li").click(function(){
			var li_function = $(this).attr('li_function');
			window.location.href = "/commission/index.php/Home/"+li_function+"/load"+li_function+"Page";
		});
		$("#btn_logout").click(function(){
			window.location.href = "/commission/index.php/Home/Index/index";
			$.post("/commission/index.php/Home/Index/logout",{},function(){
				
			});
		});
	});
</script>

	<script>
		$(function(){
			$("[li_function='SystemConfig']").addClass("active");
			$("#changePWD_form").validationEngine('attach');
			$("#addUser_form").validationEngine('attach');
			//工作区间切换
			$('#myTab a').click(function (e) {
			  e.preventDefault();
			  $(this).tab('show');
			});
			
		})
	</script>