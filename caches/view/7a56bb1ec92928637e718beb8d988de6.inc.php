<?php if(!defined('IN_APP')) exit('Access Denied');?>
<div class="layout @border-top @border-gray-white global-footer margin-big-top">
<!-- <div class="layout border border-main"></div> -->
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

<dl class="fore last">
<dt>&nbsp;</dt>
<!-- <dd>
<span><img class="footer-logo" src="<?php echo SKIN_PATH;?>statics/images/logo.png" width="158" /></span>
</dd> -->
</dl>
</div>
<?php $data = runhook('help_between_link');?>
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
<script>
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
var url = "<?php echo url('goods/index/html_load');?>";
$(function(){
$.ajax(url, {}, function(ret){});
})
</script>  
<?php echo runhook('global_footer');?>


