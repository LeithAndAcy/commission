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
		<script src="/commission/Public/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="/commission/Public/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" />
		<style>
			.datetimepicker, .glyphicon {
				cursor: pointer;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<button class="btn btn-info" data-toggle="modal" data-target="#searchModel">
						复杂搜索
					</button>
					<button class="btn btn-success" data-toggle="modal" data-target="#loadData">
						按日期载入数据
					</button>
					<button class="btn btn-primary" id="get_ratio">
						取数
					</button>
					<button class="btn btn-warning" id="update_quantity">
						更新发货数量
					</button>
					<span>
						合同总数：<?php echo ($count_settlement_contact); ?>
					</span>
					<span>
						合同明细总数：<?php echo ($count_settlement_contact_detail); ?>
					</span>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<table id="mainSourceTable" class="table table-striped table-bordered table-hover datatable" width="240%" cellspacing="0" style="margin-top: 20px;overflow-x: auto">
							<thead>
								<tr>
									<th>合同号</th>
									<th>销售订单号</th>
									<th>客户编码</th>
									<th>客户名称</th>
									<th>业务员编码</th>
									<th>业务员姓名</th>
									<th>存货编码</th>
									<th>存货类别编码</th>
									<th>存货名称</th>
									<th>规格型号</th>
									<th>颜色</th>
									<th>销售单价</th>
									<th>总经理底价上浮比例</th>
									<th>总经理上浮底价</th>
									<th>技术底价上浮比例</th>
									<th>技术上浮底价</th>
									<th>底价（元）</th>
									<th>最终底价（元）</th>
									
									<th>月结底价上浮比例</th>
									<th>月结上浮底价</th>
									
									<th>定制费</th>
									<th>定制费上浮底价</th>
									
									<th>短米上浮的底价（元）</th>
									<th>销售数量（米数）</th>
									<th>发货数量（米数）</th>
									<th>发货金额(元)</th>
									<th>销售费用单价(元)</th>
									<th>销售费用比例</th>
									<th>基本业绩提成比例</th>
									<th>基本利润提成比例</th>
									<th>达标利润提成比例折扣</th>
									<th>业务提成调整比例</th>
									<th>利润提成调整比例</th>
									<th>底价调整金额（元）</th>
									<th>调整比例修改人员</th>
									<th>手动结算</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($settlement_contact_detail)): $i = 0; $__LIST__ = $settlement_contact_detail;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
										<td><?php echo ($vo["contact_id"]); ?></td>
										<td><?php echo ($vo["cSOCode"]); ?></td>
										<td><?php echo ($vo["customer_id"]); ?></td>
										<td><?php echo ($vo["customer_name"]); ?></td>
										<td><?php echo ($vo["salesman_id"]); ?></td>
										<td><?php echo ($vo["salesman_name"]); ?></td>
										<th><?php echo ($vo["inventory_id"]); ?></th>
										<td><?php echo ($vo["classification_id"]); ?></td>
										<td><?php echo ($vo["inventory_name"]); ?></td>
										<td><?php echo ($vo["specification"]); ?></td>
										<td><?php echo ($vo["colour"]); ?></td>
										<td><?php echo ($vo["sale_price"]); ?></td>
										<td><?php echo ($vo["gm_ratio"]); ?>%</td>
										<td><?php echo ($vo["gm_price"]); ?></td>
										<td><?php echo ($vo["skill_ratio"]); ?>%</td>
										<td><?php echo ($vo["skill_price"]); ?></td>
										<td><?php echo ($vo["cost_price"]); ?></td>
										<td><?php echo ($vo["end_cost_price"]); ?></td>
										
										<td><?php echo ($vo["special_approve_float_price_ratio"]); ?>%</td>
										<td><?php echo ($vo["special_approve_float_price"]); ?></td>
										
										<td><?php echo ($vo["custom_fee"]); ?></td>
										<td><?php echo ($vo["custom_fee_float_price"]); ?></td>
										
										<td><?php echo ($vo["float_price"]); ?></td>
										<td><?php echo ($vo["sale_quantity"]); ?></td>
										<td><?php echo ($vo["delivery_quantity"]); ?></td>
										<td><?php echo ($vo["delivery_money"]); ?></td>
										<td><?php echo ($vo["sale_expense"]); ?></td>
										<td><?php echo ($vo["sale_expense_ratio"]); ?>%</td>
										<td><?php echo ($vo["normal_business_ratio"]); ?>%</td>
										<td><?php echo ($vo["normal_profit_ratio"]); ?>%</td>
										<td><?php echo ($vo["normal_profit_discount_ratio"]); ?>%</td>
										<td id="<?php echo ($vo["contact_id"]); ?>_<?php echo ($vo["inventory_id"]); ?>_business_adjust"><?php echo ($vo["business_adjust"]); ?>%</td>
										<td id="<?php echo ($vo["contact_id"]); ?>_<?php echo ($vo["inventory_id"]); ?>_profit_adjust"><?php echo ($vo["profit_adjust"]); ?>%</td>
										<td id="<?php echo ($vo["contact_id"]); ?>_<?php echo ($vo["inventory_id"]); ?>_cost_price_adjust"><?php echo ($vo["cost_price_adjust"]); ?></td>
										<td><?php echo ($vo["operator"]); ?></td>
										<td contact_id="<?php echo ($vo["contact_id"]); ?>" inventory_id="<?php echo ($vo["inventory_id"]); ?>"><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Edit"  alt="编辑" src="/commission/Public/img/edit.png" data-toggle="modal" data-target="#editDetail"> </span><span style="margin-left: 10px;margin-right: 10px;cursor: pointer;"> <img title="Delete" alt="删除" src="/commission/Public/img/delete.png"> </span></td>
										<td>
										<button class="btn btn-primary" btn_type ="btn_manual" contact_id = "<?php echo ($vo["contact_id"]); ?>">
											申请
										</button></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div>
					<?php echo ($page); ?>
				</div>
			</div>
			<div class="modal fade" id="loadData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">载入数据</h4>
						</div>
						<form class="form-horizontal" id="load_data_form" role="form" method="post" action="loadData">
							<div class="modal-body row">
								<div class="col-sm-8">
									<div class="form-group">
										<label  class="col-sm-4 control-label">选择月份</label>
										<div class="col-sm-6 input-group date form_datetime" data-date-format="yyyy-mm">
											<input type="text" class="form-control" name="contact_date" id="contact_date" readonly="" >
											<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div>
									<!-- <div class="form-group">
										<label class="col-sm-4 control-label">结束时间</label>
										<div class="col-sm-6 input-group date form_datetime" data-date-format="yyyy-mm-dd">
											<input type="text" class="form-control" name="end_date" id="end_date" readonly="">
											<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
										</div>
									</div> -->
								</div>
								<div class="col-sm-offset-8">
									<label>载入数据历史记录：</label>
									<?php if(is_array($load_history)): $i = 0; $__LIST__ = $load_history;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p>
											<?php echo ($vo["begin_date"]); ?>---<?php echo ($vo["end_date"]); ?>
										</p><?php endforeach; endif; else: echo "" ;endif; ?>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss = "modal" value="取消" />
								<input type="submit" class="btn btn-primary" value="确定"/>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade" id="searchModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">复杂搜索</h4>
						</div>
						<form class="form-horizontal" role="form" method="post" action="/commission/index.php/Home/SourceData/complicateSearch" >
							<div class="modal-body">
								<div class="modal-body">
									<div class="form-group">
										<label class="col-sm-4 control-label">合同号</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_contact_id">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">销售订单号</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_cSOCode">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">业务员编码</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_salesman_id">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">业务员姓名</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_salesman_name">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">客户编码</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_customer_id">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">客户姓名</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_customer_name">
										</div>
									</div>
									<div class="form-group">
										<label  class="col-sm-4 control-label">存货编码</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_inventory_id" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">存货分类编码</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_classification_id">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">规格型号</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_specification">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label">颜色</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="search_colour">
										</div>
									</div>
									<div class="hidden">
										<input type="text" name="search_type" value="settlement" />
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
		</div>
		</div>
		<div class="modal fade" id="editDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">修改</h4>
					</div>
					<form class="form-horizontal" role="form" id="edit_form" action="ratioAdjust" method="post">
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">业务提成调整比例</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px" >
									<input type="text" name="edit_business_adjust" id="edit_business_adjust" class="form-control validate[required,[custom[number]]">
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">利润提成调整比例</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px" >
									<input type="text" name="edit_profit_adjust" id="edit_profit_adjust" class="form-control validate[required,[custom[number]]">
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">调整底价金额</label>
								<div class="col-sm-6 input-group" style="padding-left: 15px;padding-right: 14px" >
									<input type="text" name="edit_cost_price_adjust" id="edit_cost_price_adjust" class="form-control validate[required,[custom[number]]">
									<span class="input-group-addon">元</span>
								</div>
							</div>
							<div class="hidden">
								<input type="text" name="edit_contact_id"  id="edit_contact_id" value=""/>
								<input type="text" name="edit_inventory_id" id="edit_inventory_id" value=""/>
								;
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
		var table = $('#mainSourceTable').DataTable( {
			scrollY:        "500px",
			scrollX:        true,
			scrollCollapse: true,
			paging:         true,
			searching:      false,
		});
		 new $.fn.dataTable.FixedColumns(table, {
			leftColumns: 8
			// rightColumns: 1
		});
		$("#load_data_form").validationEngine('attach');
		$("#edit_form").validationEngine('attach');
		$(".form_datetime").datetimepicker({
			autoclose : true,
			todayBtn : true,
			minView : 2,
			todayBtn : 1,
			todayHighlight : 1,
			pickerPosition : 'bottom-right',
			language : 'cn',
		});
		$(".glyphicon-remove").click(function() {
			$(this).parent().prev().val("");
		});
		$("#get_ratio").click(function() {
			$('#get_ratio').attr('disabled',"true");
			$.post("/commission/index.php/Home/SourceData/getSettlementRatio", {}, function(data) {
				window.location.href = "/commission/index.php/Home/SourceData/loadSettleSummaryPage";
			});
		});
		$("#update_quantity").click(function(){
			$('#update_quantity').attr('disabled',"true");
			var month = prompt("请输入月份2017-01","");
			$.post("/commission/index.php/Home/SourceData/updateDeliveryQuantity", {
				"month":month
			}, function(data) {
				window.location.href = "/commission/index.php/Home/SourceData/loadSettleSummaryPage";
			});
		});
		$("#mainSourceTable tbody").on("click", "tr td span img:even", function() {
			var contact_id = $(this).parent().parent().attr('contact_id');
			var inventory_id = $(this).parent().parent().attr('inventory_id');
			var business_adjust = $("#" + contact_id + "_" + inventory_id + "_business_adjust").text();
			var profit_adjust = $("#" + contact_id + "_" + inventory_id + "_profit_adjust").text();
			var cost_price_adjust = $("#" + contact_id + "_" + inventory_id + "_cost_price_adjust").text();

			business_adjust = business_adjust.slice(0, -1);
			profit_adjust = profit_adjust.slice(0, -1);

			$("#edit_business_adjust").val(business_adjust);
			$("#edit_profit_adjust").val(profit_adjust);
			$("#edit_cost_price_adjust").val(cost_price_adjust);

			$("#edit_contact_id").val(contact_id);
			$("#edit_inventory_id").val(inventory_id);
		});
		$("#mainSourceTable tbody").on("click","tr td span img:odd",function() {
			var temp = confirm("确认删除该条合同吗？");
			if(temp){
				var contact_id = $(this).parent().parent().attr('contact_id');
				var inventory_id = $(this).parent().parent().attr('inventory_id');
				$.post("/commission/index.php/Home/SourceData/deleteContact", {
					"contact_id" : contact_id,
					"inventory_id" : inventory_id,
				}, function(data) {
					window.location.href="/commission/index.php/Home/SourceData/loadSettleSummaryPage";
				});
			}
		});
		$("#mainSourceTable tbody").on("click", "tr td [btn_type='btn_manual']", function() {
			var temp = confirm("是否申请结算");
			if (temp) {
				var contact_id = $(this).attr("contact_id");
				$.post("/commission/index.php/Home/SourceData/setManualContact", {
					"contact_id" : contact_id,
				}, function() {
					window.location.href = "/commission/index.php/Home/SourceData/loadSettleSummaryPage";
				});
			}
		});
	}); 
</script>
</block>