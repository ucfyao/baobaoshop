<?php if(!defined('IN_APP')) exit('Access Denied');?>
<?php include template('toper','common');?>
<?php include template('header','common');?>
<!--面包屑-->
<div class="container crumbs border-bottom clearfix">
<p class="fl layout">
<i class="icon-crumbs"></i>
<?php echo catpos($_GET['id']);?>
</p>
</div>
<?php echo runhook('lists_crumbs');?>
<!--list-->
<div class="item-two-column container margin-large-top clearfix">
<div class="left fl">
<div class="list-category border radius margin-big-bottom border-gray-white">
<!-- <div class="list-category "> -->
<div class="item-title"><?php echo $category['top_parentid']['name'];?></div>
<div class="layout sp-category">
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>$category[top_parentid][id]), array('limit'=>'20','cache'=>'78710c2843d13c0fdba4f3f2b68bbe66,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><?php if($_GET[id] == $r[id] || $r[id] == $category[parent_id] || $id == $category[top_parentid][id]) { ?>
<dl class="open">
<dt><i></i><?php echo $r['name'];?></dt>
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>$r[id]), array('limit'=>'20','cache'=>'62f15ddc9bf6d28f988bdcec5de15bdd,3600'));
?><?php if(is_array($data)) foreach($data as $rr) { ?><dd><a href="<?php echo !empty($rr['url']) ? $rr['url'] : url('goods/index/lists',array('id'=>$rr['id']))?>"><?php echo $rr['name'];?></a></dd>
<?php } ?>

</dl>
<?php } else { ?>
<dl>
<dt><i></i><?php echo $r['name'];?></dt>
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>$r[id]), array('limit'=>'20','cache'=>'62f15ddc9bf6d28f988bdcec5de15bdd,3600'));
?><?php if(is_array($data)) foreach($data as $rr) { ?><dd><a href="<?php echo !empty($rr['url']) ? $rr['url'] : url('goods/index/lists',array('id'=>$rr['id']))?>"><?php echo $rr['name'];?></a></dd>
<?php } ?>

</dl>
<?php } ?>
<?php } ?>

</div>
</div>
<div class="layout margin-top border radius margin-big-bottom border-gray-white clearfix">
<div class="item-title">看了又看</div>
<div class="ext-content padding clearfix">
<div class="ext-show">
<div class="ext-con-wrap clearfix">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('order'=>'rand()'), array('limit'=>'6','cache'=>'f684f291a41f3a69fb786cfe32e19cbf,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><div class="ext-con-box margin-bottom">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>" title="<?php echo $r['sku_name'];?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb'],500,500)?>" width="90" height="90" /></a>
<p>￥<?php echo $r['prom_price']?></p>
</div>
<?php } ?>

</div>
</div>
</div>
</div>
<!--列表页广告-->
<?php
	$taglib_ads_ads = new taglib('ads','ads');
	$data = $taglib_ads_ads->lists(array('position'=>'2','order'=>'rand()'), array('limit'=>'1','cache'=>'612ad437dadd62937e96a65f2a22d621,3600'));
?>
<?php if(($data['list'])) { ?>
<div class="margin-top ad fl"><?php if(is_array($data['list'])) foreach($data['list'] as $r) { ?><?php if(empty($r['content'])) { ?>
<a href="javascript:;" >
<img src="<?php echo $data['defaultpic'];?>" />
</a>
<?php } else { ?>
<a href="<?php echo url('ads/index/adv_view',array('id'=>$r['id'],'url'=>$r['link']));?>" title="<?php echo $r['title'];?>">
<img src="<?php echo $r['content'];?>"/>
</a>
<?php } ?>
<?php } ?>
</div>
<?php } ?>

<?php echo runhook('lists_left');?>
</div>
<div class="fr right goods-list-wrap">
<!--热销商品-->
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('catid'=>$category[id],'statusext'=>'4'), array('limit'=>'4','cache'=>'d9b32560201898333ee7d2796692a397,3600'));
?>
<?php if(count($data) > 0) { ?>
<div class="margin-bottom border border-gray-white item-blue-top">
<div class="item-title padding-left">推荐商品</div>
<div class="padding selling-goods">
<ul><?php if(is_array($data)) foreach($data as $r) { ?><li>
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>" class="pic"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb'],500,500)?>" width="90" height="90" /></a>
<div class="info">
<a class="title" href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>" title="<?php echo $r['sku_name'];?>"><?php echo $r['sku_name'];?></a>
<p>销售价：<span class="txt-mix">￥<?php echo $r['prom_price']?></span></p>
<?php if($r[number] > 0) { ?>
<a class="cart-btn" data-event="buy_now" data-skuids="<?php echo $r['sku_id'];?>" href="javascript:;">立即抢购</a>
<?php } else { ?>
<span class="button bg-gray">商品已售罄</span>
<?php } ?>
</div>
</li>
<?php } ?>
</ul>
</div>
</div>
<?php } ?>

<?php echo runhook('lists_recommend_between_lists');?>
<!--属性选择-->
<!--<div class="border border-gray-white item-blue-top selected-type">-->
<div class="item-blue-top margin-large-bottom text-default">
<div class="item-title padding-left margin-big-bottom text-default"><span class="text-sub"><?php echo $category['name'];?></span> - 商品筛选<a class="fr margin-big-right text-sub" href="<?php echo url('lists',array('id'=>$_GET[id]));?>"> 重置筛选</a></div>
<div class="list-type-selected clearfix">
<?php if(!empty($brands)) { ?>
<dl class="item-type hidden">
<dt>品牌：</dt>
<dd class="type-name">
<?php if($_GET['brand_id']) { ?>
<a class="hidden" href="<?php echo create_url('brand_id', 0);?>">全部</a>
<?php } else { ?>
<a class="hidden selected" href="javascript:void(0)">全部</a>
<?php } ?>
<?php foreach ($brands AS $brand):?>
<?php if($_GET['brand_id']==$brand['id']) { ?>
<a class="hidden selected" href="javascript:void(0)"><?php echo $brand['name'];?></a>
<?php } else { ?>
<a class="hidden" href="<?php echo create_url('brand_id', $brand['id']);?>"><?php echo $brand['name'];?></a>
<?php } ?>
<?php endforeach?>
</dd>
<dd class="more"></dd>
</dl>
<?php } ?>
<?php if($grades) { ?>
<dl class="item-type hidden">
<dt>价格：</dt>
<dd class="type-name">
<?php if($_GET['price']) { ?>
<a class="hidden" href="<?php echo create_url('price', '');?>">全部</a>
<?php } else { ?>
<a class="hidden selected" href="javascript:void(0)">全部</a>
<?php } ?>
<?php
$current = current($grades);
$end = end($grades);
?>
<?php if($current[0] > 1) { ?>
<?php $max_price = $current[0] - 1;?>
<?php if($_GET['price'] == '0,'.$max_price) { ?>
<a class="hidden selected" href="javascript:void(0)"><?php echo $max_price;?>以下</a>
<?php } else { ?>
<a class="hidden" href="<?php echo create_url('price', '0,'.$max_price);?>"><?php echo $max_price;?>以下</a>
<?php } ?>
<?php } ?>
<?php foreach ($grades AS $grade):?>
<?php if($_GET['price'] == implode(',',$grade)) { ?>
<a class="hidden selected" href="javascript:void(0)"><?php echo $grade[0].'-'.$grade[1];?></a>
<?php } else { ?>
<a class="hidden" href="<?php echo create_url('price', implode(',',$grade));?>"><?php echo $grade[0].'-'.$grade[1];?></a>
<?php } ?>
<?php endforeach?>
<?php $min_price = $end[1] + 1;?>
<?php if($_GET['price'] == $min_price.',0') { ?>
<a class="hidden selected" href="javascript:void(0)"><?php echo $min_price;?>以上</a>
<?php } else { ?>
<a class="hidden" href="<?php echo create_url('price', $min_price.',0');?>"><?php echo $min_price;?>以上</a>
<?php } ?>
</dd>
<dd class="more"></dd>
</dl>
<?php } ?>
<?php
	$taglib_goods_type = new taglib('goods','type');
	$data = $taglib_goods_type->lists(array('catid'=>$_GET[id]), array('limit'=>'20','cache'=>'97fc4d5ca6f1de52b84a71f247a6485b,3600'));
?>
<?php $attrs = array_keys($data);?><?php if(is_array($data)) foreach($data as $k => $r) { ?><?php if($r[search] == 1) { ?>
<dl class="item-type hidden">
<dt><?php echo $r['name'];?>：</dt>
<dd class="type-name">
<?php if($_GET['attr'][$k]) { ?>
<a class="hidden" href="<?php echo create_url($k, '', $attrs);?>">全部</a>
<?php } else { ?>
<a class="hidden selected" href="javascript:void(0)">全部</a>
<?php } ?><?php if(is_array($r['value'])) foreach($r['value'] as $v) { ?><?php if($_GET['attr'][$k] != base_encode($v)) { ?>
<a class="hidden" href="<?php echo create_url($k, $v, $attrs);?>"><?php echo $v;?></a>
<?php } else { ?>
<a class="hidden selected" data-status="true" href="javascript:void(0)"><?php echo $v;?></a>
<?php } ?>
<?php } ?>
</dd>
<dd class="more"></dd>
</dl>
<?php } ?>
<?php } ?>

<?php
	$taglib_goods_type = new taglib('goods','type');
	$data = $taglib_goods_type->specs(array('catid'=>$_GET[id]), array('limit'=>'20','cache'=>'cab80832137833e431746b4f5028c511,3600'));
?>
<?php $spec= array_keys($data);?><?php if(is_array($data)) foreach($data as $k => $r) { ?><dl class="item-type hidden">
<dt><?php echo $r['name'];?>：</dt>
<dd class="type-name">
<?php if($_GET['attr'][$k]) { ?>
<a class="hidden" href="<?php echo create_url($k, '', $spec);?>">全部</a>
<?php } else { ?>
<a class="hidden selected" href="javascript:void(0)">全部</a>
<?php } ?><?php if(is_array($r['value'])) foreach($r['value'] as $v) { ?><?php if($_GET['attr'][$k] != base_encode($v)) { ?>
<a class="hidden" href="<?php echo create_url($k, $v, $spec);?>"><?php echo $v;?></a>
<?php } else { ?>
<a class="hidden selected" data-status="true" href="javascript:void(0)"><?php echo $v;?></a>
<?php } ?>
<?php } ?>
</dd>
<dd class="more"></dd>
</dl>
<?php } ?>

</div>
</div>
<!--筛选-->
<!-- <div class="margin-top border border-gray-white item-blue-top filter"> -->
<div class="margin-top text-default filter">
<dl>
<dt>排序方式：</dt>
<dd <?php if($result['sort'] == 'comper') { ?> class="selected" <?php } ?>><a href="<?php echo create_url('sort','comper,');?>">综合</a></dd>
<dd <?php if($result['sort'] == 'sale') { ?> class="selected" <?php } ?>><a href="<?php echo create_url('sort','sale,');?>">销量<?php if($result['sort'] == 'sale') { ?><i class="ico-down"></i><?php } ?></a></dd>
<dd <?php if($result['sort'] == 'shop_price') { ?> class="selected" <?php } ?>><a href="<?php echo create_url('sort','shop_price,'.$result['_by']);?>">价格<?php if($result['sort'] == 'shop_price') { ?><?php if($result['_by'] == 'asc') { ?><i class="ico-down"></i><?php } else { ?><i class="ico-up"></i><?php } ?><?php } ?></a></dd>
<dd <?php if($result['sort'] == 'hits') { ?> class="selected" <?php } ?>><a href="<?php echo create_url('sort','hits,');?>">人气<?php if($result['sort'] == 'hits') { ?><i class="ico-down"></i><?php } ?></a></dd>
<dd class="filter-page item-page border-none">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->page(array('catid'=>$_GET[id],'brand_id'=>$result[brand_id],'order'=>$result[order],'goods_ids'=>$result[_goods_ids],'price'=>$result[price],'show_switch'=>$result[show_switch]), array('limit'=>'12','cache'=>'3dddc79e5e57ad54d4dfe56436404b25,3600','page'=>$_GET[page],'pagefunc'=>'pages'));
	$pages = $taglib_goods_goods->pages;
?>
<?php echo $data['page'];?>

</dd>
</dl>
<?php echo runhook('lists_order');?>
</div>
<!--商品列表-->
<div class="list-wrap">
<ul class="list-h clearfix">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('catid'=>$_GET[id],'brand_id'=>$result[brand_id],'order'=>$result[order],'goods_ids'=>$result[_goods_ids],'price'=>$result[price],'show_switch'=>$result[show_switch]), array('limit'=>'12','cache'=>'9468af3366becf6ef118dcc483652cfd,3600','page'=>$_GET[page],'pagefunc'=>'pages'));
	$pages = $taglib_goods_goods->pages;
?><?php if(is_array($data)) foreach($data as $r) { ?><li>
<div class="lh-wrap">
<div class="p-img">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" width="230" height="230" data-original="<?php echo thumb($r['thumb'],500,500)?>" /></a>
<?php if($r['status_ext'] == 1) { ?>
<div class="picon pi3"></div>
<?php } elseif($r['status_ext'] == 2) { ?>
<div class="picon pi2"></div>
<?php } elseif($r['status_ext'] == 3) { ?>
<div class="picon pi1"></div>
<?php } ?>
</div>
<div class="p-name">
<a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><?php if(isset($r['prom_time']) && $r['prom_time'] > 0) { ?><span class="padding-small-left padding-small-right radius-small bg-mix text-white margin-small-right">限时促销</span><?php } ?><?php echo $r['sku_name'];?></a>
</div>
<div class="p-price">
<span class="text-mix">￥<?php echo $r['prom_price']?></span>
</div>
<div class="p-hand text-right">
<div class="short-share">
<?php if($r[number] > 0) { ?>
<a class="cart-btn fl" data-event="cart_add" data-skuids="<?php echo $r['sku_id'];?>">加入购物车</a>
<?php } else { ?>
<span class="fl">商品已售罄</span>
<?php } ?>
<?php if(is_favorite($member['id'],$r['sku_id'])) { ?>
<span class="button"><em class="text-gray">已收藏</em></span>
<?php } else { ?>
<a class="button collect-btn" data-skuid="<?php echo $r['sku_id'];?>" data-url="<?php echo urlencode($_SERVER['REQUEST_URI']);?>" data-name="<?php echo $r['sku_name'];?>" data-price="<?php echo $r['shop_price'];?>">收藏商品</a>
<?php } ?>
</div>
</div>
</div>
</li>
<?php } ?>

</ul>
</div>
<div class="paging margin-top padding-tb clearfix" data-id="<?php echo $_GET['id'];?>" data-page="<?php echo $_GET['page'];?>">
<?php echo $pages?>
</div>
</div>
</div>
<?php include template('toolbar','common');?>
<?php include template('footer','common');?>

<script type="text/javascript" src="<?php echo __ROOT__ ?>statics/js/jquery.lazyload.js?v=<?php echo HD_VERSION;?>" ></script>
<script>
$(function(){
//点击到指定页
$(".paging .button").click(function(){
jump_to_page(this);
});
//回车到指定页
$(".paging input[name=page]").live('keyup',function(e){
if(e.keyCode == 13){
jump_to_page(this);
} 
});

loadAfterAction();

//展示和隐藏更多属性
$(".item-type .more a").live('click',function(){
var _child = $(this).parent().prev(".type-name").children();
if(!$(this).hasClass("fold")){
$(this).html("收起").addClass("fold");
_child.removeClass("hidden");
}else{
$(this).html("更多").removeClass("fold");
_child.addClass("hidden").slice(0,11).removeClass("hidden");
}
return false;
});



//展示和隐藏更多属性选项
$(".more-type").live('click',function(){
if(!$(this).hasClass("fold")){
$(this).html("收起选项").addClass("fold");
$(".selected-type .item-type").removeClass("hidden");
}else{
$(this).html("更多选项").removeClass("fold");
$(".selected-type .item-type").addClass("hidden").slice(0,5).removeClass("hidden");
}
return false;
});

$(".list-h li").hover(function(){
$(this).addClass("hover");
},function(){
$(this).removeClass("hover");
});

//筛选条
filterBar();

})

//添加属性选择的更多
function loadAfterAction(){
var flog = true;
$(".item-type").each(function(){
if($(this).find("a[data-status]").length>0&&$(this).index()>4){
flog = false;
}
$(this).children(".type-name").find("a").slice(0,11).removeClass("hidden");
if($(this).children(".type-name").find("a").length>10){
$(this).children(".more").append('<a class="text-sub" href="">更多</a>');
}
});
if($(".item-type").length>5&&flog){
$(".item-type").slice(0,5).removeClass("hidden");
$(".selected-type").after('<a class="more-type" href="">更多选项</a>');
}else{
$(".item-type").removeClass("hidden");
}
}

//筛选条
function filterBar(){
$(window).scroll(function(){
var doctop = $(document).scrollTop();
var _head = $(".list-wrap").offset().top-41;
if(doctop <= _head){
$('.goods-list-wrap').removeClass('filter-bar');
}
if(doctop > _head){
$('.goods-list-wrap').addClass('filter-bar');
}
});
}
</script>
</body>
</html>
