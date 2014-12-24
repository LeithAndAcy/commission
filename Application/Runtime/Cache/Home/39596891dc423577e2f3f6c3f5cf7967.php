<?php if (!defined('THINK_PATH')) exit();?><html>
	<head>
		<script src="/commission/Public/jquery-1.11.1.min.js"></script>
		<script src="/commission/Public/bootstrap/js/bootstrap.min.js"></script>
		<link href="/commission/Public/bootstrap/css/bootstrap.css" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/DataTables/jquery.dataTables.css">
		<script type="text/javascript" src="/commission/Public/plugins/DataTables/jquery.dataTables.js"></script>
	</head>
	<body>
		<div class="col-xs-12">
			<table id="normalBusinessTable" class="display" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>合同号</th>
						<th>业务员姓名</th>
						<th>基本业绩提成</th>
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