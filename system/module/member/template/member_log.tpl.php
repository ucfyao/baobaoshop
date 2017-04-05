<?php include template('header','admin');?>
<div class="fixed-nav layout">
	<ul>
		<li class="first">余额管理<a id="addHome" title="添加到首页快捷菜单">[+]</a></li>
		<li class="spacer-gray"></li>
	</ul>
	<div class="hr-gray"></div>
</div>
<div class="content padding-big have-fixed-nav">
	<form method="GET" action="">
			<input type="hidden" value="member" name="m" />
			<input type="hidden" value="member_log" name="c" />
			<input type="hidden" value="index" name="a" />
			<div class="member-comment-search clearfix">
				<div class="form-box clearfix border-bottom-none" style="width: 590px;">
					<div style="z-index: 4;" id="form-group-id1" class="form-group form-layout-rank group1">
						<span class="label">操作时间</span>
						<div class="box margin-none">
							<?php echo form::calendar('start',!empty($_GET['start']) ? $_GET['start']:'',array('format' => 'YYYY-MM-DD'))?>
						</div>
					</div>
					<div style="z-index: 3;" id="form-group-id2" class="form-group form-layout-rank group2">
						<span class="label">~</span>
						<div class="box margin-none">
							<?php echo form::calendar('end',!empty($_GET['end'])? $_GET['end']:'',array('format' => 'YYYY-MM-DD'))?>
						</div>
					</div>
					<div style="z-index: 1;" id="form-group-id4" class="form-group form-layout-rank group4">
						<span class="label">搜索</span>
						<div class="box margin-none">
							<input class="input" name="keywords" placeholder="请输入会员名搜索余额信息" tabindex="0" type="text" value="<?php echo !empty($_GET['keywords'])?$_GET['keywords'] :''?>">
						</div>
					</div>
				</div>
				<input class="button bg-sub fl" value="查询" type="submit">
			</div>
			</form>
	<div class="table-wrap member-info-table">
		<div class="table resize-table check-table border paging-table clearfix">
			<div class="member  tr">
				<span class="th" data-width="10">
					<span class="td-con">会员账号</span>
				</span>
				<span class="th" data-width="20">
					<span class="td-con">操作日期</span>
				</span>
				<span class="th" data-width="20">
					<span class="td-con">操作金额</span>
				</span>
				<span class="th" data-width="40">
					<span class="td-con">日志描述</span>
				</span>
				<span class="th" data-width="10">
					<span class="td-con">状态</span>
				</span>
			</div>
			<?php foreach ($lists AS $r): ?>
			<div class="member tr">
				<!-- <span class="td check-option"><input type="checkbox" name="id" value="1" /></span> -->
				<div class="td"><span class="td-con"><?php echo $r['username'] ?></span></div>
				<div class="td">
					<span class="td-con"><?php echo date('Y-m-d H:i:s', $r['dateline']); ?></span>
				</div>
				<div class="td">
					<span class="td-con "><?php echo $r['value'];?></span>
				</div>
				<div class="td">
					<span class="td-con"><?php echo $r['msg'] ?></span>
				</div>
				<div class="td">
					<span class="td-con">成功</span>
				</div>
			</div>
			<?php endforeach ?>
			<div class="paging padding-tb body-bg clearfix">
				<?php echo $pages; ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
	<script>
		$(".table").resizableColumns();
		$(".paging-table").fixedPaging();
		/*$('.batch-delete').batchDelete({
			url: "<?php echo url('del')?>",
			formhash: "<?php echo FORMHASH?>"
		});*/
	</script>
<?php include template('footer','admin');?>
