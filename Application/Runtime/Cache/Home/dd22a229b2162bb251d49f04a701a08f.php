<?php if (!defined('THINK_PATH')) exit();?><html>
	<head>
		<script src="/commission/Public/jquery-1.11.1.min.js"></script>
<script src="/commission/Public/bootstrap/js/bootstrap.min.js"></script>
<link href="/commission/Public/bootstrap/css/bootstrap.css" rel="stylesheet">

<script type="text/javascript" src="/commission/Public/plugins/DataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/commission/Public/plugins/DataTables/dataTables.bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/DataTables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/DataTables/bootstrap-responsiv.css">

<script type="text/javascript" src="/commission/Public/plugins/Validate/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/commission/Public/plugins/Validate/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Validate/validationEngine.jquery.css">

<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Select2/select2.css">
<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Select2/select2.bootstrap.css">
<script type="text/javascript" src="/commission/Public/plugins/Select2/select2.js"></script>
<style>
	.datatable {
		table-layout: fixed;
		word-break: break-all;
		font-size: 13px;
	}
	.datatable  th {
		text-align: center;
	}
	.datatable  td {
		text-align: center;
	}
	.dataTables_wrapper{
		margin-top:15px;
	}
	</style>
<script>
	$(function(){
		$(".datatable tbody").on("dblclick","tr",function() {
			$(this).children("td:last()").children("span:eq(0)").children("img").click();
		});
	})
</script>
	</head>
	<body>
		<div class="col-xs-12">
			<table id="normalBusinessTable" class="table table-striped table-bordered table-hover datatable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>合同号</th>
						<th>业务员姓名</th>
						<th>未达标利润提成</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>合同号1</td>
						<td>业务员1</td>
						<td>5000</td>

					</tr>
					<tr>
						<td>合同号2</td>
						<td>业务员1</td>
						<td>10000</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>

<script>
	$(function() {
		$("#normalBusinessTable").dataTable();
	}); 
</script>