<?php include template('header', 'admin'); ?>
<div class="fixed-nav layout">
    <ul>
        <li class="first">侧边工具栏_基本设置</li>
        <li class="spacer-gray"></li>
				<li><a class="current" href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:hsb_toolbar'))?>">基本设置</a></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_toolbar:kf_list'))?>">客服设置</a></li>
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
		    <p>1、每次保存时，须重新上传二维码图片，否则保存后图片会被清除掉。</p><br>
			<p>2、请确保您的模板文件中存在下面的钩子，否则将无法显示。<font color="#FF0000"><xmp>{hook/global_footer}</xmp></font></p><br>
        </div>
    </div>
    <div class="hr-gray"></div>
    <form class="addfrom" name="form1" id="form1" action="" method="post" enctype="multipart/form-data">
	<div class="form-box clearfix">
		<?php echo form::input('text', 'bg_color', $config['bg_color'], '鼠标经过背景色：', '鼠标经过时背景颜色，请使用十六进制颜色代码，例如：#DE373E') ?>
	</div>
	<div class="form-box clearfix"><b>A区 - 链接设置</b></div>
	<div class="form-box clearfix">
		<?php echo form::input('text', 'link_name', $config['link_name'], '链接名称：', '') ?>
		<?php echo form::input('text', 'link_url', $config['link_url'], '链接地址：', '') ?>
	</div>
	<div class="form-box clearfix"><b>B区 - 咨询客服基本设置</b></div>
	<div class="form-box clearfix">
	    <?php echo form::input('text', 'ol_time', $config['ol_time'], '在线咨询服务时间：', '') ?>
		<?php echo form::input('text', 'ol_text', $config['ol_text'], '在线咨询说明：', '') ?>
		<?php echo form::input('radio','ol_status', $config['ol_status'],'是否统一在线状态图标','',array('items'=>array('否','是'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'ot_text', $config['ot_text'], '其他咨询说明：', '') ?>
		<?php echo form::input('textarea', 'ot_cont', $config['ot_cont'], '其他咨询内容：', '') ?>
		<?php echo form::input('text', 'tel_type', $config['tel_type'], '电话服务类型：', '') ?>
		<?php echo form::input('text', 'tel_time', $config['tel_time'], '电话服务时间：', '') ?>
		<?php echo form::input('text', 'tel_num', $config['tel_num'], '电话号码：', '') ?>
	</div>
	<div class="form-box clearfix"><b>C区 - 二维码设置</b></div>
	<div class="form-box clearfix">
		<?php echo form::input('text', 'qr_text', $config['qr_text'], '二维码说明：', '') ?>
		<?php echo form::input('file', 'qr_img', $config['qr_img'], '二维码图片：',''); ?>	
	</div>
        <div class="form-group">
            <input type="submit" class="button bg-main" value="确定"/>
            <a class="button margin-left bg-gray" href="">返回</a>
        </div>
    </form>
</div>