<?php include $this->admin_tpl('header','admin');?>
	<script type="text/javascript" src="./statics/js/goods/goods_list.js"></script>
		<div class="fixed-nav layout">
			<ul>
				<li class="first">优惠券活动<a id="addHome" title="添加到首页快捷菜单">[+]</a></li>
				<li class="spacer-gray"></li>
				<li><a class="current" href="javascript:;"></a></li>
			</ul>
			<div class="hr-gray"></div>
		</div>
		<div class="content padding-big have-fixed-nav">
			<div class="tips margin-tb">
				<div class="tips-info border">
					<h6>温馨提示</h6>
					<a id="show-tip" data-open="true" href="javascript:;">关闭操作提示</a>
				</div>
				<div class="tips-txt padding-small-top layout">
					<p class="text-red">- 第一次使用前，务必手动更新缓存</p>
				</div>
			</div>
			<div class="hr-gray"></div>
			<div class="table-work border margin-tb">
				<div class="border border-white tw-wrap">
					<a href="<?php echo url('add')?>"><i class="ico_add"></i>添加</a>
					<div class="spacer-gray"></div>
					<a data-message="是否确定删除所选？" href="<?php echo url('delete')?>" data-ajax='id'><i class="ico_delete"></i>删除</a>
					<div class="spacer-gray"></div>
				</div>
			</div>
			<div class="table resize-table paging-table check-table border clearfix">
				<div class="tr">
					<span class="th check-option" data-resize="false">
						<span><input id="check-all" type="checkbox" /></span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">优惠券名称</span>
					</span>
					<span class="th" data-width="8">
						<span class="td-con">满足金额</span>
					</span>
					<span class="th" data-width="6">
						<span class="td-con">面值</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">使用范围</span>
					</span>
					<span class="th" data-width="8">
						<span class="td-con">仅原价可用</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">提醒时间</span>
					</span>
					<span class="th" data-width="12">
						<span class="td-con">提醒类型</span>
					</span>
					<span class="th" data-width="13">
						<span class="td-con">到期时间</span>
					</span>
					<span class="th" data-width="8">
						<span class="td-con">数量</span>
					</span>
					<span class="th" data-width="15">
						<span class="td-con">操作</span>
					</span>
				</div>
				<?php foreach ($info AS $prom) {?>
				<div class="tr">
					<div class="td check-option"><input type="checkbox" name="id" value="<?php echo $prom['id']?>" /></div>
					<span class="td">
						<span class="td-con"><?php echo $prom['name']?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php if($prom['condition'] == -1){echo '无限制';}else{ echo $prom['condition'];} ?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $prom['discount'];?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php if($prom['type_use'] == 1){echo '全店商品';}else{ echo '指定商品';} ?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php if($prom['type_buy'] == 1){echo '是';}else{ echo '否';} ?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $prom['type_remind']?'提前'.$prom['remind_time'].'天':'-'?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php if($prom['type_remind']){ echo $prom['type_remind'];}else{echo '不提醒';}?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php if($prom['type_time'] == 1){echo date('Y-m-d H:i:s',$prom['end_time']);}else{ echo '领后'.$prom['time'].'天失效';} ?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $prom['num'];?></span>
					</span>
					
					<span class="td">
						<span class="td-con">
							<!-- <a href="javascript:agree(<?php echo $prom['id']?>,'<?php echo url('coupon_list/index',array('cid'=>$prom['id']))?>');" data-id="<?php echo $prom['id']?>">生成</a>&nbsp;&nbsp;&nbsp; -->
							<a href="<?php echo url('coupon_list/index',array('cid'=>$prom['id']))?>">详细</a>&nbsp;&nbsp;&nbsp;
							<a href="<?php echo url('edit',array('id'=>$prom['id']))?>">编辑</a>&nbsp;&nbsp;&nbsp;
							<a data-confirm="是否确认删除？" href="<?php echo url('delete',array('id'=>$prom['id']))?>">删除</a>
						</span>
					</span>
				</div>
				<?php }?>
				<div class="paging padding-tb body-bg clearfix">
					<?php echo $pages?>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<script>
			$(".table").resizableColumns();
			$(".table").fixedPaging();
			url = "<?php echo url('ajax_set_coupons')?>";
			function agree(id,tpl_url){
				var d = top.dialog({
					url : url,
					title: '生成优惠券',
					data:{
						id:id,
						url:tpl_url
					},
					width: 280,
					height: 125,
					okValue : '确定',
					cancelValue : '取消',
					onclose: function() {
						if(this.returnValue){
							window.location.href = this.returnValue;
						}
				    }
				});
				d.showModal();
			}

		</script>