<?php include template('header','admin');?>
	<script type="text/javascript" src="./statics/js/goods/goods_list.js"></script>
		<div class="fixed-nav layout">
			<ul>
				<li class="first">侧边工具栏_客服设置</li>
				<li class="spacer-gray"></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:hsb_toolbar'))?>">基本设置</a></li>
				<li><a class="current" href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list'))?>">客服设置</a></li>
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
					<p>1、客服排序，数字越大越靠前。</p>
				</div>
			</div>

			<div class="hr-gray"></div>
			<div class="table-work border margin-tb">
				<div class="border border-white tw-wrap">
					<a href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:kf_add'))?>"><i class="ico_add"></i>添加</a>
				</div>
			</div>

	<div class="table-wrap">
			<div class="table resize-table paging-table high-table border clearfix">
				<div class="tr">
					<span class="th" data-width="20">
						<span class="td-con">客服名称</span>
					</span>
					<span class="th" data-width="30">
						<span class="td-con">客服号码</span>
					</span>
					<span class="th" data-width="20">
						<span class="td-con">客服类型</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">客服排序</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">显示状态</span>
					</span>
				    <span class="th" data-width="10">
						<span class="td-con">操作</span>
					</span>
				</div>
				<?php foreach ($kfs AS $v): ?>
				<div class="tr">
					<span class="td">
						<div class="td-con"><?php echo $v['kf_name']?></div>
					</span>
					<span class="td">
						<div class="td-con"><?php echo $v['kf_no']?></div>
					</span>
					<span class="td">
						<span class="td-con"><?php echo getkftype($v['kf_type'])?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $v['kf_sort']?></span>
					</span>
					<span class="td">
						<span class="td-con">
						<?php if($v['kf_status'] == 1){?>
							<a class="ico_up_rack" data-confirm="确认隐藏？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:kf_ajax_status','id' => $v['id']))?>&is_on=0"></a>
						<?php }else{?>
							<a class="ico_up_rack cancel" data-confirm="确认显示？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:kf_ajax_status','id' => $v['id']))?>&is_on=1"></a>
						<?php }?>
						</span>
					</span>
					<span class="td">
						<span class="td-con"><a data-confirm="确认删除？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:kf_delete','id' => $v['id']))?>">删除</a></span>
					</span>
				</div>
				<?php endforeach ?>
				<div class="paging padding-tb body-bg clearfix">
					<?php echo $pages?>
					<div class="clear"></div>
				</div>
			</div>
	</div>
<script>
	$(".form-group .box").addClass("margin-none");
	$(window).load(function(){
		$(".table").resizableColumns();
		$(".paging-table").fixedPaging();
		var $val=$("input[type=text]").first().val();
		$("input[type=text]").first().focus().val($val);
	})
</script>
<?php include template('footer','admin');?>