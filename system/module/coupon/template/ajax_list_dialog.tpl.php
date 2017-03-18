<?php include template('header','admin');?>
<script type="text/javascript" src="./statics/js/goods/goods_cat.js" ></script>
<script type="text/javascript" src="./statics/js/template.js" ></script>
		<div class="goods-list-search padding-top padding-bottom padding-big-left border-bottom clearfix" >
		
		</div>
		<div class="table border-none high-table opreate-table clearfix">
			<div class="tr border-none">
				<div class="th w20">优惠券名</div>
				<div class="th w25">过期时间</div>
				<div class="th w10">满足金额</div>
				<div class="th w10">面值</div>
				<div class="th w10">仅原价可用</div>
				<div class="th w10">使用范围</div>
				<div class="th w15">操作</div>
			</div>
			<?php foreach ($coupon['list'] AS $sku) {?>
			<div class="tr" data-id="<?php echo $sku['id'] ?>" data-name="<?php echo $sku['name'] ?>" data-time="<?php if($sku['type_time'] == 2){ echo "领取之后".$sku['time']."天过期";}else{echo date("Y-m-d H:i:s", $sku['start_time'])." - ".date("Y-m-d H:i:s", $sku['end_time']);} ?>" data-condition="<?php echo $sku['condition']?>" data-discount="<?php echo $sku['discount'] ?>" data-typebuy="<?php if($sku['type_buy']==1){echo "是";}else{echo "否";}?>">
				<div class="td w20 ">
					<?php echo $sku['name']?>
				</div>
				<div class="td w25 text-left">
                                        <span class="td-con"><?php if($sku['type_time'] == 2){ echo "领取之后".$sku['time']."天过期";}else{echo date("Y-m-d H:i:s", $sku['start_time'])." - ".date("Y-m-d H:i:s", $sku['end_time']);} ?></span>
				</div>
				<div class="td w10"><?php echo $sku['condition']?></div>
				<div class="td w10"><?php echo $sku['discount']?></div>
				<div class="td w10"><?php if($sku['type_buy']==1){echo "是";}else{echo "否";}?></div>
				<div class="td w10"><?php if($sku['type_use']==1){echo "全店";}else{echo "指定";}?></div>
				<div class="td w15">
				<?php if(!$sku['prom_type'] || $_GET['type'] == 'give') { ?>
					<label class="check-btn button bg-gray"><span>选择</span></label>
				<?php } else { ?>
					<span class="check-btn selected">该商品已参加其他活动</span>
				<?php }?>
				</div>
			</div>
			<?php }?>
			<div class="layout paging padding-tb body-bg clearfix">
				<?php echo $pages;?>
			</div>
		</div>
		<div class="padding text-right ui-dialog-footer">
			<input type="submit" class="button margin-left bg-sub" value="确定" />
			<input type="reset" class="button margin-left bg-gray" value="关闭" />
		</div>
		<style>
		.goods-search-class-content {left:-70px;}
		</style>
		<script>
			$(function(){
				try {
					var dialog = top.dialog.get(window);
				} catch (e) {
					return;
				}
				var $val=$("input[type=text]").eq(1).val();
				$("input[type=text]").eq(1).focus().val($val);
				dialog.reset();     // 重置对话框位置

				var multiple = "<?php echo (int) $_GET['multiple'] ?>"; // 定义本次页面是否允许多项选择
				var selected = dialog.selected; // 接收页面传来的已传值
				var removeids = dialog.removeids //移除商品id

				_selected();

				/* 执行点击事件 */
				$("label.button").live('click', function() {
					var $_this = $(this).parents('.tr'),
						$_id = $_this.data('id');
					if($(this).hasClass('bg-sub') == true) {
						$(this).removeClass("bg-sub").addClass("bg-gray").find('span').text("选择");
						/* 如果仅允许单选 */
						if(multiple == 0) {
							$(this).parents(".tr").siblings().find('label.button').removeClass('bg-sub').addClass('bg-gray').find('span').text("选择");
						}
					} else {
						$(this).removeClass("bg-gray").addClass("bg-sub").find('span').text("已选");
						/* 如果仅允许单选 */
						if(multiple == 0) {
							$(this).parents(".tr").siblings().find('label.button').removeClass('bg-sub').addClass('bg-gray').find('span').text("选择");
						}
					}
					_callback($_id);
				})

				function _selected() {
					var _selected = '<label class="check-btn button bg-sub"><span>已选</span></label>';
					var _checked = '<label class="check-btn button bg-gray"><span>选择</span></label>';
					if(multiple == 0) {
						$(".tr[data-id='"+ selected.id +"']div:last .w15").html(_selected);
					} else {
						//移除商品
						if(removeids){
							$.each(removeids,function(i,n){
								$(".tr[data-id='"+ i +"'] div:last").html(_checked);
							});
						}
						//选中商品
						$.each(selected, function(i ,n){
							$(".tr[data-id='"+ i +"'] div:last").html(_selected);
						});
					}
				}
				function _callback(id) {
					id = id || 0;
					var $_this = $(".tr[data-id='"+ id +"']");
					if($_this.length > 0) {
						var	$_val = {}
						if($_this.find("label.button").hasClass('bg-sub')) {
							$_val = {
								"id" : $_this.data('id'),
								"condition" : $_this.data('condition'),
								"name" : $_this.data('name'),
								"time" : $_this.data('time'),
								"discount" : $_this.data('discount'),
								"typebuy" : $_this.data('typebuy'),
							}
						}
						if(multiple == 0) {
							selected = $_val;
						} else {
							selected[id] = $_val;
						}
					}

					var callback = {};
					if(multiple == 1) {
						$.each(selected, function(i ,n) {
							if($.isEmptyObject(n) == false) {
								callback[i] = n;
							}
						})
						selected = callback;
					}
					return selected;
				}
				$('input[type=submit]').on('click', function () {
					dialog.close(_callback());
					dialog.remove();
					return false;
				});
				$('input[type=reset]').on('click', function () {
					dialog.remove();
					return false;
				});


				$('.select-search-field').click(function(e){
					e.stopPropagation();
				});

				//buttonedit-popup-hover
				$('.select-search-text-box .form-buttonedit-popup').click(function(){
					if($(this).hasClass('buttonedit-popup-hover')){
						$(this).nextAll('.select-search-field').show();
						$(this).nextAll('.select-search-field').children('.input').focus();
						$(this).nextAll('.listbox-items').show();
					}else{
						$(this).nextAll('.select-search-field').hide();
						$(this).nextAll('.listbox-items').hide();
					}
				});

			
				$(".select-search-text-box .listbox-items .listbox-item").live('click',function(){
					$(this).parent().prev('.select-search-field').children('.input').val();
					$(this).parent().prev('.select-search-field').hide();
					$('.select-search-text-box .form-buttonedit-popup .input').val($(this).html());
					$('input[name=brand_id]').val($(this).attr('data-val'));
				});
				$('body').click(function(){
					$('.select-search-text-box .select-search-field').hide();
					$('.select-search-text-box .listbox-items').hide();
				});


				$('.goods-search-class-wrap .form-buttonedit-popup').click(function(){
					if($('.goods-search-class-content').hasClass('hidden')){
						$(this).addClass("buttonedit-popup-hover");
						$('.goods-search-class-content').removeClass('hidden');
					}else{
						$(this).removeClass("buttonedit-popup-hover");
						$('#confirm-class').trigger('click');
						$('.goods-search-class-content').addClass('hidden');
					}
				});
				$('.goods-add-class .root a, .goods-add-class .child a').live('click',function(){
					//在下方已选择分类显示
					$('.goods-search-class-wrap .goods-class-choose span').html(classNameText());
					$('input[name=catid]').val(classId());
				});
				$('#confirm-class').click(function(){
					if(classNameText()==""){
						$('.goods-search-class-wrap .form-buttonedit-popup .input').val("请选择分类");
					}else{
						$('.goods-search-class-wrap .form-buttonedit-popup .input').val(classNameText());
					}
					$('.goods-search-class-wrap .form-buttonedit-popup').removeClass("buttonedit-popup-hover");
					$('.goods-search-class-content').addClass('hidden');
				});

				 //格式化分类
				jsoncategory = <?php echo json_encode($category) ?> ;
			    nb_category(0, '.root');

				$('#confirm-class').click(function(){
					if(classNameText()==""){
						$('.goods-search-class-wrap .form-buttonedit-popup .input').val("请选择分类");
					}else{
						$('.goods-search-class-wrap .form-buttonedit-popup .input').val(classNameText());
					}
					$('.goods-search-class-wrap .form-buttonedit-popup').removeClass("buttonedit-popup-hover");
					$('.goods-search-class-content').addClass('hidden');
				});
			function ajax_lists(){
					$_form = $('form[name=sku_search]');
					var url = $_form.attr("action");
					var parmas = $_form.serializeArray();
					var prom_type = '<?php echo $_GET['type']?>';
					$.get(url,parmas,function(ret){
						$('.table .tr:gt(0)').remove();
						$('.body-bg').html('');
						if(ret.count > 0){
							var goodsRowHtml = template('skus_template', {'lists': ret.lists, 'selected':selected, 'prom_type':prom_type});
							$('.table .tr').after(goodsRowHtml);
							$('.body-bg').html(ret.pages);
							_selected();
						}else{
							$('.table .tr').append('<div class="tr">很抱歉，没有查询到相关商品！</div>')
						}
					},'json')
				}
				//ajax分页查询
				$('.body-bg a').live('click', function() {
					var page = $.urlParam('page', $(this).attr('href'));
					$("input[name=page]").attr("value", page);
					ajax_lists()
					return false;
				})
				//获取分页的id
				$.urlParam = function(name, url){
				    var url = url || window.location.href;
				    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(url);
				    if(!results) return false;
				    return results[1] || 0;
				}
				//组织分类名称
				function classNameText(){
					var _txt = '';
					$('.goods-add-class div.focus').each(function(){
						if($(this).find("a.focus").html()!=null){
							if($(this).index()==0){
								_txt += $(this).find("a.focus").html();
							}else{
								_txt += '>'+$(this).find("a.focus").html();
							}
						}
					})
					return _txt;
				}
				function classId(){
					var _txt = '';
					$('.goods-add-class div.focus').each(function(){
						if($(this).find("a.focus").html()!=null){
							_txt = $(this).find("a.focus").attr('data-id');
						}
					})
					return _txt;
				}
			})
		</script>
<?php include template('footer','admin');?>
