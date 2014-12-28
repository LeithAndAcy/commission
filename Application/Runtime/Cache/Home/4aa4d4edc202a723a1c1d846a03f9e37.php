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
        		<div style="position:fixed; top:60px;left:10px;width: 200px;z-index: 100;">
        			
	<ul id="myTab" class="nav nav-pills nav-stacked" role="tablist">
		<li role="presentation">
			<a href="/commission/index.php/Home/BusinessPercent/CaculateNormalBusiness" target="workflow">基本业绩提成</a>
		</li>
		<li role="presentation">
			<a href="/commission/index.php/Home/BusinessPercent/CaculateSpecialBusiness" target="workflow">达标业绩提成</a>
		</li>
		<li role="presentation">
			<a href="/commission/index.php/Home/BusinessPercent/CaculateNormalProfit" target="workflow">基本利润提成</a>
		</li>
		<li role="presentation">
			<a href="/commission/index.php/Home/BusinessPercent/CaculateSpecialProfit" target="workflow">未达标利润提成</a>
		</li>
		<li role="presentation">
			<a target="workflow">可结算合同</a>
		</li>
		<li role="presentation">
			<a href="" target="workflow">已结算提成</a>
		</li>
		<li role="presentation">
			<a href="" target="workflow">业务提成表</a>
		</li>
	</ul>

        		</div>
        		<div id="container" class="row" style="padding-left:220px;padding-top: 5px">
        			
	<iframe name="workflow" frameborder="false" width="99%" allowtransparency="true" height="740px"></iframe>

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
		$(function() {
			$("[li_function='BusinessPercent']").addClass("active");
			$('#myTab a').click(function() {
				$('#myTab a').parent().removeClass("active");
				$(this).parent().addClass("active");
			});
		});
	</script>