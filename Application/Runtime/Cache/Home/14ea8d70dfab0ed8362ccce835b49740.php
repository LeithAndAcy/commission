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
			<table id="normalBusinessTable" class="display" width="120%" cellspacing="0">
				<thead>
					<tr>
						<th>合同号</th>
						<th>存货类别</th>
						<th>存货编码</th>
						<th>规格</th>
						<th>型号</th>
						<th>颜色</th>
						<th>低价</th>
						<th>最终实际低价</th>
						<th>发货数量（米数）</th>
						<th>单价</th>
						<th>销售金额</th>
						<th>基本业绩提成比例</th>
						<th>回款达标业绩提成</th>
						<th>基本利润提成</th>
						<th>回款未达标利润提成</th>
						<th>提成合计</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>业务员1</td>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>业务员1</td>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>1000</td>
						<td>2000</td>
					</tr>
					<tr>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>业务员1</td>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>业务员1</td>
						<td>合同号1</td>
						<td>1000</td>
						<td>2000</td>
						<td>1000</td>
						<td>2000</td>
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