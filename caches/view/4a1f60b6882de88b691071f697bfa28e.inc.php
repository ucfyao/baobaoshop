<?php if(!defined('IN_APP')) exit('Access Denied');?>
<?php include template('toper','common');?>
<?php include template('header','common');?>
<script type="text/javascript" src="template/default/statics/js/index.js?v=<?php echo HD_VERSION;?>"></script>
<script type="text/javascript" src="template/default/statics/js/jquery.cookie.js?v=<?php echo HD_VERSION;?>"></script>
<!--Banner-->
<div class="banner">
<div class="shade-slider">
<ul class="box">
<?php
	$taglib_misc_focus = new taglib('misc','focus');
	$data = $taglib_misc_focus->lists(array('order'=>'sort ASC'), array('limit'=>'20','cache'=>'084399cac6745350d12ad62046eb9112,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><li class="item"><a href="<?php echo $r['url'];?>" <?php if($r[target] == 1) { ?> target="_blank" <?php } ?> ><img src="<?php echo $r['thumb'];?>" width="1200" /></a></li>
<?php } ?>

</ul>
</div>
</div>
<!--Banner end-->
<?php echo runhook('banner_between_content');?>
<!--content-->
<div class="container #border-bottom #border-gray-white clearfix">
<div class="fl index-activity">
<ul class="tab layout text-big #border-bottom #border-gray-white clearfix goods_lists">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->page(array('statusext'=>'3'), array('limit'=>'5','cache'=>'57f4bf92de4450375b0329a333eb743b,3600'));
?>
<?php if($data['count'] > 0) { ?>
<li class="current" data-id="3">
<span>新品推荐</span>
<b></b>
</li>
<?php } ?>

<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->page(array('statusext'=>'1'), array('limit'=>'5','cache'=>'be612aa71ab1d0121e9d544141c099da,3600'));
?>
<?php if($data['count'] > 0) { ?>
<li data-id="1">
<span>促销单品</span>
<b></b>
</li>
<?php } ?>

<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->page(array('statusext'=>'2'), array('limit'=>'5','cache'=>'a3be7238db747eaaff5a90328f629278,3600'));
?>
<?php if($data['count'] > 0) { ?>
<li data-id="2">
<span>热卖商品</span>
<b></b>
</li>
<?php } ?>

<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->page(array('statusext'=>'4'), array('limit'=>'5','cache'=>'85cc3ab24e5b2be461568535dbbd9e72,3600'));
?>
<?php if($data['count'] > 0) { ?>
<li data-id="4">
<span>爆款推荐</span>
<b></b>
</li>
<?php } ?>

</ul>
<div class="tab-tag clearfix statusext_box">
</div>
</div>
<div class="fr margin-top notice">
<div class="news-plate">
<div class="title #border-bottom #border-gray-white">
<div class="padding-left text-sub">文章资讯
<em style="float:right;"><a href="<?php echo url('misc/index/article_lists',array('category_id'=>'all'));?>" class="text-default">更多</a></em>
</div>
</div>
<ul class="content margin-big-top">
<?php
	$taglib_misc_article = new taglib('misc','article');
	$data = $taglib_misc_article->lists(array('order'=>'sort ASC','_string'=>'recommend=1'), array('limit'=>'8','cache'=>'7510fd9a28dcce4cb8ae367eb204395c,3600'));
?><?php if(is_array($data)) foreach($data as $k => $r) { ?><li><em>·</em><a <?php if($k < 2 ) { ?>class="hot"<?php } ?> target="_blank" href="<?php echo url('misc/index/article_detail',array('id'=>$r['id']));?>" title="<?php echo $r['title'];?>"><?php echo $r['title'];?></a></li>
<?php } ?>

</ul>
</div>
</div>
</div>
<!--
<div class="container brand-text news-plate">
<div class="title padding-bottom padding-top">
<div class="text-sub">推荐品牌</div>
</div>
<ul>
<?php
	$taglib_goods_brand = new taglib('goods','brand');
	$data = $taglib_goods_brand->lists(array(), array('limit'=>'10','cache'=>'2bcefc34d6fedf8ebef0f647cdf84432,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><li><a href="<?php echo url('goods/index/brand_list',array('id'=>$r['id']));?>"><?php echo $r['name'];?></a></li>
<?php } ?>

</ul>
</div>
-->
<?php echo runhook('misc_between_like');?>
<!--猜您喜欢-->
<div class="container padding-large-top">
<div class="layout #border #border-gray-white #item-blue-top #pro-border-top-gray">
<ul class="tab p-tab-trigger-wrap">
<li class="ui-switchtab-item current"><a href="javascript:;">猜您喜欢</a></li>
<div class="fr refresh-bottom border #margin-big-right text-lh-30 margin-big-top"><span>换一批<b class="margin-left refresh change_goods"></b></span>
</div>
</ul>

<div class="like">
<ul class="clearfix guess_like">
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('order'=>'rand()'), array('limit'=>'6','cache'=>'f684f291a41f3a69fb786cfe32e19cbf,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><li class="index-pic-1">
<div class="img"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb'],500,500)?>" width="150" height="150" /></a></div>
<div class="text">
<div class="title text-ellipsis"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><?php echo $r['sku_name'];?></a></div>
<div class="price">￥<?php echo $r['prom_price']?></div>
</div>
</li>
<?php } ?>

</ul>
</div>
</div>
</div>
<!--1 Floor-->
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>'0'), array('limit'=>'20','cache'=>'5f73f91d94bceb1a9f359ef3796c03f2,3600'));
?><?php if(is_array($data)) foreach($data as $k => $r) { ?><?php $catid = $r['id']?>
<div class="floor container padding-large-top clearfix">
<div class="floor-info tab">
<div class="floor-logo"><?php echo $k+1?>F</div>
<h3><?php echo $r['name'];?></h3>
<ul class="fr text-default clearfix">
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>$r[id]), array('limit'=>'20','cache'=>'6cd79ce160d2a48badbaaf839f4695f3,3600'));
?><?php if(is_array($data)) foreach($data as $key => $r) { ?><li data-id="<?php echo $r['id'];?>" <?php if($key == 0) { ?>data-ajax="1"<?php } ?>><span><?php echo $r['name'];?></span></li>
<?php } ?>

</ul>
</div>
<div class="floor-content tab-tag">
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>$catid), array('limit'=>'20','cache'=>'96f24f4ac92a5f994a785f7f1ad658ea,3600'));
?><?php if(is_array($data)) foreach($data as $key => $r) { ?><?php $cat_id = $r['id']?>
<div class="tag">
<div class="left">
<ul class="clearfix" data-id="<?php echo $cat_id?>">
<?php if($key == 0) { ?>
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('catid'=>$cat_id), array('limit'=>'8','cache'=>'9365802c6f34a34a1e6a5fb384df57c7,3600'));
?><?php if(is_array($data)) foreach($data as $k => $r) { ?><li class="index-pic-2">
<div class="img"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb'],500,500)?>" width="218" height="218" /></a></div>
<div class="text">
<div class="title"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><?php echo $r['sku_name'];?></a></div>
<div class="price">￥<?php echo $r['prom_price'];?></div>
</div>
</li>
<?php } ?>

<?php } ?>
</ul>
</div>
<div class="right">
<div class="top-text">TOP热销榜</div>
<ul class="top-con" data-id="<?php echo $cat_id?>">
<?php if($key == 0) { ?>
<?php
	$taglib_goods_goods = new taglib('goods','goods');
	$data = $taglib_goods_goods->lists(array('catid'=>$cat_id,'order'=>'sales desc'), array('limit'=>'10','cache'=>'e2cc156067f8030f724fb63de978ebd2,3600'));
?><?php if(is_array($data)) foreach($data as $k => $r) { ?><li <?php if($k < 1) { ?>class="hover"<?php } ?>>
<div class="no-num"><span <?php if($k < 3) { ?>class="text-mix"<?php } ?>>NO.<?php if($k < 9) { ?>0<?php } ?><?php echo $k+1?></span></div>
<div class="img"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="<?php echo thumb($r['thumb'],500,500)?>" width="97" height="97" /></a></div>
<div class="text">
<div class="title"><a href="<?php echo url('goods/index/detail',array('sku_id'=>$r['sku_id']));?>"><?php echo $r['sku_name'];?></a></div>
<div class="price"><span>￥<?php echo $r['prom_price']?></span></div>
</div>
</li>
<?php } ?>

<?php } ?>
</ul>
</div>
</div>
<?php } ?>

</div>
</div>
<?php } ?>

<!--content end-->
<!--遮罩层广告-->
<?php
	$taglib_ads_ads = new taglib('ads','ads');
	$data = $taglib_ads_ads->lists(array('position'=>'3','order'=>'id desc'), array('limit'=>'1','cache'=>'cd4b42306d301854fb5130948c9dcc21,3600'));
?>
<?php if(($data['list'])) { ?>
<div class="mask">
<div class="ads" >
<a href="javascript:;">关闭</a><?php if(is_array($data['list'])) foreach($data['list'] as $r) { ?><?php if(empty($r['content'])) { ?>
<img src="<?php echo $data['defaultpic'];?>" />
<?php } else { ?>
<img src="<?php echo $r['content'];?>" title="<?php echo $r['title'];?>" />
<?php } ?>
<?php } ?>
</div>
</div>
<?php } ?>

<!--底部-->
<div class="hd-toolbar-footer">
<div class="hd-toolbar-tab hd-tbar-tab-top margin-bottom">
<a href="#"><i class="tab-ico"></i></a>
</div>
</div>
<!--floor nav-->
<div class="floor-nav">
<ul>
<?php
	$taglib_goods_category = new taglib('goods','category');
	$data = $taglib_goods_category->lists(array('catid'=>'0'), array('limit'=>'20','cache'=>'5f73f91d94bceb1a9f359ef3796c03f2,3600'));
?><?php if(is_array($data)) foreach($data as $k => $r) { ?><li>
<a class="name" href="javascript:;"><?php echo $r['name'];?></a>
<a class="f" href="javascript:;"><?php echo $k+1?>F</a>
</li>
<?php } ?>

</ul>
</div>
<div class="hd-toolbar-footer">
<div class="hd-toolbar-tab hd-tbar-tab-top margin-bottom">
<a href="#"><i class="tab-ico"></i></a>
</div>
</div>
<!--<div class="layout #border-top #border-gray-white #global-footer #margin-big-top">-->
<div class="layout global-footer ">
<!--<div class="layout border border-main"></div>-->
<div class="container hd-service clearfix">
<?php
	$taglib_misc_help = new taglib('misc','help');
	$data = $taglib_misc_help->lists(array('id'=>'0','order'=>'sort asc'), array('limit'=>'5','cache'=>'84b147563f06d71ce3bf8560d089bada,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><dl class="fore">
<dt><?php echo $r['title'];?></dt>
<dd>
<?php
	$taglib_misc_help = new taglib('misc','help');
	$data = $taglib_misc_help->lists(array('id'=>$r[id],'order'=>'sort asc'), array('limit'=>'4','cache'=>'b17d48b8eaad2c4c0e320128d3061b34,3600'));
?><?php if(is_array($data)) foreach($data as $s) { ?><div><a href="<?php echo url('misc/index/help_detail',array('id'=>$s['id']));?>"><?php echo $s['title'];?></a></div>
<?php } ?>

</dd>
</dl>
<?php } ?>


<dl class="fore last fr">
<dt>&nbsp;</dt>
<dd>
<span><img src="<?php echo SKIN_PATH;?>statics/images/logo.png" width="158" /></span>
</dd>
</dl>

</div>
<div class="friend-link padding-large-top container">
<dl class="clearfix">
<dt class="fl">友情链接：</dt>
<dd class="fl">
<?php
	$taglib_misc_friendlink = new taglib('misc','friendlink');
	$data = $taglib_misc_friendlink->lists(array('order'=>'sort ASC'), array('limit'=>'10','cache'=>'cceb39832eed02761fd2770f447c37b3,3600'));
?><?php if(is_array($data)) foreach($data as $r) { ?><a href="<?php echo $r['url'];?>" <?php if(($r[target] == 1)) { ?>target="_blank"<?php } ?>><?php echo $r['name'];?></a>
<?php } ?>

</dd>
</dl>
</div>
<div class="container copyright border-top border-gray-white padding-tb clearfix">
<p class="cop-left fl w50 text-lh-small"><?php echo SITE_AUTHORIZE == 0? COPYRIGHT:''?></p>
<div class="cop-right fr text-right w50">
<?php $cache = cache('setting');?>
<p class="text-lh-small"><!-- <a href="">手机版</a> |  -->
<?php if($cache[com_name]) { ?>
<a target="_blank" href="<?php echo $cache['site_url'];?>"><?php echo $cache['com_name'];?></a>
<?php } ?>
<?php if($cache[icp]) { ?>
|<a target="_blank" href="http://www.miitbeian.gov.cn/"><?php echo $cache['icp'];?></a>
<?php } ?>
<?php if($cache[site_statice]) { ?>
|<a href=""><?php echo $cache['site_statice'];?></a></p>
<?php } ?>
<p class="text-lh-small">PRC, <em id="time"></em></p>
</div>
</div>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo __ROOT__ ?>statics/js/jquery.lazyload.js?v=<?php echo HD_VERSION;?>" ></script>
<script type="text/javascript">
var url = "<?php echo url('goods/index/ajax_goods')?>";
$(function(){
toggle_ads();
if($('.goods_lists .current').attr('data-ajax') == 1) return;
ajax_statusext($('.goods_lists .current').attr('data-id'),5,450);
})
function toggle_ads(){
if($.cookie('visits') !== '1'){
var time = new Date();
var nowline =  time.getHours();
var deadline = 24 - nowline;
$.cookie('visits','1',{hoursToLive: deadline });
$(".mask").show();
}
$(".roof").show();
}
$(".mask a").live('click',function(){
$(".mask").hide();
})
$('.change_goods').live('click',function(){
ajax_like('rand',6,450);
})
$('.text-default li').live('mouseenter',function(){
if($(this).attr('data-ajax') == 1) return;
var catid = $(this).attr('data-id');
ajax_goods(catid,8,500);
})
$('.goods_lists li').live('mouseenter',function(){
if($(this).attr('data-ajax') == 1) return;
ajax_statusext($(this).attr('data-id'),5,450);
})
function ajax_statusext(statusext,limit,length){
$.get(url,{statusext:statusext,limit:limit,length:length},function(ret){
if(!ret.lists) return;
var html = '<ul class="tag selected" data-id="'+ statusext +'">';
$.each(ret.lists,function(i,item){
html += '<li class="index-pic-1">'
+		'<div class="img"><a href="'+ item.url +'"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="'+ item.thumb +'" width="150" height="150" /></a></div>'
+		'<div class="text">'
+			'<div class="title text-ellipsis"><a href="'+ item.url +'">'+ item.sku_name +'</a></div>'
+			'<div class="price">￥'+ item.prom_price +'</div>'
+		'</div>'
+	'</li>';
})
html += '</ul>';
$('.statusext_box ul').removeClass('selected');
$('.statusext_box').append(html);
$('.statusext_box img.lazy').lazyload({
    effect : "fadeIn"
});
$('.goods_lists li[data-id="'+ statusext +'"]').attr('data-ajax',1);
},'json');
}
function ajax_like(order,limit,length,type){
$.get(url,{order:order,limit:limit,length:length},function(ret){
if(!ret.lists) return;
var html = '';
$.each(ret.lists,function(i,item){
html += '<li class="index-pic-1">'
+		'<div class="img"><a href="'+ item.url +'"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="'+ item.thumb +'" width="150" height="150" /></a></div>'
+			'<div class="text">'
+				'<div class="title text-ellipsis"><a href="'+ item.url +'">'+ item.sku_name +'</a></div>'
+				'<div class="price">￥'+ item.prom_price +'</div>'
+			'</div>'
+		'</li>';
})
$('.guess_like').html(html);
$('.guess_like img.lazy').lazyload({
    effect : "fadeIn"
});
},'json');
}
function ajax_goods(catid,limit,length){
$.get(url,{catid:catid,limit:limit,length:length},function(ret){
if(!ret.lists){
$('.text-default li[data-id="'+ catid +'"]').attr('data-ajax',1);
return;
}
var html = '';
if(ret.lists.length > 0){
$.each(ret.lists,function(i,item){
html += '<li class="index-pic-2">'
+		'<div class="img"><a href="'+ item.url +'"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="'+ item.thumb +'" width="218" height="218" /></a></div>'
+		'<div class="text">'
+			'<div class="title"><a href="'+ item.url +'">'+ item.sku_name +'</a></div>'
+			'<div class="price">￥'+ item.prom_price +'</div>'
+		'</div>'
+	'</li>'
})
$('.left ul[data-id="' + catid + '"]').html(html);
$('.left ul[data-id="' + catid + '"] img.lazy').lazyload({
    effect : "fadeIn"
});
ajax_top(catid,'sales',10,100);
}
},'json');
}
function ajax_top(catid,order,limit,length){
$.get(url,{catid:catid,order:order,limit:limit,length:length},function(ret){
if(!ret.lists){
return;
}
var html = '';
if(ret.lists.length > 0){
$.each(ret.lists,function(i,item){
var li_html = i < 1 ? '<li class="hover">' : '<li>';
var top_num = i < 9 ? '0' + (i*1+1) : i+1;
html += li_html
+		'<div class="no-num"><span class="text-mix">NO.' + top_num + '</span></div>'
+		'<div class="img"><a href="'+ item.url +'"><img class="lazy" src="<?php echo SKIN_PATH;?>statics/images/lazy.gif" data-original="'+ item.thumb +'" width="97" height="97" /></a></div>'
+		'<div class="text">'
+			'<div class="title"><a href="'+ item.url +'">'+ item.sku_name +'</a></div>'
+			'<div class="price"><span>￥'+ item.prom_price +'</span></div>'
+		'</div>'
+	'</li>'
})
$('.right ul[data-id="' + catid + '"]').html(html);
$('.right ul[data-id="' + catid + '"] img.lazy').lazyload({
    effect : "fadeIn"
});
$('.text-default li[data-id="'+ catid +'"]').attr('data-ajax',1);
}
},'json');
}
function current(){
var d = new Date();
var year = d.getFullYear(),
month = d.getMonth() < 10 ? '0'+(d.getMonth() + 1) : d.getMonth() + 1,
date = d.getDate() < 10 ? '0'+d.getDate() : d.getDate(),
hours = d.getHours() < 10 ? '0'+d.getHours() : d.getHours(),
minutes = d.getMinutes() < 10 ? '0'+d.getMinutes() : d.getMinutes(),
seconds = d.getSeconds() < 10 ? '0'+d.getSeconds() : d.getSeconds();
return  year+'-'+month+'-'+date+' '+hours+':'+minutes+':'+seconds;
}
setInterval(function(){$("#time").html(current)},1000);
</script>
<?php echo runhook('global_footer');?>