<?php include template('header','admin');?>

		<div class="agree padding-none bg-white clearfix">
	       	<div class="form-group padding-none border-none clearfix">
		       	<div class="box margin-none" style="width: 100%;">
		       		<textarea class="layout textarea border-none" name="Area" style="height: 400px;">海盗电子商务(网店)系统授权协议 适用于中文用户

	版权所有 (c) 2013-2015，云南迪米盒子科技有限公司保留所有权利。

	感谢您选择海盗电子商务(网店)系统(以下简称海盗电商)。希望我们的努力能为您提供一个高效快速、强大的独立网店建站解决方 案。海盗电商官方网址为 http://www.haidao.la，产品交流社区网址为http://bbs.haidao.la。

	用户须知：本协议是您与迪米盒子公司之间关于您使用迪米盒子公司提供的各种软件产品及服务的法律协议。无论您是个人或组织 、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，包括免除或者限制迪米盒子责任的免责条款及对您的 权利限制。请您审阅并接受或不接受本服务条款。如您不同意本服务条款及/或迪米盒子随时对其的修改，您应不使用或主动取消迪 米盒子公司提供的迪米盒子产品。否则，您的任何对迪米盒子产品中的相关服务的注册、登录、下载、查看等使用行为将被视为您 对本服务条款全部的完全接受，包括接受迪米盒子对服务条款随时所做的任何修改。

	本服务条款一旦发生变更, 迪米盒子将在网页上公布修改内容。修改后的服务条款一旦在网站管理后台上公布即有效代替原来的服 务条款。您可随时登录迪米盒子官方论坛查阅最新版服务条款。如果您选择接受本条款，即表示您同意接受协议各项条件的约束。 如果您不同意本服务条款，则不能获得使用本服务的权利。您若有违反本条款规定，迪米盒子公司有权随时中止或终止您对迪米盒 子产品的使用资格并保留追究相关法律责任的权利。

	在理解、同意、并遵守本协议的全部条款后，方可开始使用迪米盒子产品。您可能与迪米盒子公司直接签订另一书面协议，以补充 或者取代本协议的全部或者任何部分 迪米盒子拥有海盗电商的全部知识产权。本软件只供许可协议，并非出售。迪米盒子只允许您 在遵守本协议各项条款的情况下复制、下载、安装、使用或者以其他方式受益于本软件的功能或者知识产权。

	I. 协议许可的权利

	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
	您可以在协议规定的约束和限制范围内修改迪米盒子产品源代码(如果被提供的话)或界面风格以适应您的网站要求。
	您拥有使用本软件构建的网站中全部商品信息、会员资料、文章及相关信息的所有权，并独立承担与使用本软件构建的网站内容的 审核、注意义务，确保其不侵犯任何人的合法权益，独立承担因使用迪米盒子软件和服务带来的全部责任，若造成迪米盒子公司或 用户损失的，您应予以全部赔偿。
	若您需将迪米盒子软件或服务用户商业用途，必须另行获得迪米盒子的书面许可，您在获得商业授权之后，您可以将本软件应用于 商业用途，同时依据所购买的授权类型中确定的技术支持期限、技术支持方式和技术支持内容，自购买时刻起，在技术支持期限内 拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但 没有一定被采纳的承诺或保证。
	您可以从迪米盒子提供的应用中心服务中下载适合您网站的应用程序，但应向应用程序开发者/所有者支付相应的费用。

	II. 协议规定的约束和限制

	未获迪米盒子公司书面商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利 的网站）。购买商业授权请登录http://www.haidao.la参考相关说明，也可以致电400-600-2042了解详情。</textarea>
		       	</div>
	       	</div>
	       	<div class="padding layout fl text-right ui-dialog-footer">
				<input type="button" class="button bg-main" id="agreement" value="同意并绑定" />
			</div>
		</div>
		<div class="login hidden">
			<form name="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="post" >
			<div class="padding-big">
				<p class="border-bottom">输入海盗云商官方账号完成绑定<a class="text-main fr" href="javascript:" onClick="window.open('http://account.haidao.la/?c=index&a=register')" >立即注册</a></p>
				<div class="login-con order-eidt-popup border-bottom-none clearfix">
					<?php echo form::input('text', 'account', '', '用户名：', '', array('datatype'=>'*')); ?>

					<?php echo form::input('password', 'password', '', '密码：', '', array('datatype'=>'*')); ?>
				</div>
			</div>
			<div class="padding text-right ui-dialog-footer">
				<input type="submit" class="button bg-main" id="okbtn" value="立即绑定" />
			</div>
			</form>
		</div>
		<div class="bind hidden">
			<div class="padding clearfix">
				<p class="fl padding text-mix text-lh-small">更改绑定站点，可能会导致丢失云市场授权，请谨慎操作！</p>
				<a class="fr button bg-main new_site" href="javascript:;">添加新站点</a>
			</div>
			<div class="table bg-white border-top clearfix" style="height: 247px; overflow: hidden; overflow-y: auto;">
				<div class="tr">
					<div class="th w25">站点名称</div>
					<div class="th w25">通信密钥</div>
					<div class="th w20">绑定时间</div>
					<div class="th w15">认证状态</div>
					<div class="th w15">操作</div>
				</div>

			</div>
			<div class="padding text-right ui-dialog-footer">
				<input type="button" class="button bg-main" id="bind" value="立即绑定" />
				<input type="button" class="button margin-left bg-gray" id="cancelbind" value="取消" />
			</div>
		</div>
		<script>
			$(function(){
				var $val=$("input[type=text]").first().val();
				$("input[type=text]").first().focus().val($val);
				if(window.cloud == 0){
					$('#okbtn').val('立即绑定');
				}else{
					$('#okbtn').val('重新绑定');
				}

				var dialog = top.dialog.get(window);

				$("#agreement").click(function(){
					$(".agree").addClass("hidden");
					$(".login").removeClass("hidden");
					dialog.width(320);
					dialog.height(263);
					dialog.reset();
				});

				$(".table").find(".button").live('click',function(){
					$.each($(".table").find(".button"), function() {
						if($(this).hasClass('bg-main')){
							$(this).removeClass("bg-main").addClass("bg-gray");
						}
					});
					$(this).addClass("bg-main").removeClass("bg-gray");
					return false;
				});

				

				$("form").Validform({
					ajaxPost:true,
					callback:function(data){
						if(data.status == 0){
							alert(data.message);
							return false;
						}else if(data.status == 1 && data.result.site!=undefined){
							$(".login").addClass("hidden");
							$(".bind").removeClass("hidden");
							dialog.title('站点绑定');
							dialog.width(896);
							dialog.height(360);
							dialog.reset(); 
							var lists = data.result.site;
							var html = '';
							$.each(lists,function(i,item){
								var auth = text = '';
								if(item.current == 1){
									text = '当前绑定';
								}else{
									text = '<a class="button bg-gray" href="">选择</a>'
								}
								if(item.authentication_status == 0){
									auth = '<a class="text-main" target="_blank" href="http://www.haidao.la/index.php?m=console&c=site&a=manage">立即认证</a>'
								}else{
									auth = '已认证';
								}
								html += 	'<div class="tr" data-identifier='+item.identifier+'>'
									 +		'<div class="td w25">'+item.site_name+'</div>'
									 +		'<div class="td w25">'+item.key+'</div>'
									 +		'<div class="td w20">'+item._install_time+'</div>'
									 +		'<div class="td w15">'+auth+'</div>'
									 +		'<div class="td w15">'+text+'</div>'
									 +	'</div>';
							})
							$('.table').append(html);
							return false;
						}
						dialog.close('1'); // 关闭（隐藏）对话框
						dialog.remove();	// 主动销毁对话框
					}
				});
				$('#bind').bind('click',function(){
					var identifier = $('.bg-main').parents('.tr').attr('data-identifier');
					$.post('<?php echo url("bind")?>',{identifier:identifier},function(ret){
						if(ret.status == 1){
							dialog.close(ret.result); // 关闭（隐藏）对话框
							dialog.remove();	// 主动销毁对话框
						}else{
							alert(ret.message);
						}
					},'json');
				})
				$('.new_site').bind('click',function(){
					if(confirm('进行此操作会新生成站点，确认该操作吗？')){
						$.post('<?php echo url("bind")?>',{identifier:''},function(ret){
							if(ret.status == 1){
								dialog.close(ret.result); // 关闭（隐藏）对话框
								dialog.remove();	// 主动销毁对话框
							}else{
								alert(ret.message);
							}
						},'json');
					}
				})
				$('#cancelbind').bind('click',function(){
					dialog.close('1'); // 关闭（隐藏）对话框
					dialog.remove();	// 主动销毁对话框
				})
			})
		</script>
<?php include template('footer','admin');?>
