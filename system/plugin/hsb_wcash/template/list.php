<?php include template('header','admin');?>
	<script type="text/javascript" src="./statics/js/goods/goods_list.js"></script>
		<div class="fixed-nav layout">
			<ul>
				  <li class="first">余额提现_提现记录</li>
        <li class="spacer-gray"></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash'))?>">基本设置</a></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => 'config_alidayu'))?>">短信验证设置</a></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => 'config_alipay'))?>">支付宝设置</a></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => 'config_wxpay'))?>">微信支付设置</a></li>
				<li><a class="current" href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:list'))?>">提现记录</a></li>
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
					<p>1、实时付款：点击后，系统将自动付款给申请人，请确保相应平台中有足够余额。</p>
					<p>2、手动付款：由财务人员手动付款后，点此进行标记</p>
					<p>3、拒绝申请：点击后，客户的冻结余额将恢复至可用余额，本次申请提现终止。</p>
				</div>
			</div>
		<div class="hr-gray"></div>
		<div class="clearfix">
			<form name="form-search" method="get">
				<input type="hidden" name="m" value="admin" />
				<input type="hidden" name="c" value="app" />
				<input type="hidden" name="a" value="module" />
				<input type="hidden" name="mod" value="hsb_wcash:list" />
				<div class="form-group form-layout-rank border-none" style="width: 300px;">
					<div class="box ">
						<div class="field margin-none">
							<input class="input" type="text" name="keyword" value="<?php echo $_GET['keyword']; ?>" placeholder="收款人" tabindex="0">
						</div>
					</div>
				</div>
				<input class="button bg-sub margin-top fl" type="submit" style="height: 26px; line-height: 14px;" value="查询">
			</form>
		</div>

	<div class="table-wrap">
			<div class="table resize-table paging-table border clearfix">
				<div class="tr">
					<span class="th" data-width="5">
						<span class="td-con">序号</span>
					</span>
					<span class="th" data-width="15">
						<span class="td-con">收款账户</span>
					</span>
					<span class="th" data-width="5">
						<span class="td-con">账户类型</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">收款人</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">提现金额</span>
					</span>
					<span class="th" data-width="5">
						<span class="td-con">提现费率</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">实际应付</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">申请日期</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">处理日期</span>
					</span>
					<span class="th" data-width="10">
						<span class="td-con">会员</span>
					</span>
				    <span class="th" data-width="10">
						<span class="td-con">操作</span>
					</span>
				</div>
				<?php foreach ($infos AS $k=>$r): ?>
				<div class="tr">
					<span class="td">
						<div class="td-con"><?php echo $k+1?></div>
					</span>
					<span class="td">
						<div class="td-con"><?php if($r['account_type']=='alipay'){?><?php echo $r['account']?><?php }elseif($r['account_type']=='wxpay'){?><?php echo _getopenid($r['member_id'])?><?php }?></div>
					</span>
					<span class="td">
						<div class="td-con"><?php if($r['account_type']=='alipay'){?>支付宝<?php }elseif($r['account_type']=='wxpay'){?>微信<?php }?></div>
					</span>
					<span class="td">
						<span class="td-con"><?php echo $r['true_name']?></span>
					</span>
					<span class="td">
						<span class="td-con">￥<?php echo sprintf('%.2f' ,$r['money'])?></span>
					</span>
					<span class="td">
						<div class="td-con"><?php echo sprintf('%.2f' ,$r['rate'])?>%</div>
					</span>
					<span class="td">
						<div class="td-con">￥<?php echo sprintf('%.2f' ,$r['go_money'])?></div>
					</span>
					<span class="td">
						<div class="td-con"><?php echo date('Y-m-d H:i:s',$r['up_date'])?></div>
					</span>
					<span class="td">
						<span class="td-con"><?php if($r['do_date']=='0'){?><?php }else{?><?php echo date('Y-m-d H:i:s',$r['do_date'])?><?php }?></span>
					</span>
					<span class="td">
						<span class="td-con"><?php echo _getuser($r['member_id'])?></span>
					</span>
					<span class="td">
						<span class="td-con">
						<?php if($r['status']=='0'){?>
						
						      <?php if($config['alipay_autopay_status'] && $r['account_type']=='alipay'){?>
						   <a data-confirm="将立即支付，是否继续？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:list','act' => 'alipay_autopay','id' => $r['id']))?>">实时</a>&nbsp;&nbsp;/&nbsp;&nbsp;
						      <?php }?>
							  
						      <?php if($config['wxpay_autopay_status'] && $r['account_type']=='wxpay'){?>
						   <a data-confirm="将立即支付，是否继续？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:list','act' => 'wxpay_autopay','id' => $r['id']))?>">实时</a>&nbsp;&nbsp;/&nbsp;&nbsp;
						      <?php }?>
							  
						   <a data-confirm="确认已付款？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:list','act' => 'pay','id' => $r['id']))?>" style=" color:#FF0000">手动</a>&nbsp;&nbsp;/&nbsp;&nbsp;
						   <a data-confirm="确认拒绝？" href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:list','act' => 'nopay','id' => $r['id']))?>">拒绝</a>
						
						<?php }elseif($r['status']=='1'){?>
						
						   <font title="转账单据号：<?php echo $r['order_id']?>；支付时间：<?php echo $r['pay_date']?>">已实时支付</font>
						
						<?php }elseif($r['status']=='2'){?>
						
						   已手动支付
						
						<?php }elseif($r['status']=='-1'){?>
						
						   已拒绝申请
						
						<?php }?>
						
						</span>
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