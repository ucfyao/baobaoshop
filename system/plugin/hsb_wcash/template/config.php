<?php include template('header', 'admin'); ?>
<div class="fixed-nav layout">
    <ul>
        <li class="first">余额提现_基本设置</li>
        <li class="spacer-gray"></li>
				<li><a <?php if(!$act){?>class="current"<?php }?> href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash'))?>">基本设置</a></li>
				<li><a <?php if($act=='config_alidayu'){?>class="current"<?php }?> href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => 'config_alidayu'))?>">短信验证设置</a></li>
				<li><a <?php if($act=='config_alipay'){?>class="current"<?php }?> href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => 'config_alipay'))?>">支付宝设置</a></li>
				<li><a <?php if($act=='config_wxpay'){?>class="current"<?php }?> href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:hsb_wcash','act' => 'config_wxpay'))?>">微信支付设置</a></li>
				<li><a href="<?php echo url('admin/app/module',array('mod' => 'hsb_wcash:list'))?>">提现记录</a></li>
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
		<?php if($act=='config_alidayu'){?>
		    <p>1、您需要先前往阿里大于注册账号，并设置短信模板。支持的变量为：${code} 验证码，${product} 申请提现</p><br>
		<?php }elseif($act=='config_alipay'){?>
		 <p>1、您需要现在支付宝开放平台建立一个应用，并开通单笔转账到支付宝的功能，可以使用相应支付宝的余额向用户支付款项。</p><br>
		<?php }elseif($act=='config_wxpay'){?>
		 <p>1、您需要有一个经认证过的微信公众号，且已开通了微信支付中的企业付款功能，该功能需要单独充值，不能使用微信支付的余额向用户支付款项。</p><br>
		<?php }else{?>
		 <p></p><br>
		<?php }?>
		
		
		
        </div>
    </div>
    <div class="hr-gray"></div>
    <form class="addfrom" name="form1" id="form1" action="" method="post" enctype="multipart/form-data">
<?php if(!$act){?>	
	<div class="form-box clearfix">
		<?php echo form::input('text', 'rate', $config['rate'], '提现费率：', '设置提现收取的手续费费率，单位为%') ?>
		<?php echo form::input('text', 'lessmoney', $config['lessmoney'], '最低提现金额：', '单次最低提现金额，建议最低不得低于2元，单位为元') ?>
		<?php echo form::input('radio','alipay_status', $config['alipay_status'],'<b class="text-red">开启提现到支付宝：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('radio','wxpay_status', $config['wxpay_status'],'<b class="text-red">开启提现到微信：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('textarea','tips', $config['tips'],'提现说明：','关于提现的有关说明，不留空将在前台显示'); ?>
		
		<div style="display:none">
		<?php echo form::input('radio','alidayu_mobilecheck_status', $config['alidayu_mobilecheck_status'],'<b class="text-red">开启短信验证：</b>','只支持使用阿里大于',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alidayu_signname', $config['alidayu_signname'], '短信签名：', '请使用阿里大于已通过审核的短信签名') ?>
		<?php echo form::input('text', 'alidayu_appkey', $config['alidayu_appkey'], 'AppKey：', '阿里大于的中的 AppKey') ?>
		<?php echo form::input('text', 'alidayu_appsecret', $config['alidayu_appsecret'], 'AppSecret：', '阿里大于的中的 AppSecret') ?>
		<?php echo form::input('text', 'alidayu_templatecode', $config['alidayu_templatecode'], '短信验证 模版ID：', '请在阿里大于中添加模板，模板内容为：验证码${code}，您正在${product}，非本人操作请勿理会，请妥善保管账户信息。') ?>
		<?php echo form::input('radio','alipay_autopay_status', $config['alipay_autopay_status'],'开启实时付款：','是否开启支付宝实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alipay_payer_show_name', $config['alipay_payer_show_name'], '显示姓名：', '收款方可见') ?>
		<?php echo form::input('text', 'alipay_appId', $config['alipay_appId'], '支付宝appId：', '支付宝分配给开发者的应用ID') ?>
		<?php echo form::input('textarea','alipay_rsaPrivateKey', $config['alipay_rsaPrivateKey'],'商户应用私钥：','请填写开发者私钥'); ?>
		<?php echo form::input('textarea','alipay_alipayrsaPublicKey', $config['alipay_alipayrsaPublicKey'],'支付宝公钥：','请填写支付宝公钥（非应用公钥）'); ?>
		<?php echo form::input('radio','wxpay_autopay_status', $config['wxpay_autopay_status'],'开启实时付款：','是否开启微信实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'wxpay_mch_appid', $config['wxpay_mch_appid'], 'APPID：', 'appid是微信公众账号或开放平台APP的唯一标识') ?>
		<?php echo form::input('text', 'wxpay_mchid', $config['wxpay_mchid'], '微信支付商户号(mch_id)：', '商户申请微信支付后，由微信支付分配的商户收款账号') ?>
		<?php echo form::input('text', 'wxpay_key', $config['wxpay_key'], 'API密钥(key)：', '交易过程生成签名的密钥') ?>
		<?php echo form::input('text', 'wxpay_apiclient_dir', $config['wxpay_apiclient_dir'], '证书存放位置目录：', '请将证书 apiclient_cert.pem、apiclient_key.pem 上传至您的网站目录下，建议放在防下载目录下，将目录填于此处。根目录请留空，其他目录以 / 开头，结尾不能有 /') ?>
		</div>
	</div>
	
<?php }elseif($act=='config_alidayu'){?>

	<div class="form-box clearfix">
		<?php echo form::input('radio','alidayu_mobilecheck_status', $config['alidayu_mobilecheck_status'],'开启短信验证：','只支持使用阿里大于',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alidayu_signname', $config['alidayu_signname'], '短信签名：', '请使用阿里大于已通过审核的短信签名') ?>
		<?php echo form::input('text', 'alidayu_appkey', $config['alidayu_appkey'], 'AppKey：', '阿里大于的中的 AppKey') ?>
		<?php echo form::input('text', 'alidayu_appsecret', $config['alidayu_appsecret'], 'AppSecret：', '阿里大于的中的 AppSecret') ?>
		<?php echo form::input('text', 'alidayu_templatecode', $config['alidayu_templatecode'], '短信验证 模版ID：', '请在阿里大于中添加模板，模板内容为：验证码${code}，您正在${product}，非本人操作请勿理会，请妥善保管账户信息。') ?>
		
		<div style="display:none">
		<?php echo form::input('text', 'rate', $config['rate'], '提现费率：', '设置提现收取的手续费费率，单位为%') ?>
		<?php echo form::input('text', 'lessmoney', $config['lessmoney'], '最低提现金额：', '单次最低提现金额，建议最低不得低于2元，单位为元') ?>
		<?php echo form::input('radio','alipay_status', $config['alipay_status'],'<b class="text-red">开启提现到支付宝：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('radio','wxpay_status', $config['wxpay_status'],'<b class="text-red">开启提现到微信：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('textarea','tips', $config['tips'],'提现说明：','关于提现的有关说明，不留空将在前台显示'); ?>
		<?php echo form::input('radio','alipay_autopay_status', $config['alipay_autopay_status'],'开启实时付款：','是否开启支付宝实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alipay_payer_show_name', $config['alipay_payer_show_name'], '显示姓名：', '收款方可见') ?>
		<?php echo form::input('text', 'alipay_appId', $config['alipay_appId'], '支付宝appId：', '支付宝分配给开发者的应用ID') ?>
		<?php echo form::input('textarea','alipay_rsaPrivateKey', $config['alipay_rsaPrivateKey'],'商户应用私钥：','请填写开发者私钥'); ?>
		<?php echo form::input('textarea','alipay_alipayrsaPublicKey', $config['alipay_alipayrsaPublicKey'],'支付宝公钥：','请填写支付宝公钥（非应用公钥）'); ?>
		<?php echo form::input('radio','wxpay_autopay_status', $config['wxpay_autopay_status'],'开启实时付款：','是否开启微信实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'wxpay_mch_appid', $config['wxpay_mch_appid'], 'APPID：', 'appid是微信公众账号或开放平台APP的唯一标识') ?>
		<?php echo form::input('text', 'wxpay_mchid', $config['wxpay_mchid'], '微信支付商户号(mch_id)：', '商户申请微信支付后，由微信支付分配的商户收款账号') ?>
		<?php echo form::input('text', 'wxpay_key', $config['wxpay_key'], 'API密钥(key)：', '交易过程生成签名的密钥') ?>
		<?php echo form::input('text', 'wxpay_apiclient_dir', $config['wxpay_apiclient_dir'], '证书存放位置目录：', '请将证书 apiclient_cert.pem、apiclient_key.pem 上传至您的网站目录下，建议放在防下载目录下，将目录填于此处。根目录请留空，其他目录以 / 开头，结尾不能有 /') ?>
		</div>
	</div>
	
<?php }elseif($act=='config_alipay'){?>

	<div class="form-box clearfix">
		<?php echo form::input('radio','alipay_autopay_status', $config['alipay_autopay_status'],'开启实时付款：','是否开启支付宝实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alipay_payer_show_name', $config['alipay_payer_show_name'], '显示姓名：', '收款方可见') ?>
		<?php echo form::input('text', 'alipay_appId', $config['alipay_appId'], '支付宝appId：', '支付宝分配给开发者的应用ID') ?>
		<?php echo form::input('textarea','alipay_rsaPrivateKey', $config['alipay_rsaPrivateKey'],'商户应用私钥：','请填写开发者私钥'); ?>
		<?php echo form::input('textarea','alipay_alipayrsaPublicKey', $config['alipay_alipayrsaPublicKey'],'支付宝公钥：','请填写支付宝公钥（非应用公钥）'); ?>
		
		<div style="display:none">
		<?php echo form::input('text', 'rate', $config['rate'], '提现费率：', '设置提现收取的手续费费率，单位为%') ?>
		<?php echo form::input('text', 'lessmoney', $config['lessmoney'], '最低提现金额：', '单次最低提现金额，建议最低不得低于2元，单位为元') ?>
		<?php echo form::input('radio','alipay_status', $config['alipay_status'],'<b class="text-red">开启提现到支付宝：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('radio','wxpay_status', $config['wxpay_status'],'<b class="text-red">开启提现到微信：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('textarea','tips', $config['tips'],'提现说明：','关于提现的有关说明，不留空将在前台显示'); ?>
		<?php echo form::input('radio','alidayu_mobilecheck_status', $config['alidayu_mobilecheck_status'],'<b class="text-red">开启短信验证：</b>','只支持使用阿里大于',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alidayu_signname', $config['alidayu_signname'], '短信签名：', '请使用阿里大于已通过审核的短信签名') ?>
		<?php echo form::input('text', 'alidayu_appkey', $config['alidayu_appkey'], 'AppKey：', '阿里大于的中的 AppKey') ?>
		<?php echo form::input('text', 'alidayu_appsecret', $config['alidayu_appsecret'], 'AppSecret：', '阿里大于的中的 AppSecret') ?>
		<?php echo form::input('text', 'alidayu_templatecode', $config['alidayu_templatecode'], '短信验证 模版ID：', '请在阿里大于中添加模板，模板内容为：验证码${code}，您正在${product}，非本人操作请勿理会，请妥善保管账户信息。') ?>
		<?php echo form::input('radio','wxpay_autopay_status', $config['wxpay_autopay_status'],'开启实时付款：','是否开启微信实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'wxpay_mch_appid', $config['wxpay_mch_appid'], 'APPID：', 'appid是微信公众账号或开放平台APP的唯一标识') ?>
		<?php echo form::input('text', 'wxpay_mchid', $config['wxpay_mchid'], '微信支付商户号(mch_id)：', '商户申请微信支付后，由微信支付分配的商户收款账号') ?>
		<?php echo form::input('text', 'wxpay_key', $config['wxpay_key'], 'API密钥(key)：', '交易过程生成签名的密钥') ?>
		<?php echo form::input('text', 'wxpay_apiclient_dir', $config['wxpay_apiclient_dir'], '证书存放位置目录：', '请将证书 apiclient_cert.pem、apiclient_key.pem 上传至您的网站目录下，建议放在防下载目录下，将目录填于此处。根目录请留空，其他目录以 / 开头，结尾不能有 /') ?>
		</div>
	</div>
	
<?php }elseif($act=='config_wxpay'){?>	

	<div class="form-box clearfix">
		<?php echo form::input('radio','wxpay_autopay_status', $config['wxpay_autopay_status'],'开启实时付款：','是否开启微信实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'wxpay_mch_appid', $config['wxpay_mch_appid'], 'APPID：', 'appid是微信公众账号或开放平台APP的唯一标识') ?>
		<?php echo form::input('text', 'wxpay_mchid', $config['wxpay_mchid'], '微信支付商户号(mch_id)：', '商户申请微信支付后，由微信支付分配的商户收款账号') ?>
		<?php echo form::input('text', 'wxpay_key', $config['wxpay_key'], 'API密钥(key)：', '交易过程生成签名的密钥') ?>
		<?php echo form::input('text', 'wxpay_apiclient_dir', $config['wxpay_apiclient_dir'], '证书存放位置目录：', '请将证书 apiclient_cert.pem、apiclient_key.pem 上传至您的网站目录下，建议放在防下载目录下，将目录填于此处。根目录请留空，其他目录以 / 开头，结尾不能有 /') ?>
		
		<div style="display:none">
		<?php echo form::input('text', 'rate', $config['rate'], '提现费率：', '设置提现收取的手续费费率，单位为%') ?>
		<?php echo form::input('text', 'lessmoney', $config['lessmoney'], '最低提现金额：', '单次最低提现金额，建议最低不得低于2元，单位为元') ?>
		<?php echo form::input('radio','alipay_status', $config['alipay_status'],'<b class="text-red">开启提现到支付宝：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('radio','wxpay_status', $config['wxpay_status'],'<b class="text-red">开启提现到微信：</b>','',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('textarea','tips', $config['tips'],'提现说明：','关于提现的有关说明，不留空将在前台显示'); ?>
		<?php echo form::input('radio','alidayu_mobilecheck_status', $config['alidayu_mobilecheck_status'],'<b class="text-red">开启短信验证：</b>','只支持使用阿里大于',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alidayu_signname', $config['alidayu_signname'], '短信签名：', '请使用阿里大于已通过审核的短信签名') ?>
		<?php echo form::input('text', 'alidayu_appkey', $config['alidayu_appkey'], 'AppKey：', '阿里大于的中的 AppKey') ?>
		<?php echo form::input('text', 'alidayu_appsecret', $config['alidayu_appsecret'], 'AppSecret：', '阿里大于的中的 AppSecret') ?>
		<?php echo form::input('text', 'alidayu_templatecode', $config['alidayu_templatecode'], '短信验证 模版ID：', '请在阿里大于中添加模板，模板内容为：验证码${code}，您正在${product}，非本人操作请勿理会，请妥善保管账户信息。') ?>
		<?php echo form::input('radio','alipay_autopay_status', $config['alipay_autopay_status'],'开启实时付款：','是否开启支付宝实时付款功能',array('items'=>array('关闭','开启'),'colspan'=>'2')); ?>
		<?php echo form::input('text', 'alipay_payer_show_name', $config['alipay_payer_show_name'], '显示姓名：', '收款方可见') ?>
		<?php echo form::input('text', 'alipay_appId', $config['alipay_appId'], '支付宝appId：', '支付宝分配给开发者的应用ID') ?>
		<?php echo form::input('textarea','alipay_rsaPrivateKey', $config['alipay_rsaPrivateKey'],'商户应用私钥：','请填写开发者私钥'); ?>
		<?php echo form::input('textarea','alipay_alipayrsaPublicKey', $config['alipay_alipayrsaPublicKey'],'支付宝公钥：','请填写支付宝公钥（非应用公钥）'); ?>
		</div>
	</div>
	
<?php }?>	
	
        <div class="form-group">
            <input type="submit" class="button bg-main" value="确定"/>
            <a class="button margin-left bg-gray" href="">返回</a>
        </div>
    </form>
</div>