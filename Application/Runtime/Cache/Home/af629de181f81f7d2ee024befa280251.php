<?php if (!defined('THINK_PATH')) exit();?><html>
	<head>
		<script src="/commission/Public/jquery-1.11.1.min.js"></script>
<script src="/commission/Public/bootstrap/js/bootstrap.min.js"></script>
<link href="/commission/Public/bootstrap/css/bootstrap.css" rel="stylesheet">

<script type="text/javascript" src="/commission/Public/plugins/DataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/commission/Public/plugins/DataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="/commission/Public/plugins/DataTables/dataTable.fixedColumns.js"></script>
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
			<div>
				<form class="form-inline" enctype="multipart/form-data" method="post" action="/commission/index.php/Home/Excel/importNormalBusinessRatioExcel">
					<div class="form-group">
						<a>
						<li data-toggle="modal" data-target="#addNew" class="btn btn-info">
							新增
						</li></a>
					</div>
					<div class="form-group">
						<input type="file" name="excel_file" class="btn btn-info" value="导入Excel文件" />
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" value="提交" />
					</div>
				</form>
				<form action="/commission/index.php/Home/Excel/exportNormalBusinessRatioExcelFile" method="post">
					<div class="form-group">
						<input type="submit" class="btn btn-info" value="导出" />
					</div>
				</form>

			</div>
			<table id="allNormalBusinessRatioTable" class="table table-striped table-bordered table-hover datatable" width="100%" cellspacing="0" style="margin-top: 20px">
				<thead>
					<tr>
						<th>业务员编码</th>
						<th>业务员姓名</th>
						<th>存货编码</th>
						<!-- <th>存货名称</th>
						<th>存货类别</th>
						<th>规格型号</th> -->
						<th>基本业绩提成比例</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($all_normal_business_ratio)): $i = 0; $__LIST__ = $all_normal_business_ratio;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td id="<?php echo ($vo["id"]); ?>_salesman_id"><?php echo ($vo["salesman_id"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_name"><?php echo ($vo["salesman_name"]); ?></td>
							<td id="<?php echo ($vo["id"]); ?>_number"><?php echo ($vo["inventory_id"]); ?></td>
							<!-- <td><?php echo ($vo["inventory_name"]); ?></td>
							<td><?php echo ($vo["classification_id"]); ?></td>
							<td><?php echo ($vo["specification"]); ?></td> -->
							<td id="<?php echo ($vo["id"]); ?>_ratio"><?php echo ($vo["ratio"]); ?>%</td>
							<td normal_business_ratio_id="<?php echo ($vo["id"]); ?>"><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Edit"  alt="编辑" src="/commission/Public/img/edit.png" data-toggle="modal" data-target="#edit"> </span><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Delete" alt="删除" src="/commission/Public/img/delete.png"> </span></td>
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
					<form id="edit_form" class="form-horizontal" role="form" action="editNormalBusinessRatio" method="post">
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
								<label for="inputEmail3" class="col-sm-4 control-label">货品编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="edit_number" disabled="disabled">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-4 control-label">基本提成比例</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px">
									<input type="text" name="edit_ratio" class="form-control validate[[custom[number]]" id="edit_ratio">
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
						<h4 class="modal-title" id="myModalLabel">新增</h4>
					</div>
					<form id="add_new_form"  class="form-horizontal" role="form" method="post" action="addNewNormalBusinessRatio">
						<div class="modal-body">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-4 control-label">业务员姓名</label>
								<div class="col-sm-6">
									<select class="form-control" id="salesman_list" name="add_new_salesman_name">
										<option></option>
										<optgroup label="员工">
											<?php if(is_array($all_salesman)): $i = 0; $__LIST__ = $all_salesman;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option salesman_id="<?php echo ($vo["salesman_id"]); ?>" value="<?php echo ($vo["salesman_name"]); ?>"><?php echo ($vo["salesman_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">人员编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control validate[required]" name="add_new_salesman_id"  id="add_new_salesman_id" readonly="readonly">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">货存编码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control validate[required]" name="add_new_inventory_id">
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
		$("#allNormalBusinessRatioTable").dataTable();
		$("#salesman_list").select2({
			placeholder : "请选择一名员工"
		});
		$("#add_new_form").validationEngine('attach');
		$("#edit_form").validationEngine('attach');
		$("#salesman_list").change(function() {
			var temp_salesman_id = $("#salesman_list").find("option:selected").attr('salesman_id');
			$("#add_new_salesman_id").val(temp_salesman_id);
		});
		$("#allNormalBusinessRatioTable tbody").on("click", "tr td span img:even", function() {
			var edit_id = $(this).parent().parent().attr('normal_business_ratio_id');
			var edit_ratio = $("#" + edit_id + "_ratio").text();
			edit_ratio = edit_ratio.slice(0, -1);
			var edit_specification = $("#" + edit_id + "_specification").text();
			var edit_number = $("#" + edit_id + "_number").text();
			var edit_name = $("#" + edit_id + "_name").text();
			var edit_salesman_id = $("#" + edit_id + "_salesman_id").text();
			$("#edit_id").val(edit_id);
			$("#edit_salesman_id").val(edit_salesman_id);
			$("#edit_salesman_name").val(edit_name);
			$("#edit_number").val(edit_number);
			$("#edit_specification").val(edit_specification);
			$("#edit_ratio").val(edit_ratio);
		});

		$("#allNormalBusinessRatioTable tbody").on("click", "tr td span img:odd", function() {
			var temp = confirm("确认删除该条信息吗？");
			if (temp) {
				var delete_id = $(this).parent().parent().attr('normal_business_ratio_id');
				$.post("/commission/index.php/Home/SourceData/deleteNormalBusinessRatioById", {
					"delete_id" : delete_id,
				}, function(data) {
					window.location.href="/commission/index.php/Home/SourceData/loadNormalBusinessPage";
				});
			}
		});
	}); 
</script>