<?php include template('header','admin'); ?>
<div class="fixed-nav layout">
	<ul>
		<li class="first">第三方登录<a id="addHome" title="添加到首页快捷菜单">[+]</a></li>
		<li class="spacer-gray"></li>
		<li><a class="labelbox current" href="javascript:;"><?php echo $logins[$_GET['code']]['login_name']?></a></li>
	</ul>
	<div class="hr-gray"></div>
</div>
<div class="content padding-big-left padding-big-bottom have-fixed-nav">
	<div class="tips margin-tb">
		<div class="tips-info border">
			<h6>温馨提示</h6>
			<a id="show-tip" data-open="true" href="javascript:;">关闭操作提示</a>
		</div>
		<div class="tips-txt padding-small-top layout">
			<p>- 请仔细填写相关配置</p>
		</div>
	</div>
	<div class="hr-gray"></div>
	<form class="addfrom" name="form1" id="form1" action="" method="post">
		<div class="youhui clearfix margin-top">
			<ul>
				<li class="borm">
					<?php echo form::input('enabled','enabled', isset($logins[$_GET['code']]['enabled']) ? $logins[$_GET['code']]['enabled'] : '0','是否开启：','是否开启该登录方式',array('itemrows' => 2)); ?>
					<?php echo form::input('enabled','quick', isset($logins[$_GET['code']]['quick']) ? $logins[$_GET['code']]['quick'] : '0','免绑定登录：','是否开启免绑定登录',array('itemrows' => 2)); ?>
				</li>
				<?php echo form::input('text', 'info[login_name]', $logins[$_GET['code']]['login_name'] ? $logins[$_GET['code']]['login_name'] : $xmldata['name'],'登录名称：','设置登录名称供前台显示',array('datatype'	=> '*','nullmsg'	=> '登录名称不能为空')); ?>
	            <?php foreach ($xmldata['config'] as $k => $v): ?>
                    <?php
                    $_show = TRUE;
                    switch ($v['type']) {
                        case 'hidden':
                            $_show = FALSE;
                            $_form = '<input type="hidden" name="info[config]['.$k.'][value]" value="'.$v['value'].'">';
                            break;
                        default:
                        	$_form = form::input('text', 'info[config]['.$k.'][value]', $logins[$_GET['code']]['config'][$k], $v[name], $v[tips]);
                            break;
                    }
                    ?>
                <li <?php if (!$_show): ?>style="display:none;"<?php endif ?>><?php echo $_form; ?></li>
            <?php endforeach ?>
            <?php if($_GET['code'] == 'wechat_wap'){?>
            	<?php //echo form::input('enabled','synchro', isset($logins[$_GET['code']]['config']['synchro']) ? $logins[$_GET['code']]['config']['synchro'] : '0','是否已绑定微信开发平台：','是否已和微信开放平台绑定，请慎重选择',array('itemrows' => 2)); ?>
            	<?php echo form::input('enabled','force', isset($logins[$_GET['code']]['config']['force']) ? $logins[$_GET['code']]['config']['force'] : '0','是否在微信端强制开启：','若开启该选项，则微信端登录会默认选择该登录方式',array('itemrows' => 2)); ?>
            <?php }?>
			</ul>
		</div>
     	<div class="padding">
			<input type="submit" class="button bg-main" value="提交" />
			<a class="button margin-left bg-gray" href="<?php echo url('#login')?>">返回</a>
		</div>
	</form>
</div>