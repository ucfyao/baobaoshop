<?php include $this->admin_tpl('header', 'admin'); ?>
		<div class="">
			<div class="table check-table layout clearfix">
				<div class="tr">
					<div class="th check-option">
						<input id="check-all" type="checkbox">
					</div>
					<div class="th w30 text-left">名称</div>
					<div class="th w15">类型</div>
					<div class="th w15">面值</div>
					<div class="th w40">使用条件</div>
				</div>
				<?php  foreach ($coupon_activity as $k => $v) { ?>
				<div class="tr">
					<div class="td check-option">
						<input type="checkbox" name="coupon" value="<?php echo $v['id']?>">
					</div>
					<div class="td w30 text-left"><?php echo $v['name']?></div>
					<div class="td w15"><?php if($v['type_coupon'] == 'amount_discount'){ echo '满额立减';}else{echo '价格立减';}?></div>
					<div class="td w15"><?php echo $v['discount']?></div>
					<div class="td w40"><?php if($v['type_coupon'] == 'amount_discount'){ echo '满'.$v['condition'].'元可用';}else{echo '无限制';}?></div>
				</div>
				<?php }?>
			</div>
			<div class="paging padding-tb body-bg clearfix">
				<ul class="fr">
					<li>共4条数据</li>
					<li class="spacer-gray margin-lr"></li>
					<li>每页显示<input class="input radius-none" type="text" name="limit" value="20">条</li>
					<li class="spacer-gray margin-left"></li>
					<li class="default-start"></li>
					<li class="default-prev"></li>
					<li class="current"><a href="">1</a></li>
					<li class="default-next"></li>
					<li class="default-end"></li>
				</ul>
			</div>
		</div>
		<div class="padding text-right ui-dialog-footer">
			<input type="button" class="button bg-main" id="okbtn" value="选取" />
			<input type="button" class="button margin-left bg-gray" id="closebtn" value="取消" />
		</div>
		<script>
			try{
				var dialog = top.dialog.get(window);
			}catch(e){
				//TODO handle the exception
			}
			dialog.title('优惠券选取');
			dialog.reset();
			var datas = dialog.data.split(",");

			$('#okbtn').on('click', function () {
				var D = [];
				$.each($("input[name=coupon]"), function(){
					if($(this).is(":checked")){
						D.push(this.value);
					}
				});
				if(D.length > 0){
					
				}
			    dialog.close(D);
				dialog.remove();	// 主动销毁对话框
			});
			$('#closebtn').on('click', function () {
				dialog.remove();
			});
		</script>
	</body>
</html>
