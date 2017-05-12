<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" >
		<title>后台首页面板</title>
		<link type="text/css" rel="stylesheet" href="<?php echo __ROOT__;?>statics/css/haidao.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo __ROOT__;?>statics/css/admin.css" />
		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/haidao.plug.js" ></script>
		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/haidao.resizeable.columns.js" ></script>
		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/haidao.form.js" ></script>
		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/haidao.validate.js?v=5.3.2" ></script>
		<link rel="stylesheet" href="<?php echo __ROOT__;?>statics/css/validate.css?v=0.0.1"/>

		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/admin.js" ></script>
		<script type="text/javascript" src="<?php echo __ROOT__;?>statics/js/dialog/dialog-plus-min.js"></script>
		<link type="text/css" rel="stylesheet" href="./statics/js/dialog/ui-dialog.css" />
		<script type="text/javascript">
		var formhash = "<?php echo FORMHASH ?>";
		var SYS_MODULE_NAME = "<?php echo MODULE_NAME ?>";
		var SYS_CONTROL_NAME = "<?php echo CONTROL_NAME ?>";
		var SYS_METHOD_NAME = "<?php echo METHOD_NAME ?>";		
		var menuaddurl = "<?php echo url('admin/public/ajax_menu_add',array('formhash'=>FORMHASH))?>";
		var menurefreshurl = "<?php echo url('admin/public/ajax_menu_refresh',array('formhash'=>FORMHASH))?>";
		var menudelurl = '<?php echo url('admin/public/ajax_diymenu_del',array('formhash'=>FORMHASH))?>';
		var site = {
			root:'<?php echo __ROOT__;?>',
			app:'<?php echo __APP__;?>',
			domain:'<?php echo $_SERVER['REQUEST_URI']?>'
		};

        $(function(){
        		
            $('form').each(function(i, n) {
                $(this).append('<input type="hidden" name="formhash" value="'+ formhash +'"/>');
            })

            $("a").each(function() {
            	var _this = $(this);
                var href = _this.attr('href');
                if(href && href.indexOf('javascript:') == -1 && href.indexOf('formhash') == -1 && $(this).attr('rel') != 'nofollow') {
                    if(href.indexOf('?') != -1) {
                        href += '&formhash='+formhash;
                    } else {
                        href += '?formhash='+formhash;
                    }
                    $(this).attr('href', href);
                }
            })

			$("[data-iframe]").live('click', function() {
				var href = $(this).data('iframe');
				if(href == true) {
					href = $(this).attr('href');
				}
				var width = $(this).data('iframe-width') || 500;
				_iframe(href, width);
				return false;
			})


			$("[data-confirm]").live('click', function() {
				var message = $(this).data('confirm') || '您确定执行本操作？';
				return confirm(message);
			})

        });
	//分类选择
    function setClass() {
        var pid = $('input[name="spu[catid]"]').val();
        var pname = $('#choosecat').val();
        var pvalue = $('input[name=cat_format]').val();
        var data = [pid, pname, pvalue];
        top.dialog({
            url: "<?php echo 'plugin.php?id=custom_category:custom_category_popup'?>",
            title: '加载中...',
            data: data,
            width: 930,
            onclose: function () {
                if (this.returnValue) {
                    var catname = this.returnValue.html().replace(/&gt;/g, '>');
                    $('#choosecat').val(catname);
                    var catids = this.returnValue.attr('data-id').split(',');
                    var catid = catids[catids.length - 1];
                    $('input[name=cat_format]').val(this.returnValue.attr('data-id'));
                    $('input[name="spu[catid]"]').val(catid);
                    $.post('<?php echo url("goods/category/ajax_get_attr")?>',{id:catid},function(data){
                        $('.goods-attr-content').html(attr_select);
                        var type_select = '',type_default = 0;
                        for(var k in data.result.types){
                            type_select += '<span class="listbox-item" data-val="'+k+'">'+data.result.types[k]+'</span>';
                        }
                        $("#spu-typeid").find(".listbox-items").html(type_select);
                        $('#spu-typeid').find('.input').val(data.result.types[0]);
                    },'json')
                }
            }
        })
        .showModal();
    }
		function _iframe(url, width) {
			top.dialog({
				url: url,
				title: 'loading...',
				width: width,
				onclose:function() {
					//console.log(this.returnValue);
				}
			})
			.showModal();
		}

		$("form .bg-gray:not([data-back])").live('click',function(){
			history.go(-1);
		})
		
		window.onload = function(){
			document.onkeydown = function (e) {
				var ev = window.event || e;
				var code = ev.keyCode || ev.which;
				if (code == 116) {
					if(ev.preventDefault) {
						if(document.getElementById("main_frame")){
							document.getElementById("main_frame").contentWindow.location.reload(true)
						}else{
							window.location.reload();
						}
						ev.preventDefault();
					} else {
						if(document.getElementById("main_frame")){
							document.getElementById("main_frame").contentWindow.location.reload(true)
						}else{
							window.location.reload();
						}
						ev.keyCode = 0;
						ev.returnValue = false;
					}
				}
			}
		}
		
		</script>

<form action=" " method="POST" name="form-validate">
<div class="fixed-nav layout">
	<ul>
        <li class="first">插件配置</li>
        <li class="spacer-gray"></li>
        <li><a href="<?php echo url('admin/app/module',array('mod'=>'auto_comment')); ?>">首页</a></li>
        <li><a class="current" href="<?php echo url('admin/app/module',array('mod'=>'auto_comment','action'=>'setting')); ?>">设置</a></li>
    </ul>
    <div class="hr-gray"></div>
</div>
<div class="content padding-big have-fixed-nav">
	<div class="form-box clearfix">
		<?php echo Form::input('text', 'setting',$set_time? $set_time:7, '自动好评周期:', '单位天,如：7',array('datatype'=>'n')); ?>
	</div>
	<div class="padding">
		<input type="submit" name="dosubmit" class="button bg-main" value="保存" />
		<input type="reset" name="back" class="button margin-left bg-gray" value="返回" />
	</div>
</div>
</form>
</body>
</html>

<script type="text/javascript">
$(function() {
	var validate = $("[name=form-validate]").Validform({
		ajaxPost:true,
		callback:function(ret) {
			message(ret.message);
			if(ret.status == 1) {
				setTimeout(function(){
					window.top.main_frame.location.href= ret.referer;
				}, 1000);
			} else {
				return false;
			}
		}
	});

	$('input[name=address_detail]').live('blur',function() {
		var address_detail = $(this).val();
		var district = $('#choosecat').val();
		var city_name = district.split(" > ");
		if(city_name[1]){
			var city = city_name[1].replace(/(\s*$)/g,"")+'市';
		}else{
			var city = "";
		}
		// 百度地图API功能
		var myGeo = new BMap.Geocoder();
		bdGEO();
		function bdGEO(){
			geocodeSearch("'"+address_detail+"'");
		}
		function geocodeSearch(add){
			myGeo.getPoint(add, function(point){
				if (point) {
					var address= point.lng + "," + point.lat;
	              	$('input[name=map_point]').val(address);
				}
			},city);
		}
	})
})
</script>
<?php include template('footer','admin');?>