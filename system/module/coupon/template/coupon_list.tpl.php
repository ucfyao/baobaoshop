<?php include $this->admin_tpl('header','admin');?>
	<script type="text/javascript" src="./statics/js/goods/goods_list.js"></script>
		<div class="fixed-nav layout">
			<ul>
				<li class="first">优惠券<a id="addHome" title="添加到首页快捷菜单">[+]</a></li>
				<li class="spacer-gray"></li>
				<li><a class="current" href="javascript:;"></a></li>
			</ul>
			<div class="hr-gray"></div>
		</div>
		<div class="content padding-big have-fixed-nav">
			<div class="table-work border margin-tb">
				<div class="border border-white tw-wrap">
					<a data-message="是否确定删除所选？" href="<?php echo url('delete')?>" data-ajax='id'><i class="ico_delete"></i>删除</a>
					<div class="spacer-gray"></div>
				</div>
			</div>
			<div class="table resize-table paging-table check-table border clearfix">
				<div class="tr">
					<span class="th check-option" data-resize="false">
						<span><input id="check-all" type="checkbox" /></span>
					</span>
					<span class="th" data-width="20">
						<span class="td-con">优惠券名称</span>
					</span>
					<span class="th" data-width="20">
						<span class="td-con">优惠劵编码号</span>
					</span>
					<span class="th" data-width="20">
						<span class="td-con">使用订单</span>
					</span>
					<span class="th" data-width="15">
						<span class="td-con">所属会员</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">状态</span>
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
						<span class="td-con"><?php echo $prom['code']?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $prom['sn']?$prom['sn']:'-' ;?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $prom['username']?$prom['username']:'-' ;?></span>
					</span>
					
					<span class="td">
						<span class="td-con"><?php if($prom['status'] == 0){echo '未领取';}elseif($prom['status'] == 1){echo '已领取';}elseif($prom['status'] == 2){echo '已使用';}else{echo '已禁用';}?></span>
					</span>
					<span class="td">
						<span class="td-con"><a <?php if($prom['status'] == 3||$prom['status'] == 2){echo 'hidden';}?> href="<?php echo url('disable',array('id'=>$prom['id'],'status'=>3,'cid'=>$prom['cid']))?>">禁用</a>&nbsp;&nbsp;&nbsp;<a data-confirm="是否确认删除？" href="<?php echo url('delete',array('id'=>$prom['id'],'cid'=>$prom['cid']))?>">删除</a></span>
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
			var ajax_name = "<?php echo url('ajax_name')?>";
			$(".table").resizableColumns();
			$(".table").fixedPaging();
			$('input[name=name]').bind('blur',function() {
				var name = $(this).val();
				var id = $(this).attr('data-id');
				list_action.change_name(ajax_name,id,name);
			});
		</script>