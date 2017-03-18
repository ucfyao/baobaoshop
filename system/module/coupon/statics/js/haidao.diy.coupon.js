define("text!operation/coupon.html", [], function(){
	return '<div class="control-group">\n	<span class="control-label"><%:=label%></span>\n	<div class="controls">\n	<ul class="coupon-list">\n	<%for(var i=0;i<data.length;i++){%>\n	<li data-id="<%:=data[i].id%>">\n	<div class="coupon-list-content">\n	<div class="coupon-list-summary">\n	<span class="label label-success"><%:=data[i].type%></span>\n	<span class="text-black"><%:=data[i].type%></span>\n	<span class="text-gray"><%:=data[i].num%></span>\n	</div>\n	</div>\n	<div class="coupon-list-opts">\n	<a href="javascript:;" class="hd-remove-coupon">删除</a>\n	</div>\n	</li>\n		<%}%>\n	</ul>\n	<%if(value.length < 3){%><a href="javascript:;" class="hd-add-coupon h5">添加优惠券</a><%}%>\n	<span class="margin-left text-gray">（每个优惠券板块只展示三种）</span>\n	<input type="hidden" name="<%:=name%>" value="<%:=value%>" data-validate="required" />\n	</div>\n	</div>';
}),
define(["require", "panel", "opts", "base", "template", "text!operation/coupon.html"], function(require, panel, opts, base, template){
	var B = require("base"), $coupon = hd_config.coupon.libs.data;
	panel.coupon = function(config){
		var $list = '';
		if(config){
			var $d = B.decode(config);
			var $array = '';
			$.each($d, function(k, v){
				$array = v;
			})
			if($array){
				$array = $array.split(",");
				for (var i = 0; i < 3; i++) {
					$.each($coupon, function() {
						if(this.id == $array[i]){
							if(this.condition == -1){
								$list += '<li><a href="javascript:;"><div class="custom-coupon-price text-ellipsis"><span>￥</span>'+ this.num +'</div><div class="custom-coupon-desc text-ellipsis">无门槛使用</div></a></li>';
							}else{
								$list += '<li><a href="javascript:;"><div class="custom-coupon-price text-ellipsis"><span>￥</span>'+ this.num +'</div><div class="custom-coupon-desc text-ellipsis">满'+ this.condition +'元可用</div></a></li>';
							}
						}
					});
				}
			}
		}
		if(!$list){
			$list = '<li><a href="javascript:;"><div class="custom-coupon-price"><span>￥</span>20</div><div class="custom-coupon-desc">满200元可用</div></a></li><li><a href="javascript:;"><div class="custom-coupon-price"><span>￥</span>20</div><div class="custom-coupon-desc">满200元可用</div></a></li><li><a href="javascript:;"><div class="custom-coupon-price"><span>￥</span>20</div><div class="custom-coupon-desc">满200元可用</div></a></li>';
		}
		return '<ul class="custom-coupon clearfix">'+ $list +'</ul>';
	}
	
	opts.coupon = function(data, config){
		var $dialog = config.libs.dialog,
		group = {};
		group.name = config.libs.form.name;
		group.label = config.libs.form.label;
		group.data = [];
		group.value = [];
		if(data){
			data = B.decode(data);
			data = data.coupon.split(',');
			data = data.slice(0,3);
			group.value = data;
			for(var i = 0; i < data.length; i++){
				for(var k in $coupon){
					if($coupon[k].id = data[i]){
						var coup = {};
						coup.id = $coupon[k].id;
						coup.name = $coupon[k].name;
						coup.type = ($coupon[k].type_coupon == 'amount_discount'?'满额立减':'价格立减');
						coup.num = (this.type_coupon == 'amount_discount'?'满'+ $coupon[k].num +'元可用':'无限制');
						group.data.push(coup);
					}
				}
			}
		}
		B.createOpt(template(require("text!operation/coupon.html"), group));
		//添加优惠券
		$('.hd-add-coupon').unbind().bind("click", function(){
			var self = $(this);
			top.dialog({
				url: $dialog.url,
				title: $dialog.title,
				data: self.nextAll("input").val(),
				width: 681,
				onclose: function(){
					var $input = $(".hd-add-coupon").nextAll("input");
					if(this.returnValue && this.returnValue.length > 0){
						if($input.val()){
							$input.val($input.val() + "," + this.returnValue);
						}else{
							$input.val(this.returnValue);
						}
						require("diy").reload();
					}
				}
			})
			.showModal();
		});
		//删除优惠券
		$('.hd-remove-coupon').unbind().bind("click", function(){
			var $id = $(this).parents("li").data("id");
			var $input = $(".sidebar-content").find("input");
			var $array = $input.val().split(",");
			$array.splice($.inArray(parseInt($id),$array),1);
			$input.val($array);
			require("diy").reload();
		})
	}
})