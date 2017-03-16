<?php if(!defined('IN_APP')) exit('Access Denied');?>
<?php include template('toper','common');?>
<?php include template('header','common');?>
<!--商品详情-->
<script type="text/javascript" src="<?php echo __ROOT__ ?>template/default/statics/js/detail.js?v=<?php echo HD_VERSION;?>" ></script>
<script type="text/javascript" src="<?php echo __ROOT__ ?>template/default/statics/js/jquery.jqzoom.js?v=<?php echo HD_VERSION;?>" ></script>
<!--面包屑-->
<div class="container crumbs clearfix">
<p class="fl layout">
<i class="icon-crumbs"></i>
<?php echo catpos($goods['catid']);?><em>></em><?php echo $goods['sku_name'];?>
</p>
</div>
<?php echo runhook('detail_crumbs');?>
<!-- <div class="product-info container border border-gray-white clearfix"> -->
<div class="product-info container border-top clearfix">
<div class="preview">
<div id="spec-n">
<div class="jqzoom"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo $goods['img_list'][0]?>" width="338" height="338" /></div>
</div>
<div id="showDetails" style="display: none;">
<img src="<?php echo $goods['img_list'][0]?>" id="showImgBig" alt="" width="338" height="338" />
</div>
<div class="slider" id="spec-list">
<a href="javascript:;" class="slider-control border prev"><</a>
<a href="javascript:;" class="slider-control border next">></a>
<div class="slider-items">
<ul class="lh">
<?php foreach ($goods['img_list'] AS $list_img){?>
<li><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo $list_img;?>" width="50" height="50" /></li>
<?php }?>
</ul>
</div>
</div>
<div class="short-share padding-tb border-top clearfix">
<?php if($favorite) { ?>
<span class="button fl"><em class="text-gray">已收藏</em></span>
<?php } else { ?>
<a class="button collect-btn fl" data-skuid="<?php echo $goods['sku_id'];?>" data-name="<?php echo $good['sku_name'];?>" data-url="<?php echo urlencode($_SERVER['REQUEST_URI']);?>" data-price="<?php echo $goods['shop_price'];?>">收藏商品</a>
<?php } ?>
<?php echo runhook('detail_behind_album');?>
</div>
</div>
<div class="item-info">
<div class="name">
<h1 class="text-big text-ellipsis" data-skuid="<?php echo $goods['sku_id'];?>"><?php echo $goods['sku_name'];?></h1>
<p class="memo" style="color:<?php echo $goods['style'] ? $goods['style'] : '#000'?>"><?php echo $goods['subtitle'];?></p>
</div>
<div class="summary">
<p class="market-price">&emsp;市场价：<b>￥<?php echo $goods['market_price'];?></b></p>
<p class="price-info <?php if(isset($goods['prom_time']) && $goods['prom_time'] > 0) { ?>show<?php } else { ?>hidden<?php } ?>"><!--有促销时显示此处-->
<span class="promo-price">&emsp;促销价：<em class="text-mix"><?php echo $goods['prom_price'];?></em></span>
<span class="margin-lr">原价：￥<?php echo $goods['shop_price'];?></span>
<span class="timer" data-time="<?php echo $goods['prom_time'];?>">还剩<em class="d text-mix">00</em>天<em class="h text-mix">00</em>小时<em class="m text-mix">00</em>分<em class="s text-mix">00</em>秒结束</span>
</p>
<?php echo runhook('detail_goods_prom_info');?>
<p class="price-info <?php if(isset($goods['prom_time']) && $goods['prom_time'] > 0) { ?>hidden<?php } else { ?>show<?php } ?>"><!--默认状态-->
<span class="promo-price">&emsp;销售价：<em class="text-mix"><?php echo $goods['prom_price'];?></em></span>
<?php echo runhook('detail_price_right');?>
</p>
<!--<div>-->
<!--当商品价格低于<input type="text" name="price">时通知您-->
<!--<input type="email" name="email" placeholder="邮箱">-->
<!--<input type="text" name="phone" placeholder="手机号">-->
<!--<input type="hidden" name="goods_id" value="<?php echo $goods['sku_id'];?>">-->
<!--<input type="button" id="btn_sure" value="确定">-->
<!--</div>-->
<!--<script>-->
<!--$(function () {-->
<!--//$('.promo-price em.text-mix').html()-->
<!--$('input[name=goods_id]').val("<?php echo $goods['id'];?>");-->
<!--$('input[name=price]').val("<?php echo $goods['prom_price'];?>");-->
<!--});-->
<!--$('#btn_sure').click(function () {-->
<!--$.post('plugin.php?id=price_notice:noticeservlet',-->
<!--{-->
<!--'price':$('input[name=price]').val(),-->
<!--'email':$('input[name=email]').val(),-->
<!--'phone':$('input[name=phone]').val()-->
<!--},function (res) {-->
<!--console.log(res);-->
<!--})-->
<!--});-->
<!--</script>-->
<?php if($goods[prom_type] == 'goods' && $goods[prom_id] > 0) { ?>
<div class="promotion-dom">
<div class="clearfix promotion-box">
<span class="promotion-msg fl">促销信息 :</span>
<div class="fl promotion-wrap">
<ul><?php if(is_array($goods_proms[rules])) foreach($goods_proms[rules] as $rule) { ?><li class="clearfix">
<span class="promotion-font text-white fl" ><?php echo $rule['subtitle'];?></span>
<span class="text-dot margin-left promotion-detail fl"><?php echo $rule['title'];?></span>
</li>
<?php } ?>
</ul>
</div>
<span class="text-gray margin-small-top promotion-num ">共<?php echo $counts;?>项促销<i class="promotion-icon"></i></span>
</div>
</div>
<?php } ?>
</div>
<div id="choose" class="choose-wrap layout clearfix"><!--error为提示未选择规格属性-->
<?php foreach($goods['specs'] AS $specs):?>
<?php if(!is_null($specs['id'])) { ?>
<div class="dl">
<div class="dt"><?php echo $specs['name'];?>：</div>
<div class="dd item-check">
<?php foreach(explode(',',$specs['value']) as $k => $spec):?>
<div class="item" data-id="<?php echo $specs['id'];?>" data-name="<?php echo $specs['name'];?>" data-value="<?php echo $spec;?>">
<i></i>
<?php if($specs[style] == 1) { ?>
<?php $color = explode(',',$specs['color'])?>
<div class="square-check" title="<?php echo $spec;?>" style="background-color:<?php echo $color[$k];?>;"></div>
<?php } elseif($specs[style] == 2) { ?>
<?php $img = explode(',',$specs['img'])?>
<div class="square-check pic-center" title="<?php echo $spec;?>"><img title="<?php echo $spec;?>" src="<?php echo $img[$k];?>"></div>
<?php } else { ?>
<a href="javascript:;" title=""><?php echo $spec;?></a>
<?php } ?>
</div>
<?php endforeach?>
</div>
</div>
<?php } ?>
<?php endforeach?>
<div id="adjust" class="layout">
<div class="dt">数量：</div>
<div class="dd adjust">
<div class="adjust-wrap">
<a class="adjust-control adjust-reduce disabled border" id="adjust-reduce" href="javascript:;">-</a>
<input class="adjust-input input border bg-white padding-none" type="text" value="1" data-min="1" data-max="<?php echo $goods['number'];?>" />
<a class="adjust-control adjust-add border" id="adjust-add" href="javascript:;">+</a>
</div>
<?php echo runhook('detail_goods_num_info');?>
<span class="text-lh-large margin-large-left">库存剩余：<?php echo $goods['number'];?> 件</span><!--有促销时显示此处-->
</div>
</div>
<p class="choose-tips text-sub">请选择您想要的商品信息</p>
</div>
<?php if($goods[number] > 0 && $goods[status] == 1) { ?>
<a class="fl cart-btn" data-event="buy_now" data-skuids="<?php echo $goods['sku_id'];?>" href="javascript:;">立即购买</a>
<div class="item-btn button border-none" data-event="cart_add" data-skuids="<?php echo $goods['sku_id'];?>">
<i class="icon-cart">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M 143.123 602.62 l 194.878 -1.892 l 47.3 -342.457 l 348.132 -1.892 l 49.193 196.77 h 98.384 l -51.083 -293.262 l -541.118 1.892 l -49.192 342.456 h -96.493 Z M 300.161 40.689 c 0 0 0 0 0 0 c 0 -42.842 34.731 -77.572 77.572 -77.572 c 42.841 0 77.573 34.731 77.573 77.572 c 0 0 0 0 0 0 c 0 42.841 -34.731 77.573 -77.573 77.573 c -42.841 0 -77.572 -34.731 -77.572 -77.573 Z M 667.213 40.689 c 0 -42.842 34.731 -77.572 77.573 -77.572 s 77.572 34.731 77.572 77.572 c 0 42.841 -34.731 77.573 -77.572 77.573 c -42.841 0 -77.573 -34.731 -77.573 -77.573 Z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg>
</i>加入购物车
</div>
<?php } elseif($goods[status] != 1) { ?>
<div class="padding-big-left layout">
<a class="fl margin-big-left w25 button bg-gray" href="javascript:;">
<i class="margin-right icon-cart va-m">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M 143.123 602.62 l 194.878 -1.892 l 47.3 -342.457 l 348.132 -1.892 l 49.193 196.77 h 98.384 l -51.083 -293.262 l -541.118 1.892 l -49.192 342.456 h -96.493 Z M 300.161 40.689 c 0 0 0 0 0 0 c 0 -42.842 34.731 -77.572 77.572 -77.572 c 42.841 0 77.573 34.731 77.573 77.572 c 0 0 0 0 0 0 c 0 42.841 -34.731 77.573 -77.573 77.573 c -42.841 0 -77.572 -34.731 -77.572 -77.573 Z M 667.213 40.689 c 0 -42.842 34.731 -77.572 77.573 -77.572 s 77.572 34.731 77.572 77.572 c 0 42.841 -34.731 77.573 -77.572 77.573 c -42.841 0 -77.573 -34.731 -77.573 -77.573 Z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg></i>商品已下架
</a>
</div>
<?php } else { ?>
<div class="padding-big-left layout">
<a class="fl margin-big-left w25 button bg-gray" href="javascript:;">
<i class="margin-right icon-cart va-m">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M 143.123 602.62 l 194.878 -1.892 l 47.3 -342.457 l 348.132 -1.892 l 49.193 196.77 h 98.384 l -51.083 -293.262 l -541.118 1.892 l -49.192 342.456 h -96.493 Z M 300.161 40.689 c 0 0 0 0 0 0 c 0 -42.842 34.731 -77.572 77.572 -77.572 c 42.841 0 77.573 34.731 77.573 77.572 c 0 0 0 0 0 0 c 0 42.841 -34.731 77.573 -77.573 77.573 c -42.841 0 -77.572 -34.731 -77.572 -77.573 Z M 667.213 40.689 c 0 -42.842 34.731 -77.572 77.573 -77.572 s 77.572 34.731 77.572 77.572 c 0 42.841 -34.731 77.573 -77.572 77.573 c -42.841 0 -77.573 -34.731 -77.573 -77.573 Z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg></i>商品已售罄
</a>
</div>
<?php } ?>
<?php echo runhook('detail_goods_operate');?>
</div>
<!--右边外栏-->
<div class="product-ext fr">
<div class="item-title">商品概况</div>
<div class="ext-content border-bottom clearfix">
<div class="item-double-txt">
<em class="text-mix"><?php echo $goods['sales'] ? $goods['sales'] : 0?></em>
<p>商品销量</p>
</div>
<div class="item-double-txt">
<em class="text-sub comment-total">0</em>
<p>商品评论</p>
</div>
<div class="item-double-txt last">
<em class="consult-total">0</em>
<p>商品咨询</p>
</div>
<?php $data = runhook('detail_goods_info');?>
</div>
<?php echo runhook('detail_info_between_lists');?>
<div class="item-title">看了又看</div>
<div class="ext-content padding clearfix">
<div class="ext-show">
<div class="ext-con-wrap">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('order'=>'rand()'), array('limit'=>'6','cache'=>'f684f291a41f3a69fb786cfe32e19cbf,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><div class="ext-con-box">
<a href="<?php echo url('detail',array('sku_id'=>$r['sku_id']));?>" title="<?php echo $r['name'];?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb']);?>" width="90" height="90" /></a>
<p>￥<?php echo $r['prom_price']?></p>
</div>
<?php } ?>

</div>
</div>
</div>
</div>
</div>
<!--组合商品-->
<?php $saless = runhook('detail_brief_between_detail');?>
<?php if($goods[number] > 0) { ?>
<?php
	$taglib_promotion_prom_group = new taglib('promotion','prom_group');
	$data = $taglib_promotion_prom_group->lists(array('sku_id'=>$_GET[sku_id]), array('limit'=>'20','cache'=>'80e159f0d523e10ed7defc9da16f5b59,3600'));
?>
<!-- <div class="container fitting-suit border border-gray-white"> -->
<div class="container fitting-suit">
<ul class="text-big text-lh-large padding-bottom p-tab-first clearfix">
<?php if($data) { ?><li class="tab-first-lists current" ><a href="javascript:;">组合销售</a></li><?php } ?>
<?php if($saless) { ?><li class="tab-first-lists" ><a href="javascript:;">组合促销</a></li><?php } ?>
</ul>
</div>

<?php if($data) { ?>
<!-- <div class="container border border-gray-white border-top-none prom_group combine_way"> -->
<div class="container prom_group combine_way border border-gray-white radius">
<ul class="tab p-tab-second padding-big-left padding-big-top clearfix"><?php if(is_array($data)) foreach($data as $k => $group) { ?><li class="ui-switchtab-item tab-second-lists" data-id="<?php echo $group['id'];?>"><a href="javascript:;"><?php echo $k;?></a></li>
<span class="spacer fl"></span>
<?php } ?>
</ul><?php if(is_array($data)) foreach($data as $group) { ?><div class="ui-switch-panel ui-selected clearfix hidden group_list" data-id="<?php echo $group['id'];?>">
<div class="current-goods">
<div class="parts-item">
<div class="parts-item-goods">
<div class="pic">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$_GET['sku_id']));?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo $goods['thumb'];?>" width="150" height="150" /></a>
</div>
<p class="text-ellipsis"><a href=""><?php echo $goods['sku_name'];?></a></p>
<p class="text-mix">￥<?php echo $goods['prom_price']?></p>
</div>
<i class="icon-and"></i>
</div>
</div>
<div class="fitting-suit-items slider clearfix">
<a href="javascript:;" class="slider-control border prev">&lt;</a>
<a href="javascript:;" class="slider-control border next">&gt;</a>
<div class="slider-items">
<ul class="lh"><?php if(is_array($group['group'])) foreach($group['group'] as $k => $sku) { ?><li class="parts-item" >
<div class="parts-item-goods">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$sku['sku_id']));?>">
<div class="pic">
<img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo $sku['thumb'];?>" width="150" height="150" />
<?php if($sku['number'] < 1) { ?>
<div class="no-number"><img src="template/default/statics/images/number_over.png" /><span></span></div>
<?php } ?>
</div>
</a>
<div class="check-items">
<?php if($sku['number'] > 0) { ?>
<input class="check-item " type="checkbox" checked="checked" />
<?php } else { ?>
<input class="check-item " type="checkbox" disabled="disabled" />
<?php } ?>
<p class="text-ellipsis"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$sku['sku_id']));?>"><?php echo $sku['sku_name'];?></a></p>
<p class="text-mix">￥<em data-id="<?php echo $sku['sku_id'];?>" data-market="<?php echo $sku['market_price'];?>"><?php echo $sku['prom_price']?></em></p>
</div>
</div>
<?php if($k != count($group['group'])-1) { ?>
<i class="icon-and"></i>
<?php } ?>
</li>
<?php } ?>
</ul>
</div>
</div>
<div class="fitting-suit-info fr">
<span class="padding-big-bottom border-bottom border-dotted margin-big-bottom">商品总价：<em class="text-big text-mix">￥<em class="price"><?php echo $goods['prom_price']?></em></em></span>
<span class="text-lh">已选商品：<em class="total_num">1</em>件</span>
<span class="text-lh">参考价格：<em class="delete-line">￥<em class="market"><?php echo $goods['market_price'];?></em></em></span>
<div class="item-btn button border-none" data-event="cart_add" data-skuids="<?php echo $goods['sku_id'];?>">
<i class="icon-cart">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M 143.123 602.62 l 194.878 -1.892 l 47.3 -342.457 l 348.132 -1.892 l 49.193 196.77 h 98.384 l -51.083 -293.262 l -541.118 1.892 l -49.192 342.456 h -96.493 Z M 300.161 40.689 c 0 0 0 0 0 0 c 0 -42.842 34.731 -77.572 77.572 -77.572 c 42.841 0 77.573 34.731 77.573 77.572 c 0 0 0 0 0 0 c 0 42.841 -34.731 77.573 -77.573 77.573 c -42.841 0 -77.572 -34.731 -77.572 -77.573 Z M 667.213 40.689 c 0 -42.842 34.731 -77.572 77.573 -77.572 s 77.572 34.731 77.572 77.572 c 0 42.841 -34.731 77.573 -77.572 77.573 c -42.841 0 -77.573 -34.731 -77.573 -77.573 Z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg>
</i>组合购买
</div>
</div>
</div>
<?php } ?>
</div>
<?php } ?>

<?php } ?>



<?php if($saless) { ?>
<div class="container border border-gray-white hidden border-top-none prom_group combine_way">
<ul class="tab p-tab-trigger-wrap p-tab-second clearfix margin-big-bottom"><?php if(is_array($saless)) foreach($saless as $k => $sales) { ?><li class="ui-switchtab-item tab-second-lists" data-id="<?php echo $sales['id'];?>"><a class="sales_name_list" href="javascript:;"><?php echo $k;?></a></li>
<span class="spacer"></span>
<?php } ?>
</ul><?php if(is_array($saless)) foreach($saless as $sales) { ?><div class="ui-switch-panel ui-selected clearfix hidden group_list" data-id="<?php echo $sales['id'];?>">
<div class="current-goods">
<div class="parts-item">
<div class="parts-item-goods">
<div class="pic">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$_GET['sku_id']));?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo $goods['thumb'];?>" width="150" height="150" /></a>
</div>
<p class="text-ellipsis"><a href=""><?php echo $goods['sku_name'];?></a></p>
<p class="text-mix">￥<?php echo $goods['prom_price']?></p>
</div>
</div>
</div>
<div class="fitting-suit-items slider clearfix">
<a href="javascript:;" class="slider-control border prev">&lt;</a>
<a href="javascript:;" class="slider-control border next">&gt;</a>
<div class="slider-items">
<ul class="lh"><?php if(is_array($sales['sales'])) foreach($sales['sales'] as $k => $sku) { ?><li class="parts-item" >
<div class="parts-item-goods">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$sku['sku_id']));?>">
<div class="pic">
<img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo $sku['thumb'];?>" width="150" height="150" />
<?php if($sku['number'] < 1) { ?>
<div class="no-number"><img src="template/default/statics/images/number_over.png" /><span></span></div>
<?php } ?>
</div>
</a>
<div class="check-items ">
<p class="text-ellipsis"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$sku['sku_id']));?>"><?php echo $sku['sku_name'];?></a></p>
<p class="text-mix">￥<em data-id="<?php echo $sku['sku_id'];?>" data-market="<?php echo $sku['market_price'];?>"><?php echo $sku['prom_price']?></em></p>
</div>
</div>
<?php if($k != count($sales['sales'])+1) { ?>
<i class="icon-and"></i>
<?php } ?>
</li>
<?php } ?>
</ul>
</div>
</div>
<div class="fitting-suit-info fr">
<span class="padding-bottom border-bottom border-dotted">商品总价：<em class="text-big text-mix">￥<?php echo $sales['new_goods']['new_price'];?></em></span>
<span class="text-lh">参考价格：<em class="delete-line">￥<?php echo $sales['new_goods']['sales_price'];?></em></span>

<div class="item-btn sales-btn button border-none" data-event="cart_add" data-skuids="<?php echo $goods['sku_id'];?>">
<i class="icon-cart">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M 143.123 602.62 l 194.878 -1.892 l 47.3 -342.457 l 348.132 -1.892 l 49.193 196.77 h 98.384 l -51.083 -293.262 l -541.118 1.892 l -49.192 342.456 h -96.493 Z M 300.161 40.689 c 0 0 0 0 0 0 c 0 -42.842 34.731 -77.572 77.572 -77.572 c 42.841 0 77.573 34.731 77.573 77.572 c 0 0 0 0 0 0 c 0 42.841 -34.731 77.573 -77.573 77.573 c -42.841 0 -77.572 -34.731 -77.572 -77.573 Z M 667.213 40.689 c 0 -42.842 34.731 -77.572 77.573 -77.572 s 77.572 34.731 77.572 77.572 c 0 42.841 -34.731 77.573 -77.572 77.573 c -42.841 0 -77.573 -34.731 -77.573 -77.573 Z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg>
</i>组合购买
</div>
</div>
</div>
<?php } ?>
</div>
<?php } ?>

<!--商品详情-->
<div class="container padding-big-top item-two-column clearfix">
<div class="left fr">
<div class="product-ext border border-gray-white clearfix">
<div class="item-title">浏览记录</div>
<div class="ext-content padding clearfix">
<div class="ext-show">
<div class="ext-con-wrap">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->history(array(), array('limit'=>'6','cache'=>'ed996fd0eba888a22a7e196b99af759c,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><div class="ext-con-box">
<a href="<?php echo url('detail',array('sku_id'=>$r['sku_id']));?>" title="<?php echo $r['sku_name'];?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb']);?>" width="90" height="90" /></a>
<p>￥<?php echo $r['prom_price']?></p>
</div>
<?php } ?>

</div>
</div>
</div>
</div>
<?php echo runhook('detail_behind_history');?>
</div>

<div id="pro-detail-right" class="fl right">

<div id="item-bar">
<ul class="tab p-tab-trigger-wrap">
<li class="ui-switchtab-item current"><a href="javascript:;">商品详情</a></li>
<li class="ui-switchtab-item"><a href="javascript:;" class="total-comment">商品评价(0)</a></li>
<li class="ui-switchtab-item" id="consult"><a href="javascript:;">商品咨询(0)</a></li>
<?php $cache = cache('setting');?>
<?php if($cache[invoice_vat_enabled] == 1) { ?>
<?php
	$taglib_order_order = new taglib('order','order');
	$data = $taglib_order_order->records(array('sid'=>$goods[spu_id],'isspu'=>'true'), array('limit'=>'8','cache'=>'648a96ed4f3b00083e1c942659bde7cf,3600','page'=>$_GET[page],'pagefunc'=>'pages'));
	$pages = $taglib_order_order->pages;
?>
<li class="ui-switchtab-item"><a href="javascript:;">销售记录(<?php echo $data['count'];?>)</a></li>

<?php } ?>
<li class="fl">
<?php if($goods['number'] > 0) { ?>
<div class="item-btn button border-none" data-event="cart_add" data-skuids="<?php echo $goods['sku_id'];?>">
<i class="icon-cart">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M 143.123 602.62 l 194.878 -1.892 l 47.3 -342.457 l 348.132 -1.892 l 49.193 196.77 h 98.384 l -51.083 -293.262 l -541.118 1.892 l -49.192 342.456 h -96.493 Z M 300.161 40.689 c 0 0 0 0 0 0 c 0 -42.842 34.731 -77.572 77.572 -77.572 c 42.841 0 77.573 34.731 77.573 77.572 c 0 0 0 0 0 0 c 0 42.841 -34.731 77.573 -77.573 77.573 c -42.841 0 -77.572 -34.731 -77.572 -77.573 Z M 667.213 40.689 c 0 -42.842 34.731 -77.572 77.573 -77.572 s 77.572 34.731 77.572 77.572 c 0 42.841 -34.731 77.573 -77.572 77.573 c -42.841 0 -77.573 -34.731 -77.573 -77.573 Z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg>
</i>加入购物车
</div>
<?php echo runhook('detail_tab_right');?>
<?php } else { ?>
<div class="fr margin-right text-lh-large padding-top text-main">商品已售罄</div>
<?php } ?>
</li>
<?php if($qr_code[0]>0) { ?>
<li class="fr qrcode-box">
<span class="qrcode-top">
<span class="text">手机购买</span>
<span class="qranchor icon-qrcode"></span>
</span>
<div class="qrcode-con hidden">
<div class="qrcode" data-model='qrcode']></div>
</div>
<div class="border qrcode-border hidden"></div>
</li>
<?php } ?>
</ul>
</div>
<div class="ui-switch-panel ui-selected padding border-left border-right border-bottom border-gray-white">
<ul class="p-parameter-list clearfix">
<li title="<?php echo $goods['name'];?>">商品名称：<?php echo $goods['name'];?></li>
<li title="<?php echo $goods['brand']['name'];?>">品牌：<a href="<?php echo url('brand_list',array('id'=>$goods[brand_id]));?>"><?php echo $goods['brand']['name'];?></a></li>
<?php foreach($goods['attrs'] AS $attr):?>
<li title="<?php echo $attr;?>"><?php echo $attr;?></li>
<?php endforeach?>
</ul>
</div>
<div class="detail-content-wrap">
<p><?php echo $goods['content'];?></p>
</div>
<!-- <div id="pro-detail-comment" class="margin-big-top border border-gray-white product-detail-panel item-blue-top"> -->
<div id="pro-detail-comment" class="product-detail-panel item-blue-top margin-large-top">
<ul class="tab p-tab-trigger-wrap title margin-bottom">
<li class="ui-switchtab-item"><a href="javascript:;">商品评价</a></li>
</ul>
<div class="ui-switch-panel ui-selected padding-tb border border-gray-white clearfix">
<div class="text-center goods-comment-info">
<span class="h2 text-mix" id="positive-nb">100%</span>
<span>好评度</span>
</div>
<ul class="progress">
<li>
<span class="progress-label">好评：</span>
<div class="progress-bar positive-bar" title="0%"><div class="bg-mix" style="width:0%"></div></div>
</li>
<li>
<span class="progress-label">中评：</span>
<div class="progress-bar neutral-bar" title="0%"><div class="bg-mix" style="width:0%"></div></div>
</li>
<li>
<span class="progress-label">差评：</span>
<div class="progress-bar negative-bar" title="0%"><div class="bg-mix" style="width:0%"></div></div>
</li>
</ul>
<div class="goods-comment-btn border-left border-gray-white fr" data-url="<?php echo urlencode($_SERVER['REQUEST_URI']);?>">
<p class="text-center">购买商品后才可评价</p>
<div class="item-btn button border-none">
<i class="icon-message">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M309.56394 344.948725m-50.542116 0a49.391 49.391 0 1 0 101.084233 0 49.391 49.391 0 1 0-101.084233 0ZM511.734452 395.490841c-27.913746 0-50.544163-22.630417-50.544163-50.542116 0-27.912723 22.630417-50.542116 50.544163-50.542116 27.909653 0 50.54314 22.629393 50.54314 50.542116C562.277592 372.860425 539.644105 395.490841 511.734452 395.490841zM890.80186 673.472993 132.664997 673.472993c-27.9117 0-50.542116-22.630417-50.542116-50.54314l0-555.965328c0-27.9117 22.630417-50.541093 50.542116-50.541093l263.612886 0 102.177124-152.553465 26.542516 0 102.184287 152.553465L890.80186 16.423433c27.910677 0 50.541093 22.62837 50.541093 50.541093L941.342953 622.929854C941.34193 650.842577 918.712537 673.472993 890.80186 673.472993zM890.80186 92.236096c0-13.966083-11.316743-25.270547-25.270547-25.270547L661.03892 66.965549l0.86981 1.300622-59.390645-0.496304-1.25048-0.334621-0.286526-0.468674-30.839379-50.541093-59.06421-96.79044-58.20361 96.79044-30.393217 50.541093-0.005117 0.010233-60.438511 0.574075 0.38988-0.583285L157.93759 66.967596c-13.95585 0-25.272593 11.304464-25.272593 25.270547L132.664997 597.659307c0 13.95585 11.316743 25.270547 25.272593 25.270547l707.593723 0c13.954827 0 25.270547-11.314697 25.270547-25.270547L890.80186 92.236096zM713.902918 395.490841c-27.9117 0-50.544163-22.630417-50.544163-50.542116 0-27.912723 22.63144-50.542116 50.544163-50.542116 27.910677 0 50.542116 22.629393 50.542116 50.542116C764.444011 372.860425 741.813594 395.490841 713.902918 395.490841z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg>
</i>评价商品
</div>
</div>
</div>
</div>
<div id="pro-all-comment" class="margin-large-top product-detail-panel">
<ul class="tab p-tab-trigger-wrap" id="comment_tab">
<li class="ui-switchtab-item current border-gray-white"><a href="javascript:;" data-load="false">全部评价</a></li>
<li class="ui-switchtab-item" data-mood="positive"><a href="javascript:;" data-load="false">好评(<?php echo $count['positive'];?>)</a></li>
<li class="ui-switchtab-item" data-mood="neutral"><a href="javascript:;" data-load="false">中评(<?php echo $count['neutral'];?>)</a></li>
<li class="ui-switchtab-item" data-mood="negative"><a href="javascript:;" data-load="false">差评(<?php echo $count['negative'];?>)</a></li>
</ul>
<div class="tab-tag p-tab-con-wrap border border-gray-white consult-tag">
<div class="tag selected padding clearfix default_comment">
<ul class="comment-area layout">
</ul>
<p class="goods-item-no-info">暂无商品评价</p>
<div class="paging margin-top padding-tb clearfix comment_pages">
</div>
</div>
<div class="tag padding positive">
<ul class="comment-area layout">
</ul>
<p class="goods-item-no-info">暂无商品评价</p>
<div class="paging margin-top padding-tb clearfix comment_pages">
</div>
</div>
<div class="tag padding neutral">
<ul class="comment-area layout">
</ul>
<p class="goods-item-no-info">暂无商品评价</p>
<div class="paging margin-top padding-tb clearfix comment_pages">
</div>
</div>
<div class="tag padding negative">
<ul class="comment-area layout">
</ul>
<p class="goods-item-no-info">暂无商品评价</p>
<div class="paging margin-top padding-tb clearfix comment_pages">
</div>
</div>
</div>
</div>
<div id="pro-goods-consult" class="margin-large-top product-detail-panel item-blue-top">
<ul class="tab p-tab-trigger-wrap">
<li class="ui-switchtab-item current"><a href="javascript:;">商品咨询</a></li>
</ul>
<div class="ui-switch-panel ui-selected padding border border-gray-white clearfix">
<dl class="lh20 goods-consult-info">
<dt>温馨提示：</dt>
<dd class="text-gray">
<span>因厂家更改商品包装、场地、附配件等不做提前通知，且每位咨询者购买、提问时间等不同。为此，其他网友的咨询仅供参考！给您带来的不便还请谅解，谢谢！</span>
</dd>
</dl>
<div class="goods-consult-right border-left border-gray-white">
<a href="<?php echo url('goods/consult/add',array('sku_id'=>$_GET[sku_id],'catid'=>$goods['catid']));?>">
<div class="item-btn button border-none">
<i class="icon-message">
<svg width="14" height="14" viewBox="0 0 1024 1024">
<g>
<path d="M309.56394 344.948725m-50.542116 0a49.391 49.391 0 1 0 101.084233 0 49.391 49.391 0 1 0-101.084233 0ZM511.734452 395.490841c-27.913746 0-50.544163-22.630417-50.544163-50.542116 0-27.912723 22.630417-50.542116 50.544163-50.542116 27.909653 0 50.54314 22.629393 50.54314 50.542116C562.277592 372.860425 539.644105 395.490841 511.734452 395.490841zM890.80186 673.472993 132.664997 673.472993c-27.9117 0-50.542116-22.630417-50.542116-50.54314l0-555.965328c0-27.9117 22.630417-50.541093 50.542116-50.541093l263.612886 0 102.177124-152.553465 26.542516 0 102.184287 152.553465L890.80186 16.423433c27.910677 0 50.541093 22.62837 50.541093 50.541093L941.342953 622.929854C941.34193 650.842577 918.712537 673.472993 890.80186 673.472993zM890.80186 92.236096c0-13.966083-11.316743-25.270547-25.270547-25.270547L661.03892 66.965549l0.86981 1.300622-59.390645-0.496304-1.25048-0.334621-0.286526-0.468674-30.839379-50.541093-59.06421-96.79044-58.20361 96.79044-30.393217 50.541093-0.005117 0.010233-60.438511 0.574075 0.38988-0.583285L157.93759 66.967596c-13.95585 0-25.272593 11.304464-25.272593 25.270547L132.664997 597.659307c0 13.95585 11.316743 25.270547 25.272593 25.270547l707.593723 0c13.954827 0 25.270547-11.314697 25.270547-25.270547L890.80186 92.236096zM713.902918 395.490841c-27.9117 0-50.544163-22.630417-50.544163-50.542116 0-27.912723 22.63144-50.542116 50.544163-50.542116 27.910677 0 50.542116 22.629393 50.542116 50.542116C764.444011 372.860425 741.813594 395.490841 713.902918 395.490841z" transform="translate(0 812) scale(1 -1)" fill="#fff"></path>
</g>
</svg>
</i>我要咨询
</div>
</a>
</div>
</div>
</div>
<div id="pro-all-consult" class="margin-large-top border-gray-white product-detail-panel item-blue-top pro-border-top-gray">
<ul class="tab p-tab-trigger-wrap">
<li class="ui-switchtab-item current"><a href="javascript:;">全部咨询</a></li>
</ul>
<?php
	$taglib_goods_consult = new taglib('goods','consult');
	$data = $taglib_goods_consult->lists(array('spu_id'=>$goods[spu_id]), array('limit'=>'5','cache'=>'d274ddc49f741c39e33fcd9a9cc9d052,3600'));
?>
<?php if(empty($data)) { ?>
<div class="ui-switch-panel ui-selected padding border border-gray-white">
<p class="goods-item-no-info">暂无商品咨询</p>
</div>
<?php } else { ?>
<div class="layout consult-lists">
<div class="padding clearfix"><?php if(is_array($data)) foreach($data as $r) { ?><div class="list">
<p class="clearfix"><span class="user-name"><?php echo $r['username'] ? $r['username'] : '游客'?></span><span class="fr text-gray"><?php echo date("Y-m-d H:i:s",$r['dateline']);?></span></p>
<p>咨询内容：<span class="text-sub"><?php echo $r['question'];?></span></p>
<?php if($r['reply_content']) { ?>
<p>卖家回复： <span class="text-mix"><?php echo $r['reply_content'];?></span></p>
<?php } ?>
</div>
<?php } ?>
</div>
</div>
<?php } ?>

<?php
	$taglib_goods_consult = new taglib('goods','consult');
	$data = $taglib_goods_consult->count(array('spu_id'=>$goods[spu_id]), array('limit'=>'20','cache'=>'ac1ce350a300aa6c95a469e330a817dc,3600'));
?>
<?php if($data>5) { ?>
<div class="margin-bottom padding-right layout text-right">
共<strong><?php echo $data;?></strong>条&nbsp;&nbsp;
<a class="text-sub text-underline" href="<?php echo url('goods/consult/add',array('sku_id'=>$_GET[sku_id],'catid'=>$goods['catid']));?>">浏览所有咨询信息&gt;&gt;</a>
</div>
<?php } ?>
<script type="text/javascript">
var num = <?php echo $data?>;
$('#consult').find('a').html('商品咨询('+(num)+')');
</script>

</div>
<?php if($cache[invoice_vat_enabled] == 1) { ?>
<div id="pro-buy-record" class="margin-large-top product-detail-panel">
<ul class="tab p-tab-trigger-wrap">
<li class="ui-switchtab-item current"><a class="text-sub" href="javascript:;" id="record_btn">购买记录(0)</a></li>
</ul>
<div class="ui-switch-panel ui-selected padding border border-gray-white">
<div class="table text-ellipsis buy-record">
<div class="tr strong">
<div class="td w20">买家</div>
<div class="td w20">商品价格</div>
<div class="td w10">数量</div>
<div class="td w20">付款时间</div>
<div class="td w30">款式和型号</div>
</div>
<div id="order_goods"></div>
<div class="paging margin-top padding-tb clearfix record-pages comment_pages">
</div>
</div>

<!--<p class="goods-item-no-info">暂无购买记录</p>-->
</div>
</div>
<?php } ?>
<?php echo runhook('detail_extra_detail');?>
</div>
</div>
</div>
<?php include template('toolbar','common');?>
<?php include template('footer','common');?>
<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/jquery.qrcode.min.js?v=<?php echo HD_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/goods/jquery.md5.js?v=<?php echo HD_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo __ROOT__ ?>statics/js/jquery.lazyload.js?v=<?php echo HD_VERSION;?>" ></script>

<script>

$(function(){
$(".ishare").live('click',function(){
if($(".share-btn").hasClass('hidden')){
$(".share-btn").removeClass("hidden");
$("#socialShare").socialShare({
content: '',
url:'',
titile:''
});
}else{
$(".share-btn").addClass("hidden");
}

})
})
$(".timer").timer();
$('.goods-comment-btn').bind('click',function(){
var url_forward = $(this).data('url');
var comment = "<?php echo url('comment/index/ajax_comment_index')?>";
$.get(comment,{url_forward:url_forward},function(ret){
if(ret.status == 0) {
$.tips({
icon:'error',
content:ret.message,
callback:function() {
window.location.href = ret.referer;
}
});
}else{
window.location.href = ret.referer;
}
},'json')
})
//初始货品
var product_json = <?php echo json_encode($goods['sku_arr'])?>;
var shop_price = "<?php echo $goods['prom_price']?>";
var spec_id = "<?php echo $goods['spec_id']?>";
var sku_obj = <?php echo ($goods['spec']) ? $goods['spec'] : "[]";?>;
var sku_json = "<?php echo $goods['spec_str']?>";
var sku_url = "<?php echo url('goods/index/detail')?>";
var list_url = "<?php echo url('goods/index/lists',array('id'=>$goods['catid']))?>";
var comment_url = "<?php echo url('comment/index/ajax_comment')?>";
$(function(){
var lists = '<div class="hd-toolbar-tab hd-tbar-tab-backlist">'
+		'<a href="'+ list_url +'"><i class="tab-ico"></i></a>'
+		'</div>';
$('.hd-toolbar-footer').append(lists);
$(".fr li a").attr('data-ajax','true');
var $a=$("#comment_tab").find('a').eq(0);

ajax_record(1);
ajax_comment(1,$a);

var seecont_url="<?php echo url('goods/consult/ajax_cont')?>";
$.get(seecont_url,{spu:<?php echo $goods['spu_id']?>},function(result){
$(".consult-total").html(result);
},'json');

var $len=$(".product-info .lh li").length;
if($len<4){
$(".product-info .slider .next").addClass("disabled");
}
})
function ajax_comment(page,that){
var page = page || 1;
var spu_id = <?php echo $goods['spu_id']?>;
var mood = $("#comment_tab").find("li.current").data("mood");
var html = '';
$.post(comment_url,{spu_id:spu_id,mood:mood,page:page},function(ret){
if(ret){
$(that).data('load',true);
if(ret.count > 0){
var positive = <?php echo $count['positive']?>;
var neutral = <?php echo $count['neutral']?>;
var negative = <?php echo $count['negative']?>;
$.each(ret.lists,function(i,item){
var imgs = img_view = reply_content = ''
$.each(item.imgs,function(index,value){
imgs += '<li><a class="pic-center" href="#"><img src="' + value + '" /></a></li>';
})
if(imgs){
img_view = 		'<div class="comment-pic-view">'
+			'<div class="widget-carousel-content">'
+				'<ul class="widget-carousel-lists">'
+					imgs
+				'</ul>'
+			'</div>'
+			'<div class="widget-carousel-box pic-center"><a class="widget-carousel-link prev" href="#"></a><a class="widget-carousel-link next" href="#"></a></div>'
+		'</div>';
}else{
img_view = ''
}
if(item.reply_content){
reply_content = '<div class="m-t-15 comment-text text-mix">'
+		'<p>商家回复：' + item.reply_content + '</p>'
+	 '</div>'
}else{
reply_content = ''
}
html += '<li class="comment-list">'
+		'<div class="user-info-block">'
+			'<div class="avatar-wrapper">'
+				'<img src="'+item.avatar+'" />'
+			'</div>'
+			'<p class="name-wrapper">'+ item.username +'</p>'
+		'</div>'
+		'<div class="comment-view-wrapper">'
+			'<div class="time-area">'
+				'<span>评价时间：' + item._datetime + '</span>'
+			'</div>'
+			'<div class="comment-text">'
+				'<p>' + item.content + '</p>'
+			'</div>'
+			img_view
+			reply_content
+		'</div>'
+	'</li>';
})
switch(mood){
case 'positive':
$('.positive').find('.goods-item-no-info').remove();
$('.positive ul').html(html);
$('.positive').find('.comment_pages').html(ret.pages);
break;
case 'negative':
$('.negative').find('.goods-item-no-info').remove();
$('.negative ul').html(html);
$('.negative').find('.comment_pages').html(ret.pages);
break;
case 'neutral':
$('.neutral').find('.goods-item-no-info').remove();
$('.neutral ul').html(html);
$('.neutral').find('.comment_pages').html(ret.pages);
break;
default:
$('.default_comment').find('.goods-item-no-info').remove();
$('.default_comment ul').html(html);
$('.total-comment').text('商品评价('+ ret.count +')');
$('.comment-total').html(ret.count);
$('.default_comment').find('.comment_pages').html(ret.pages);
$('.neutral-bar').attr('title',(neutral/ret.count).toFixed(2)*100+'%').children('.bg-mix').css('width',(neutral/ret.count).toFixed(2)*100+'%');
$('.positive-bar').attr('title',(positive/ret.count).toFixed(2)*100+'%').children('.bg-mix').css('width',(positive/ret.count).toFixed(2)*100+'%');
$('.negative-bar').attr('title',(negative/ret.count).toFixed(2)*100+'%').children('.bg-mix').css('width',(negative/ret.count).toFixed(2)*100+'%');
if(ret.count != 0){
$('#positive-nb').text(parseInt((positive/ret.count)*100)+'%');
}
break;
}
$('.default_comment').find('.comment_pages').attr('data-func','ajax_comment');
}
}
},'json');
}
$('#comment_tab a').live('click', function() {
var data_load=$(this).data('load');
if(data_load){
return false;
}
ajax_comment(1,this);
});
$('#pro-all-comment .comment_pages a:not(".button")').live('click', function() {
var page = $.urlParam('page', $(this).attr('href'));
ajax_comment(page,this);
return false;
});
$('.record-pages .button').live('click', function() {
var $parent=$(this).closest(".comment_pages");
var this_page=$parent.find('.current span').html();
var go_page=$parent.find('input').val();
if(this_page == go_page){
return false;
}
ajax_record(go_page,this);
return false;
});

$('#pro-all-comment .button').live('click', function() {
var $parent=$(this).closest(".comment_pages");
var this_page=$parent.find('.current span').html();
var go_page=$parent.find('input').val();
if(this_page == go_page){
return false;
}
ajax_comment(go_page,this);
return false;
});


$.urlParam = function(name, url){
var url = url || window.location.href;
var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(url);
if(!results) return false;
return results[1] || 0;
}

function ajax_record(page){
var page = page || 1;
var url = '<?php echo url("order/cart/ajax_order_goods");?>';
var id = '<?php echo $goods["spu_id"];?>';
$.post(url,{id:id,page:page},function(ret){
var html = '';
if(ret.count > 0){
$.each(ret.lists,function(i,item){
html += '<div class="tr">'
+		'<div class="td w20">'+item.username+'</div>'
+		'<div class="td w20 strong"><span class="text-mix">￥'+ item.sku_price +'</span></div>'
+		'<div class="td w10">'+ item.buy_nums +'</div>'
+		'<div class="td w20">'+ item.dateline +'</div>'
+		'<div class="td w30">'
+		item._sku_spec
+		'</div>'
+	'</div>'
})
$('#order_goods').html(html);
$('.record-pages').html(ret.pages);
$('.record-pages').attr('data-func','ajax_record');
$('#record_btn').html('购买记录('+ ret.count +')');
}
},'json')
}
$('#pro-buy-record .record-pages a:not(".button")').live('click', function() {
var page = $.urlParam('page', $(this).attr('href'));
ajax_record(page);
return false;
})
if($('.prom_group').length > 0){
$('.prom_group').find('.tab li').eq(0).addClass('current');
$('.group_list').eq(0).removeClass('hidden');
$('.prom_group').find('.tab li').live('click',function(){
$(this).addClass('current').siblings('li').removeClass('current');
$('.group_list[data-id = "' + $(this).data('id') + '"]').removeClass('hidden').siblings('.group_list').addClass('hidden');
});

$(".group_list").each(function(){
if($(this).find(".lh .parts-item").length<=4){
$(this).find(".slider-control").addClass("hidden");
}
});

function getPrice(){
$(".group_list").each(function(){
var $this = $(this);
var $suit = $this.find(".fitting-suit-info");
var market_price = "<?php echo $goods['market_price']?>";
var number = 1,
market_price = parseFloat(market_price),
total_price = parseFloat(shop_price);
skuids = "<?php echo $goods['sku_id']?>";
$(this).find(".check-items").each(function(){
if($(this).children(".check-item").is(":checked")){
number += 1;
market_price +=  parseFloat($(this).find(".text-mix em").data("market"));
total_price +=  parseFloat($(this).find(".text-mix em").html());
skuids += ',' + $(this).find(".text-mix em").attr("data-id");
}
});

$suit.find(".total_num").html(number);
$suit.find(".price").html(total_price.toFixed(2));
$suit.find(".item-btn").attr("data-skuids",skuids);
$suit.find(".market").html(market_price.toFixed(2));
});
}
getPrice();

$('.fitting-suit-items .check-item').bind('click',function(){
getPrice();
})
}
//促销满额
$(".promotion-dom").hover(function(){
$(".promotion-box").addClass("promotion");
$(".promotion-icon").removeClass("promotion-icon").addClass("promotion-icon-up");
},function(){
$(".promotion-box").removeClass("promotion");
$(".promotion-icon-up").removeClass("promotion-icon-up").addClass("promotion-icon");
});


//组合促销
$(".p-tab-first .tab-first-lists").live('click',function(){
if(!$(this).hasClass('current')){
$(this).addClass('current').siblings().removeClass('current');
var index=$(this).index();
$(".combine_way").eq(index).removeClass("hidden");
$(".combine_way").eq(index).siblings('.combine_way').addClass('hidden');
}
})
$(function(){
var $parent=$('.combine_way').eq(1);
$parent.find('.ui-switch-panel').eq(0).removeClass('hidden');
$parent.find('.tab-second-lists').eq(0).addClass('current');
})

$(".sales_name_list").live('click', function() {
sales();
})

sales();
function sales(){
$(".group_list").each(function(){
var $this = $(this);
var $suit = $this.find(".fitting-suit-info");
var market_price = "<?php echo $goods['market_price']?>";
var number = 1,
market_price = parseFloat(market_price),
total_price = parseFloat(shop_price);
skuids = "<?php echo $goods['sku_id']?>";
$(this).find(".check-items").each(function(){
number += 1;
market_price +=  parseFloat($(this).find(".text-mix em").data("market"));
total_price +=  parseFloat($(this).find(".text-mix em").html());
skuids += ',' + $(this).find(".text-mix em").attr("data-id");
});
$suit.find(".total_num").html(number);
$suit.find(".price").html(total_price.toFixed(2));
$suit.find(".item-btn").attr("data-skuids",skuids);
$suit.find(".market").html(market_price.toFixed(2));
});
}

//商品二维码
var qr_code = '<?php echo $qr_code[0];?>';
var qr_url = window.location.href;
if(qr_code == 1) {
$("[data-model='qrcode']").qrcode({
render: "table",
width: 100,
height:100,
text:qr_url,
});
}
$(".qrcode-box").hover(function(){
$(".qrcode-con").removeClass('hidden');
$(".qrcode-border").removeClass('hidden');
},function(){
$(".qrcode-con").addClass('hidden');
$(".qrcode-border").addClass('hidden');
})

</script>