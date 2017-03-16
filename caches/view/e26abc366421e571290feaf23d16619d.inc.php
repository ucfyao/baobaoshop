<?php if(!defined('IN_APP')) exit('Access Denied');?>
<!--顶部广告-->
<?php
	$taglib_ads_ads = new taglib('ads','ads');
	$data = $taglib_ads_ads->lists(array('position'=>'1','order'=>'rand()'), array('limit'=>'1','cache'=>'240ec2b572794609af18760938e51808,3600'));
?>
<?php if(($data['list'])) { ?>
<div class="roof"><?php if(is_array($data['list'])) foreach($data['list'] as $r) { ?><?php if(empty($r['content'])) { ?>
<a href="javascript:;" >
<img src="<?php echo $data['defaultpic'];?>"/>
</a>
<?php } else { ?>
<a href="<?php echo url('ads/index/adv_view',array('id'=>$r['id'],'url'=>$r['link']));?>" style="display:block;">
<img src="<?php echo $r['content'];?>" alt="<?php echo $r['title'];?>">
</a>
<?php } ?>
<?php } ?>
</div>
<?php } ?>

<?php $data = runhook('common_header');?>
<!-- 头部 -->
<div class="header container">
<div class="header_slogan">
<h1 class="text-main">去泰国，自由行。</h1>
<p>预订独一无二的行程，像当地人一样自由。</p>
</div>
<!-- 搜索框 -->
<div class="search-wrap border radius">
<div class="search">
<form name="form-search" action="<?php echo __APP__;?>" method="get">
<input type="hidden" name="m" value="goods">
<input type="hidden" name="c" value="search">
<input type="hidden" name="a" value="search">
<input class="input border-none bg-none fl" type="text" name="keyword" placeholder="请输入商品关键字" value="" />
<input class="button text-big text-white radius-none bg-OrangeRed fr" type="submit" value="搜索" />
</form>
</div>
<ul class="keyword margin-top text-gray">
<?php
	$taglib_goods_search = new taglib('goods','search');
	$data = $taglib_goods_search->lists(array(), array('limit'=>'8','cache'=>'11999923d95a27ded3e6ae9cfc5d00eb,3600'));
?><?php if(is_array($data)) foreach($data as $k => $z) { ?><?php if($k > 0) { ?>
<li class="spacer"></li>
<?php } ?>
<li><a class="text-gray" href="<?php echo url('goods/search/search',array('keyword' => $z))?>"><?php echo $z;?></a></li>
<?php } ?>

</ul>
<?php echo runhook('header_search');?>
</div>
<div class="user border radius fr">
<!-- 购物车 -->
<div class="user-box fun-show" id="carts">
<div class="user-top bg-white #border">
<i class="icon-cart fl"></i>我的购物车<em class="cart-num text-center bg-OrangeRed margin-big-left fr" id="count">0</em>
</div>
<div class="user-content bg-white cart border radius">
<div class="user-con-top border-bottom">
<a class="fl">最新加入的商品</a>
<a class="fr" href="javascript:;">清空购物车</a>
</div>
<div class="cart-goods border-bottom none hidden" id="lists"></div>
<div class="cart-tips bg-white text-center"><p>购物车加载中...</p></div>
<div class="cart-info padding-lr">
<p class="margin-small-top hidden none">共
<em class="text-mix g_c">0</em> 种商品
<em class="text-mix m_c">0</em> 件，
商品金额总计:<em class="text-mix text-default p_c">￥0.00</em>
</p>
<a class="cart-btn margin-tb" href="<?php echo url('order/cart/index');?>">去购物车结算</a>
<!--商品下架时-->
<!--<a class="cart-btn margin-tb bg-gray" href="#">去购物车结算</a>-->
</div>
</div>
<?php $data = runhook('cart_box');?>
</div>
<!-- 会员中心 -->
<div class="user-box fun-show">
<div class="user-top bg-white">
<i class="icon-member fl"></i>会员中心<i class="icon-down fr"></i>
</div>
<div class="user-content bg-white core border radius">
<div class="user-con-top border-bottom">
<?php if($member['id']){?>
<a class="fl text-sub" href="<?php echo url('member/index/index');?>"><?php echo $member['username'] ?></a>
<?php }else{?>
<a class="fl text-sub" href="<?php echo url('member/public/login');?>">登录</a>
<?php }?>
<a class="fr text-sub" href="<?php echo url('member/index/index');?>">进入会员中心</a>
</div>
<div class="core-menu padding-tb clearfix">
<ul class="border-right">
<li><a class="text-sub" href="<?php echo url('member/order/index', array('type' => 1));?>">待付款订单(<?php echo $member['counts']['pay'] ? $member['counts']['pay'] : 0?>)</a></li>
<li><a class="text-sub" href="<?php echo url('member/order/index', array('type' => 4));?>">待收货订单(<?php echo $member['counts']['receipt'] ? $member['counts']['receipt'] : 0?>)</a></li>
<!--<li><a class="text-sub" href="#">我的优惠券(0)</a></li>-->
</ul>
<ul>
<li><a class="text-sub" href="<?php echo url('member/order/index');?>">我的订单</a></li>
<li><a class="text-sub" href="<?php echo url('member/favorite/index');?>">我的收藏</a></li>
<!--<li><a class="text-sub" href="#">我的积分(0)</a></li>-->
</ul>
</div>
<div class="histroy border-top">
<span>最近浏览的商品</span>
<ul>
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->history(array(), array('limit'=>'5','cache'=>'d255d824d275a4f39404d3ee593755de,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><li class="pic"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><img src="<?php echo thumb($r['thumb']);?>" /></a></li>
<?php } ?>

</ul>
</div>
<?php $data = runhook('user_box');?>
</div>
</div>
</div>
</div>
<!-- 购物车结束 -->
<!-- 导航 -->
<div class="nav">
<div class="container">
<!-- 商品分类 -->
<div class="category fl">
<h2 class="dt bg-OrangeRed text-default text-white">
<i>
    <svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M196.277147 586.083667c0-12.674671-10.264182-22.950711-22.951652-22.950711L87.253731 563.132956C74.567285 563.132956 64.300033 573.41002 64.300033 586.083667l0 86.069261C64.300033 684.827599 74.567285 695.104663 87.253731 695.104663l86.071764 0c12.68747 0 22.951652-10.276041 22.951652-22.950711L196.277147 586.083667zM959.45027 586.083667c0-12.674671-10.265205-22.950711-22.951652-22.950711L311.040523 563.132956c-12.686447 0-22.952675 10.276041-22.952675 22.950711l0 86.069261c0 12.674671 10.266228 22.950711 22.952675 22.950711l625.458095 0c12.685423 0 22.951652-10.276041 22.951652-22.950711L959.45027 586.083667zM196.277147 279.106134c0-12.674671-10.264182-22.951735-22.951652-22.951735L87.253731 256.154399C74.567285 256.154399 64.300033 266.431463 64.300033 279.106134l0 86.069261c0 12.674671 10.266228 22.951735 22.952675 22.951735l86.071764 0c12.68747 0 22.951652-10.276041 22.951652-22.951735L196.276124 279.106134zM959.45027 279.106134c0-12.674671-10.265205-22.951735-22.951652-22.951735L311.040523 256.154399c-12.686447 0-22.952675 10.277064-22.952675 22.951735l0 86.069261c0 12.674671 10.266228 22.951735 22.952675 22.951735l625.458095 0c12.685423 0 22.951652-10.276041 22.951652-22.951735L959.45027 279.106134zM196.277147-27.871399c0-12.674671-10.264182-22.951735-22.951652-22.951735L87.253731-50.823134C74.567285-50.823134 64.300033-40.54607 64.300033-27.871399l0 86.068238c0 12.675694 10.266228 22.952758 22.952675 22.952758l86.071764 0c12.68747 0 22.951652-10.277064 22.951652-22.952758L196.276124-27.871399zM959.45027-27.871399c0-12.674671-10.265205-22.951735-22.951652-22.951735L311.040523-50.823134c-12.686447 0-22.952675 10.277064-22.952675 22.951735l0 86.068238c0 12.675694 10.266228 22.952758 22.952675 22.952758l625.458095 0c12.685423 0 22.951652-10.277064 22.951652-22.952758L959.45027-27.871399z" fill="#fff"></path>
</g>
</svg>
</i>全部商品分类</h2>
<div class="dd">
<ul class="dd-inner clearfix">
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('order'=>'sort asc','catid'=>'0'), array('limit'=>'20','cache'=>'c5958cbc14c5ef7b035ab9e05bad0a36,3600'));
?><?php if(is_array($data)) foreach($data as $k => $r) { ?><li class="item <?php if($k%2 != 0) { ?>item-striped<?php } ?> clearfix">
<dl class="left-category">
<dt>
<a href="<?php echo !empty($r['url']) ? $r['url'] : url('goods/index/lists',array('id'=>$r['id']))?>" class="strong"><?php echo $r['name'];?></a>
<i class="arrow"></i>
</dt>
<dd>
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('order'=>'sort asc','catid'=>$r[id],'type'=>'nav'), array('limit'=>'20','cache'=>'0cb35fe7452719945c257ce372f902ed,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?>                                <a href="<?php echo !empty($r['url']) ? $r['url'] : url('goods/index/lists',array('id'=>$r['id']))?>"><?php echo $r['name'];?></a>
                            <?php } ?>

                            </dd>
</dl>
<div class="spacer"></div>
</li>
<?php } ?>

</ul>
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('order'=>'sort asc','catid'=>'0'), array('limit'=>'20','cache'=>'c5958cbc14c5ef7b035ab9e05bad0a36,3600'));
?>
<div class="dropdown-layer">
<div class="droplayer-text clearfix"><?php if(is_array($data)) foreach($data as $r) { ?><div class="item-sub">
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('order'=>'sort asc','catid'=>$r[id]), array('limit'=>'20','cache'=>'d91b87346a17844da41db5eeaf736528,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><div class="item-sub-box">
<a href="<?php echo !empty($r['url']) ? $r['url'] : url('goods/index/lists',array('id'=>$r['id']))?>"><h5><?php echo $r['name'];?></h5></a>
<ul>
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('order'=>'sort asc','catid'=>$r[id]), array('limit'=>'20','cache'=>'d91b87346a17844da41db5eeaf736528,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><li>
<span class="spacer"></span>
<a href="<?php echo !empty($r['url']) ? $r['url'] : url('goods/index/lists',array('id'=>$r['id']))?>"><?php echo $r['name'];?></a>
</li>
<?php } ?>
</ul>
</div>
<?php } ?>

</div>
<?php } ?>
</div>
</div>

</div>
</div>
<!-- 商品分类结束 -->
<ul class="site-menu text-default fl">
<?php
	$taglib_misc_navigation = new taglib('misc','navigation');
	$data = $taglib_misc_navigation->lists(array('order'=>'sort ASC'), array('limit'=>'20','cache'=>'854e9c758643a2ec44095dc3e4c46006,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><li><a href="<?php echo $r['url'];?>" <?php if(($r['target']==1)) { ?>target="_blank"<?php } ?>><?php echo $r['name'];?></a></li>
<?php } ?>

</ul>
</div>
</div>
<?php $data = runhook('nav_between_content');?>
<!--导航结束-->
<!--面包屑-->
<script>
var url = window.location.href;
$(".site-menu li").each(function(){
if($(this).children().attr('href') == url){
$(this).children().attr('class','text-sub');
}
})
var cart_jump = <?php $cache = cache('setting'); echo json_encode($cache['cart_jump'])?>;
$('input[type=submit]').bind('click',function(){
if($('[name=keyword]').val() == ''){
return false;
}
})
</script>