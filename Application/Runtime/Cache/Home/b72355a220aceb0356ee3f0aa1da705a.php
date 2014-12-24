<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="/commission/Public/jquery-1.11.1.min.js"></script>
		<script src="/commission/Public/bootstrap/js/bootstrap.min.js"></script>
		<link href="/commission/Public/bootstrap/css/bootstrap.css" rel="stylesheet">
		<script type="text/javascript" src="/commission/Public/plugins/validate/jquery.validationEngine-en.js"></script>
		<script type="text/javascript" src="/commission/Public/plugins/validate/jquery.validationEngine.js"></script>
        <link rel="stylesheet" type="text/css" href="/commission/Public/plugins/validate/validationEngine.jquery.css">
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
			   	<li li_function="CaculateWage"><a href="#">薪资计算</a></li>
			   	<li li_function="BusinessPercent"><a href="#">业务提成比例</a></li>
			   	<li li_function="ProfitPercent"><a href="#">利润提成比例</a></li>
			   	<li li_function="EditData"><a href="#">修改数据</a></li>
			   	<!-- <li><a href="#">功能4</a></li>
			   	<li><a href="#">功能5</a></li> -->
			   	<li li_function="SystemConfig"><a href="#">系统配置</a></li>
			  </ul>
			    
			  <button id="btn_logout" class="btn btn-danger navbar-btn navbar-right">退出</button>
			  </div>
			</nav>
    	</div>
        <div class="container-fluid">
        	
	<div>
		<div style="position:fixed; top:60px;left:10px;width: 150px;z-index: 100;">
			<ul id="myTab" class="nav nav-pills nav-stacked" role="tablist">
			  <li role="presentation" class="active">
			  	<a href="#normal" id="normal-tab" role="tab" data-toggle="tab" aria-controls="normal" aria-expanded="true">普通利润提成比例</a>
			  </li>
			  <li role="presentation">
			  	<a href="#special" role="tab" id="sepcial-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">特殊利润提成比例</a>
			  </li>
			</ul>
		</div>
		<div id="myTabContent" class="tab-content row" style="padding-left:170px;">
			<div role="tabpanel" class="tab-pane fade in active col-xs-12" id="normal" aria-labelledby="normal-tab">
	       	 	<p>normal content</p>
			</div>
	      	<div role="tabpanel" class="tab-pane fade col-xs-12" id="special" aria-labelledby="special-tab">
				<div class="row">
					<div class="col-xs-12">
						<p>profile content</p>
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
		$("[li_function='caculate_wage']").click();
		$("ul.navbar-nav li").click(function(){
			$("ul.navbar-nav li").removeClass("active");
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
			$("[li_function='ProfitPercent']").addClass("active");
			$('#myTab a').click(function (e) {
			  e.preventDefault();
			  $(this).tab('show');
			});
		})
	</script>