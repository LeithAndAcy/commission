<?php if (!defined('THINK_PATH')) exit();?><html>
	<head>
			<script src="/commission/Public/jquery-1.11.1.min.js"></script>
<script src="/commission/Public/bootstrap/js/bootstrap.min.js"></script>
<link href="/commission/Public/bootstrap/css/bootstrap.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/DataTables/jquery.dataTables.css">
<script type="text/javascript" src="/commission/Public/plugins/DataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/commission/Public/plugins/Validate/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/commission/Public/plugins/Validate/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Validate/validationEngine.jquery.css">

<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Select2/select2.css">
<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/Select2/select2.bootstrap.css">
<script type="text/javascript" src="/commission/Public/plugins/Select2/select2.js"></script>
	</head>
	<body>
		<div class="col-xs-12">
			<div>
				<button class="btn btn-info" data-toggle="modal" data-target="#addNew">
					新增
				</button>
			</div>
			<table id="allSalesmenTable" class="display" width="100%" cellspacing="0" style="margin-top: 20px">
				<thead>
					<tr>
						<th>人员编码</th>
						<th>姓名</th>
						<th>人员状态</th>
						<th>上海基本工资</th>
						<th>昆山基本工资</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($all_salesmen)): $i = 0; $__LIST__ = $all_salesmen;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td id="<?php echo ($vo["id"]); ?>_salesman_id"><?php echo ($vo["salesman_id"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_name"><?php echo ($vo["name"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_status"><?php echo ($vo["status"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_shanghai_salary"><?php echo ($vo["shanghai_salary"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_kunshan_salary"><?php echo ($vo["kunshan_salary"]); ?></td>
							<td sales_man_id="<?php echo ($vo["id"]); ?>"><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Edit"  alt="编辑" src="/commission/Public/img/edit.png" data-toggle="modal" data-target="#edit"> </span>
								<span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Delete" alt="删除" src="/commission/Public/img/delete.png"> </span>
							</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>
		</div>
		<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">修改</h4>
					</div>
					<form id="edit_form" class="form-horizontal" role="form" action="editSalesman" method="post">
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">人员编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_salesman_id" placeholder="人员编码" disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">业务员姓名</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_salesman_name"  disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">人员状态</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_status" disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">上海基本工资</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" name="edit_shanghai_salary" class="form-control validate[required,[custom[number],min[0]" id="edit_shanghai_salary">
									<span class="input-group-addon">元</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">昆山基本工资</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" name="edit_kunshan_salary" class="form-control validate[required,[custom[number],min[0]]" id="edit_kunshan_salary">
									<span class="input-group-addon">元</span>
								</div>
							</div>
							<input type="text" id="edit_id" name="edit_id" class="hidden" />
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="取消"/>
							<input type="submit" class="btn btn-primary" value="确定"/>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">新增</h4>
					</div>
					<form id="add_new_form"  class="form-horizontal" role="form" method="post" action="addSalesman">
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">业务员姓名</label>
								<div class="col-sm-6">
									<input type="text" class="form-control validate[required]"   name="add_new_name"  id="add_new_name" placeholder="请输入姓名">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">人员编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control validate[required,ajax[ajaxSalesmanIdCall]]" name="add_new_salesman_id"  id="add_new_salesman_id" placeholder="人员编码">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">状态</label>
								<div class="col-sm-6">
									<span>
										<input type="radio" id="radio_shanghai" class="" name="add_new_status" value="1">
										<label for="radio_shanghai" style="margin-left: 15px">上海</label>
									</span>
									<span style="margin-left: 30px">
										<input type="radio" id="radio_kunshan" name="add_new_status" value="0">
										<label for="radio_kunshan" style="margin-left: 15px">昆山</label>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-4 control-label">上海基本工资</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" class="form-control validate[required,[custom[number]]" name="add_new_shanghai_salary" placeholder="在此输入" >
									<span class="input-group-addon">元</span>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-4 control-label">昆山基本工资</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" class="form-control validate[required,[custom[number]]" name="add_new_kunshan_salary" placeholder="在此输入" >
									<span class="input-group-addon">元</span>
								</div>
							</div>

						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="取消"/>
							<input type="submit" class="btn btn-primary" value="确定"/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>

<script>
	$(function() {
		$("#allSalesmenTable").dataTable();
		
		$("#add_new_form").validationEngine('attach');
		$("#edit_form").validationEngine('attach');
		$("#salesman_list").change(function() {
			var temp_salesman_id = $("#salesman_list").find("option:selected").attr('salesman_id');
			$("#add_new_salesman_id").val(temp_salesman_id);
		});
		$("#allSalesmenTable tbody").on("click","tr td span img:even",function() {
			var edit_id = $(this).parent().parent().attr('sales_man_id');
			var edit_status = $("#"+edit_id+"_status").text();
			var edit_shanghai_salary = $("#"+edit_id+"_shanghai_salary").text();
			var edit_kunshan_salary = $("#"+edit_id+"_kunshan_salary").text();
			var edit_salesman_id = $("#"+edit_id+"_salesman_id").text();
			var edit_name = $("#"+edit_id+"_name").text();
			$("#edit_id").val(edit_id);
			$("#edit_salesman_id").val(edit_salesman_id);
			$("#edit_salesman_name").val(edit_name);
			$("#edit_status").val(edit_status);
			$("#edit_shanghai_salary").val(edit_shanghai_salary);
			$("#edit_kunshan_salary").val(edit_kunshan_salary);
		});
		
		$("#allSalesmenTable tbody").on("click","tr td span img:odd",function() {
			var temp = confirm("确认删除该条信息吗？");
			if(temp){
				var delete_id = $(this).parent().parent().attr('sales_man_id');
				$.post("/commission/index.php/Home/SourceData/deleteSalesman", {
					"delete_id" : delete_id,
				}, function(data) {
					window.location.reload();
				});
			}
		});
	}); 
</script>