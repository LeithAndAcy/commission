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
			<table id="allSpecialBusinessRatioTable" class="display" width="100%" cellspacing="0" style="margin-top: 20px">
				<thead>
					<tr>
						<th>人员编码</th>
						<th>姓名</th>
						<th>回款下限</th>
						<th>回款上限</th>
						<th>回款达标业绩提成比例</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($all_special_business_ratio)): $i = 0; $__LIST__ = $all_special_business_ratio;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td id="<?php echo ($vo["id"]); ?>_salesman_id"><?php echo ($vo["salesman_id"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_name"><?php echo ($vo["name"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_low_limit"><?php echo ($vo["low_limit"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_high_limit"><?php echo ($vo["high_limit"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_ratio"><?php echo ($vo["ratio"]); ?>%</td>
							<td special_business_ratio_id="<?php echo ($vo["id"]); ?>"><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Edit"  alt="编辑" src="/commission/Public/img/edit.png" data-toggle="modal" data-target="#edit"> </span><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Delete" alt="删除" src="/commission/Public/img/delete.png"> </span></td>
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
					<form id="edit_form" class="form-horizontal" role="form" action="editSpecialBusinessRatio" method="post">
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">人员编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_salesman_id" disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">姓名</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_salesman_name" disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">回款区间</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_collection_range" disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-4 control-label">基本提成比例</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" name="edit_ratio" class="form-control validate[required,[custom[number]]" id="edit_ratio">
									<span class="input-group-addon">%</span>
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
						<h4 class="modal-title" id="myModalLabel">增加</h4>
					</div>
					<form id="add_new_form"  class="form-horizontal" role="form" method="post" action="addNewSpecialBusinessRatio">
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">业务员姓名</label>
								<div class="col-sm-6">
									<select class="form-control" id="salesman_list" name="add_new_salesman_name">
										<option></option>
										<optgroup label="上海员工">
											<?php if(is_array($shanghai_salesmen)): $i = 0; $__LIST__ = $shanghai_salesmen;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option salesman_id="<?php echo ($vo["salesman_id"]); ?>" value="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
										</optgroup>

										<optgroup label="昆山员工">
											<?php if(is_array($kunshan_salesmen)): $i = 0; $__LIST__ = $kunshan_salesmen;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option salesman_id="<?php echo ($vo["salesman_id"]); ?>" value="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">人员编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control validate[required]"  name="add_new_salesman_id" id="add_new_salesman_id" placeholder="人员编码">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">回款区间下限</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" class="form-control validate[required,[custom[number]]" name="add_new_low_limit" placeholder="回款区间下限">
									<span class="input-group-addon">元</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">回款区间上限</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" class="form-control validate[required,[custom[number]]" name="add_new_high_limit" placeholder="回款区间下限">
									<span class="input-group-addon">元</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">基本提成比例</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" class="form-control validate[required,[custom[number]]" name="add_new_ratio" placeholder="在此输入" >
									<span class="input-group-addon">%</span>
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
		$("#allSpecialBusinessRatioTable").dataTable();
		$("#salesman_list").select2({
			placeholder : "请选择一名员工"
		});
		$("#add_new_form").validationEngine('attach');
		$("#edit_form").validationEngine('attach');
		$("#salesman_list").change(function() {
			var temp_salesman_id = $("#salesman_list").find("option:selected").attr('salesman_id');
			$("#add_new_salesman_id").val(temp_salesman_id);
		});
		$("#allSpecialBusinessRatioTable tbody").on("click", "tr td span img:even", function() {
			var edit_id = $(this).parent().parent().attr('special_business_ratio_id');
			var edit_ratio = $("#" + edit_id + "_ratio").text();
			edit_ratio = edit_ratio.slice(0, -1);
			var edit_low_limit = $("#" + edit_id + "_low_limit").text();
			var edit_high_limit = $("#" + edit_id + "_high_limit").text();
			var edit_name = $("#" + edit_id + "_name").text();
			var edit_salesman_id = $("#" + edit_id + "_salesman_id").text();
			var edit_collection_range = edit_low_limit + "元       至     " + edit_high_limit + "元";
			$("#edit_id").val(edit_id);
			$("#edit_salesman_id").val(edit_salesman_id);
			$("#edit_salesman_name").val(edit_name);
			$("#edit_collection_range").val(edit_collection_range);
			$("#edit_ratio").val(edit_ratio);
		});

		$("#allSpecialBusinessRatioTable tbody").on("click", "tr td span img:odd", function() {
			var temp = confirm("确认删除该条信息吗？");
			if (temp) {
				var delete_id = $(this).parent().parent().attr('special_business_ratio_id');
				$.post("/commission/index.php/Home/SourceData/deleteSpecialBusinessRatioById", {
					"delete_id" : delete_id,
				}, function(data) {
					window.location.reload();
				});
			}
		});
	}); 
</script>