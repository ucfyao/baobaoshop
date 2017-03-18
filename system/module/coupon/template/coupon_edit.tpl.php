<?php include $this->admin_tpl('header', 'admin'); ?>
<script type="text/javascript" src="./statics/js/template.js" ></script>
		<div class="fixed-nav layout">
			<ul>
				<li class="first">优惠券活动添加<a id="addHome" title="添加到首页快捷菜单">[+]</a></li>
				<li class="spacer-gray"></li>
				<li><a class="current" href="javascript:;"></a></li>
			</ul>
			<div class="hr-gray"></div>
		</div>
		<div class="content padding-big have-fixed-nav">
			<form action="<?php echo url('edit')?>" method="post" name="coupon_edit">
			<input type="hidden" name="id" value="<?php echo $info['id'] ?>">
			<div class="form-box clearfix">
				<?php echo form::input('text', 'name', $info['name'], '优惠券名称：', '请输入优惠券名称', array(
					'datatype' => '*',
					'nullmsg' => '请输入优惠券名称',
					)
				); ?>
				<?php echo form::input('textarea', 'describe', $info['describe'], '优惠券描述：', '请输入优惠券描述', array(
					'datatype' => '*',
					'nullmsg' => '请输入优惠券描述',
					)
				); ?>
				<?php echo form::input('text', 'num', $info['num'], '优惠券发放总量：', '请输入优惠券发放总量（请输入正整数或-1）', array(
					'datatype' => '*',
					'readonly' =>'readonly',
					'nullmsg' => '请输入发放总量',
					)
				); ?>
				<?php echo form::input('text', 'rules[discount]', $info['rules']['discount'], '面值：', '请输入优惠券面值（请输入正数）', array(
					'datatype' => '*',
					'nullmsg' => '请输入面值',
					)
				); ?>
				<?php echo form::input('text', 'rules[condition]', $info['rules']['condition'], '消费限制：', '请输入优惠券消费限制（请输入正数或-1）', array(
					'datatype' => '*',
					'nullmsg' => '请输入消费限制',
					)
				); ?>
				<?php echo form::input('text', 'receive_num', $info['receive_num'], '领取数量限制：', '请输入会员领取该优惠券最多数量（请输入正整数）', array(
					'datatype' => 'n',
					'nullmsg' => '请输入最多领取数量',
					)
				); ?>
				<div class="type_time">
					<?php echo form::input('radio','type_time', $info['type_time'],'生效时间：', '请选择优惠券生效时间类型', array('items'=>array(1=>'活动时间范围',2=>'领取后几日失效'),'colspan'=>'3')); ?>
				</div>
				<div class="after">
					<?php echo form::input('text', 'time', $info['time'], '领取后失效天数：', '请输入会员领取该优惠券后几日失效（请输入正整数）'); ?>
				</div>
				<div class="range">
					<?php echo form::input('calendar', 'start_time', date('Y-m-d H:i',$info['start_time']), '开始时间：', '优惠券开始时间', array('format' => 'YYYY-MM-DD hh:mm')); ?>
					<?php echo form::input('calendar', 'end_time', date('Y-m-d H:i',$info['end_time']), '结束时间：', '优惠券结束时间', array('format' => 'YYYY-MM-DD hh:mm')); ?>
				</div>
				<div class="remind">
					<?php echo form::input('radio','remind', $info['remind'],'是否开启提醒：', '请选择是否开启提醒', array('items'=>array(0=>'不开启',1=>'开启'),'colspan'=>'2')); ?>
				</div>
				<div class="remind_time">
					<?php echo form::input('text', 'remind_time', $info['remind_time'], '到期提醒：', '请输入优惠券到期提醒天数（请输入正整数）'); ?>
				</div>
				<div class="type_remind">
					<?php echo form::input('checkbox','type_remind[]', $info['type_remind'],'提醒类型：', '请选择优惠券提醒类型', array('items'=>array('短信'=>'短信','站内信'=>'站内信','邮件'=>'邮件'),'colspan'=>'3')); ?>
				</div>
				<div class="type_buy">
					<?php echo form::input('checkbox','type_buy', $info['type_buy'],'购买类型：', '请选择优惠券购买类型', array('items'=>array(1=>'仅原价商品可用'),'colspan'=>'1')); ?>
				</div>
				<div class="type_use">
					<?php echo form::input('radio','type_use', $info['type_use'],'使用类型：', '请选择优惠券使用类型', array('items'=>array(1=>'全店商品',2=>'指定商品'),'colspan'=>'2')); ?>
				</div>	
			</div>
			<div class="layout padding clearfix">
				<div class="type_use padding-right">
					<div class="table resize-table high-table border clearfix" data-model="sku_list">
						<div class="table-add-top">
							<div class="th layout">
								<a class="text-sub text-left" href="javascript:;" data-event="add_skulist"><em class="ico_add margin-right"></em>选择商品</a>
							</div>
						</div>
						<div class="tr border-none">
							<div class="th" data-width="45">
								<span class="td-con">商品名称</span>
							</div>
							<div class="th" data-width="20">
								<span class="td-con">原价</span>
							</div>
							<div class="th" data-width="20">
								<span class="td-con">库存</span>
							</div>
							<div class="th" data-width="15">
								<span class="td-con">操作</span>
							</div>
							<?php foreach ($info['sku_lists'] as $k => $sku): ?>
							<div class="tr" data-skuid="<?php echo $sku['sku_id'] ?>" data-number="<?php echo $sku['number'] ?>" data-thumb="<?php echo $sku['thumb'] ?>" data-title="<?php echo $sku['sku_name'] ?>" data-spec="<?php echo $sku['spec'] ?>" data-price="<?php echo $sku['shop_price'] ?>">
								<div class="td w45">
									<div class="td-con td-pic text-left">
										<div class="pic"><img src="<?php echo $sku['thumb'] ?>" /></div>
										<div class="title">
											<p class="text-ellipsis padding-small-left"><?php echo $sku['sku_name'] ?></p>
										</div>
										<div class="icon">
											<p class="text-ellipsis"><span class="text-sub">商品类型:</span><?php echo $sku['spec'] ?></p>
										</div>
									</div>
								</div>
								<div class="td w20">
									<div class="td-con">￥<?php echo $sku['shop_price']?></div>
								</div>
								<div class="td w20">
									<div class="td-con"><?php echo $sku['number'] ?></div>
								</div>
								<div class="td w15">
									<div class="td-con"><a href="javascript:;" data-event="del_sku">移除</a></div>
								</div>
								<input type="hidden" name="sku_ids[]" value="<?php echo $sku['sku_id'] ?>"/>
							</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>
			<div class="padding">
				<input type="submit" class="button bg-main" value="保存" />
				<button type="button" class="button margin-left bg-gray">返回</button>
			</div>
			</form>
		</div>
<script type="text/javascript">
	var type_time = $('.type_time input:checked').val();
	if(type_time == 1){
		$('.range').show();
		$('.after').hide();
	}else{
		$('.range').hide();
		$('.after').show();
	}
	var remind = $('.remind input:checked').val();
	if(remind == 0){
		$('.remind_time').hide();
		$('.type_remind').hide();
	}else{
		$('.remind_time').show();
		$('.type_remind').show();
	}
	var type_use = $('.type_use input:checked').val();
		if(type_use == 1){
			$(".layout .type_use").hide();
		}else{
			$(".layout .type_use").show();
		}
	$('.type_time').change(function(){
		var type_time = $('.type_time input:checked').val();
		if(type_time == 1){
			$('.range').show();
			$('.after').hide();
		}else{
			$('.range').hide();
			$('.after').show();
		}
	});
	$('.remind').change(function(){
		var remind = $('.remind input:checked').val();
		if(remind == 0){
			$('.remind_time').hide();
			$('.type_remind').hide();
		}else{
			$('.remind_time').show();
			$('.type_remind').show();
		}
	});
	$(".type_use").bind('change',function(){
		var type_use = $('.type_use input:checked').val();
		if(type_use == 1){
			$(".layout .type_use").hide();
		}else{
			$(".layout .type_use").show();
		}
		$(window).resize();
	});

	$(function(){
		$('.resize-table').resizableColumns();
		var removeids = {};
		$("[data-event='del_sku']").live('click',function(){
			var sku_id = $(this).parents(".tr[data-skuid]").data('skuid');
			removeids[sku_id] = {'removeid':sku_id};
			$(this).parents(".tr[data-skuid]").remove();
			return false;
		});

		$("select[name^=rules]").live("change", function(){
			var _val = $(this).val();
			var _id = $(this).parents(".tr[data-id]").data('id');
			var _html = template('rule_' + _val, {"i" : _id});
			$(this).parents(".tr[data-id]").find("div[data-model='rule']").html(_html);
		})

		/* 选择优惠券商品 */
		$("a[data-event='add_skulist']").live("click", function(){
			var selected = {};
			$("div[data-model='sku_list']").find("[data-skuid]").each(function(i ,n){
				var sku_id = $(this).data('skuid');
				selected[sku_id] = {
					id : sku_id,
					number : $(this).data('number'),
					pic : $(this).data('thumb'),
					price : $(this).data('price'),
					spec : $(this).data('spec'),
					title : $(this).data('title')
				}
			})
			top.dialog({
				url: "<?php echo url('goods/sku/select', array('multiple' => 1,'type' => 'give'))?>",
				title: '选择优惠券商品',
				width: 980,
				removeids:removeids,
				selected:selected,
				onclose: function () {
					if(this.returnValue){
						var _html = template('template_skulist', {'skulist' : this.returnValue});
						$("div[data-model='sku_list']").find("[data-skuid]").remove();
						$("div[data-model='sku_list']").find('.border-none').after(_html);
					}
				}
			})
			.showModal();			
			return false;
		})

		var coupon_edit = $("form[name=coupon_edit]").Validform({
			ajaxPost:false
		})
		
	})			
</script>

<script id="template_skulist" type="text/html">
<%for(var id in skulist){%>
<%sku = skulist[id]%>
<div class="tr" style="visibility: visible;" data-skuid="<%=sku['id']%>" data-number="<%=sku['number']%>" data-thumb="<%=sku['pic']%>" data-title="<%=sku['title']%>" data-spec="<%=sku['spec']%>" data-price="<%=sku['price']%>">
	<div class="td w45">
		<div class="td-con td-pic text-left">
			<div class="pic"><img src="<%=sku['pic']%>" /></div>
			<div class="title">
				<p class="text-ellipsis padding-small-left"><%=sku['title']%></p>
			</div>
			<div class="icon">
				<p class="text-ellipsis"><span class="text-sub">商品类型:</span><%=sku['spec']%></p>
			</div>
		</div>
	</div>
	<div class="td w20">
		<div class="td-con">￥<%=sku['price']%></div>
	</div>
	<div class="td w20">
		<div class="td-con"><%=sku['number']%></div>
	</div>
	<div class="td w15">
		<div class="td-con"><a href="javascript:;" data-event="del_sku">移除</a></div>
	</div>
</div>
<input type="hidden" name="sku_ids[]" value="<%=sku['id']%>"/>
<% }%>
</script>
	</body>
</html>

