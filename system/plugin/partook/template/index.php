<?php include template('header','admin'); ?>
<script type="text/javascript" src="./statics/js/template.js" ></script>

<div class="content padding-big have-fixed-nav">
	<div class="fixed-nav layout">
		<ul>
			<li class="first">分享</li>
			<li class="spacer-gray"></li>
		</ul>
		<div class="hr-gray"></div>
	</div>
	<div class="tips margin-tb">
		<div class="tips-info border">
			<h6>温馨提示</h6>
			<a id="show-tip" data-open="true" href="javascript:;">关闭操作提示</a>
		</div>
		<div class="tips-txt padding-small-top layout">
			<p>- 执行此操作可以在前台商品详情页开启分享！可以选择开启分享到要分享的软件，选中代表要在前台开启显示</p>
		</div>
	</div>
	<div class="hr-gray"></div>
	<form class="addfrom" name="form1" id="form1" action="" method="post">
	<div class="youhui mt10">
		<ul>
            <li class="borm">
				<?php echo form::input('enabled', 'status', !is_null($partook['status']) ? $partook['status'] : 1, '是否开启：', '设置分享是否开启'); ?>
			</li>
		</ul>
		<div class="form-group">
		 	<span class="label">分享到：</span>
		 	<div class="box" style="width:650px;">
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['qzone']==1)echo "checked=\"checked\"" ;?>  class="select-btn" name="qzone" value="1" />QQ空间</label>
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['tsina']==1)echo "checked=\"checked\"" ;?> class="select-btn" name="tsina" value="1" />新浪微博</label>
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['tqq']==1)echo "checked=\"checked\"" ;?> class="select-btn" name="tqq" value="1" />腾讯微博</label>
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['weixin']==1)echo "checked=\"checked\"" ;?> class="select-btn" name="weixin" value="1" />微信</label>
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['douban']==1)echo "checked=\"checked\"" ;?> class="select-btn" name="douban" value="1" />豆瓣网</label>
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['renren']==1)echo "checked=\"checked\"" ;?> class="select-btn" name="renren" value="1" />人人网</label>
		 		<label class="select-wrap"><input type="checkbox" <?php if($partook['linkedin']==1)echo "checked=\"checked\"" ;?> class="select-btn" name="linkedin" value="1" />linkedin</label>
		 	</div>
		 	
	 	</div>
	</div>

     
		<div  style="padding-top:170px" >
			<input type="submit" class="button bg-main" value="保存" />
			<a class="button margin-left bg-gray" href="">返回</a>
		</div>
	</form>
</div>
