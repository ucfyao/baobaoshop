<?php include template('header','admin'); ?>
<script type="text/javascript" src="./statics/js/template.js" ></script>
<div class="fixed-nav layout">
	<ul>
		<li class="first">虚拟销量管理<a id="addHome" title="添加到首页快捷菜单">[+]</a></li>
		<li class="spacer-gray"></li>
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
			<p>- 执行此操作将自动添加虚拟销量到商品</p>
		</div>
	</div>
	<div class="hr-gray"></div>
	<form class="addfrom" name="form1" id="form1" action="" method="post">
	<dl class="gzzt clearfix mt10">
		<dd>
			<div class="time fl">
				<?php echo form::input('calendar', 'start_time', format_time(time()), '开始时间：', '商品销售开始时间。（默认为当前时间）'); ?>
				<?php echo form::input('calendar', 'end_time', format_time(time()+60*60*24*7), '结束时间：', '商品销售结束时间。（默认为当前时间7天後）'); ?>
			</div>
		</dd>
	</dl>
	<div class="youhui mt10">
		<ul>
            <li class="borm clearfix">
				<?php echo form::input('text', 'order_number', '5', '每件商品本次生成销售订单数量：', '销售数量必须小于100。'); ?>
				<?php echo form::input('text', 'goods_number', '1', '每件商品每笔订单生成购买数量：', '购买数量必须小于10。'); ?>
			</li>
			<li class="clearfix">
				 <?php echo form::input('textarea', 'names', 'jack,rose','会员名称列表：','必须使用英文逗号【,】隔开。'); ?>
            </li>
		</ul>
	</div>

            <div class="padding">
                <div class="table resize-table high-table border clearfix" data-model="sku_list">
                    <div class="table-add-top">
                        <div class="th layout">
                            <a class="text-sub text-left" href="javascript:;" data-event="add_skulist"><em class="ico_add margin-right"></em>选择虚拟销售商品</a>
                        </div>
                    </div>
                    <div class="tr border-none">
                        <div class="th" data-width="45">
                            <span class="td-con">商品名称</span>
                        </div>
                        <div class="th" data-width="20">
                            <span class="td-con">价格</span>
                        </div>
                        <div class="th" data-width="20">
                            <span class="td-con">库存</span>
                        </div>
                        <div class="th" data-width="15">
                            <span class="td-con">操作</span>
                        </div>
                    </div>
                </div>
            </div>

        <script type="text/javascript">
            function delNewAttr(self){
                if (!confirm("确认删除？")) {
                    return false;
                }
                $(self).parent().parent('.tr').remove();
            }
            $(function(){
                var $val=$("input[type=text]").first().val();
                $("input[type=text]").first().focus().val($val);
                $('.resize-table').resizableColumns();
                $("a[data-event='add_rule']").live('click', function(){
                    var _indent = $("[data-model='rules']").find("[data-id]:last").data('id');
                    var _html = template('rules_template', {"i" : (_indent + 1)});
                    $(this).parents("div.spec-add-button").before(_html);

                })
                var removeids = {};
                $("[data-event='del_sku']").live('click',function(){
                    if(confirm("是否确认删除？")){
                        var sku_id = $(this).parents(".tr[data-skuid]").data('skuid');
                        removeids[sku_id] = {'removeid':sku_id};
                        $(this).parents(".tr[data-skuid]").remove();
                    }
                    return false;
                });

                $("select[name^=rules]").live("change", function(){
                    var _val = $(this).val();
                    var _id = $(this).parents(".tr[data-id]").data('id');
                    var _html = template('rule_' + _val, {"i" : _id});
                    $(this).parents(".tr[data-id]").find("div[data-model='rule']").html(_html);
                })

                /* 选择商品 */
                $("a[data-event='add_skulist']").live("click", function(){
                    var selected = {};
                    $("div[data-model='sku_list']").find("[data-skuid]").each(function(i ,n){
                        var sku_id = $(this).data('skuid');
                        selected[sku_id] = {
                            id : sku_id,
                            number : $(this).data('number'),
                            pic : $(this).data('thumb'),
                            price : $(this).data('price'),
                            spec : $(this).data('spec'),
                            title : $(this).data('title')
                        }
                    })
                    top.dialog({
                        url: "<?php echo url('goods/sku/select', array('multiple' => 1))?>",
                        title: '销售商品',
                        width: 980,
                        removeids:removeids,
                        selected:selected,
                        onclose: function () {
                            if(this.returnValue){
                                var _html = template('template_skulist', {'skulist' : this.returnValue});
                                $("div[data-model='sku_list']").find("[data-skuid]").remove();
                                $("div[data-model='sku_list']").find('.border-none').after(_html);
                            }
                        }
                    })
                        .showModal();
                    return false;
                })

            })
        </script>
        <script id="template_skulist" type="text/html">
            <%for(var id in skulist){%>
            <%sku = skulist[id]%>
            <div class="tr" style="visibility: visible;" data-skuid="<%=sku['id']%>" data-number="<%=sku['number']%>" data-thumb="<%=sku['pic']%>" data-title="<%=sku['title']%>" data-spec="<%=sku['spec']%>" data-price="<%=sku['price']%>">
                <div class="td w45">
                    <div class="td-con td-pic text-left">
                        <div class="pic"><img src="<%=sku['pic']%>" /></div>
                        <div class="title">
                            <p class="text-ellipsis padding-small-left"><%=sku['title']%></p>
                        </div>
                        <div class="icon">
                            <p class="text-ellipsis"><span class="text-sub">商品类型:</span><%=sku['spec']%></p>
                        </div>
                    </div>
                </div>
                <div class="td w20">
                    <div class="td-con">￥<%=sku['price']%></div>
                </div>
                <div class="td w20">
                    <div class="td-con"><%=sku['number']%></div>
                </div>
                <div class="td w15">
                    <div class="td-con"><a href="javascript:;" data-event="del_sku">移除</a></div>
                </div>
            </div>
            <input type="hidden" name="sku_ids[]" value="<%=sku['id']%>"/>
            <% }%>
        </script>
		<div class="padding">
			<input type="submit" class="button bg-main" value="生成" />
			<a class="button margin-left bg-gray" href="">返回</a>
		</div>
	</form>
</div>

<?php
function format_time($time)
{
	return date('Y-m-d H:i',$time);
}