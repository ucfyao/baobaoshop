<?php include template('header', 'admin'); ?>
<script type="text/javascript" src="./statics/js/template.js"></script>
<div class="fixed-nav layout">
    <ul>
        <li class="first">插件配置</li>
    </ul>
</div>
<div class="content padding-big have-fixed-nav">

    <div class="hr-gray"></div>
    <form action="" method="post">
        <?php echo form::input('calendar', 'start_time', $config['start_time'] ? date('Y-m-d H:i:s', $config['start_time']) : '', '开始时间：', '开始时间'); ?>
        <?php echo form::input('calendar', 'end_time', $config['end_time'] ? date('Y-m-d H:i:s', $config['end_time']) : '', '结束时间：', '结束时间'); ?>
        <?php echo form::input('text', 'exp', $config['exp'] ?: 0, '经验：', '设置赠送经验数，0则不赠送。'); ?>
        <?php echo form::input('text', 'money', $config['money'] ?: 0, '现金：', '设置赠送现金数，将以余额形式发放到用户账户内，0则不赠送。'); ?>

        <?php if ($config['iscoupon']): ?>
            <div style="margin-top: 10px;">
                <strong>请选择赠送的优惠券</strong>
            </div>
            <div class="padding">
                <div class="table-work border margin-tb">
                    <div class="border border-white tw-wrap">
                        <a class="choose-goods" href="javascript:;"><i class="ico_add"></i>选择优惠券</a>
                        <div class="spacer-gray"></div>

                        <div class="spacer-gray"></div>
                    </div>
                </div>
                <div class="table resize-table border high-table clearfix">
                    <div class="tr border-none" style="visibility: visible">
                        <div class="th w20" data-width="30">
                            <span class="td-con">优惠券名称</span>
                        </div>
                        <div class="th w25" data-width="20">
                            <span class="td-con">过期时间</span>
                        </div>
                        <div class="th w10" data-width="20">
                            <span class="td-con">满足金额</span>
                        </div>
                        <div class="th w10" data-width="15">
                            <span class="td-con">面值</span>
                        </div>
                        <div class="th w10" data-width="20">
                            <span class="td-con">仅原价可用</span>
                        </div>
                        <div class="th w15" data-width="15">
                            <span class="td-con">操作</span>
                        </div>
                    </div>
                    <script id="prom_template" type="text/html">
                        <%for(var item in templateData){%>
                        <%item = templateData[item]%>
                        <div class="tr sku_lists" style="visibility: visible" data-skuid="<%=item['id']%>">
                            <div class="td w20"><%=item['name']%></div>
                            <div class="td w25 ">
                                <div class="td-con"><%=item['time']%></div>
                            </div>
                            <div class="td w10 ">
                                <div class="td-con"><%=item['condition']%></div>
                            </div>
                            <div class="td w10 ">
                                <div class="td-con">
                                    <%=item['discount']%>
                                </div>
                            </div>
                            <div class="td w10 ">
                                <div class="td-con"><%=item['typebuy']%>
                                    <input name="coupon[<%=item['id']%>][typebuy]" type="hidden"
                                           value="<%=item['typebuy']%>"/>
                                    <input name="coupon[<%=item['id']%>][discount]" type="hidden"
                                           value="<%=item['discount']%>"/>
                                    <input name="coupon[<%=item['id']%>][name]" type="hidden"
                                           value="<%=item['name']%>"/>
                                    <input name="coupon[<%=item['id']%>][time]" type="hidden"
                                           value="<%=item['time']%>"/>
                                    <input name="coupon[<%=item['id']%>][condition]" type="hidden"
                                           value="<%=item['condition']%>"/>
                                    <input name="coupon[<%=item['id']%>][id]" type="hidden" value="<%=item['id']%>"/>
                                </div>
                            </div>
                            <div class="td w15">
                                <div class="td-con"><a class="remove-tr" href="">移除</a></div>
                            </div>
                        </div>
                        <% }%>
                    </script>
                </div>
            </div>
        <?php endif ?>
        <div class="form-group">
            <input type="submit" class="button bg-main" value="确定"/>
            <a class="button margin-left bg-gray" href="">返回</a>
        </div>
    </form>
</div>
<script>
    $(window).load(function () {
        <?php if (isset($config['coupon'])): ?>
        $('.table .tr:gt(0)').remove();
        var info = <?php echo json_encode($config['coupon']) ?> ;
        console.log(info);
        var goodsRowHtml = template('prom_template', {'templateData': info});
        $('.table .tr').after(goodsRowHtml);
        <?php endif ?>

        //移除
        var removeids = {};
        $(".remove-tr").live('click', function () {
            var sku_id = $(this).parents(".tr").data('skuid');
            removeids[sku_id] = {'removeid': sku_id};
            $(this).parents(".tr").remove();
            return false;
        });
        $(".choose-goods").live('click', function () {
            <?php if (!$config['iscoupon']): ?>
            alert("请先开启优惠券模块!");
            return false;
            <?php endif ?>
            var data = {};
            $('.sku_lists').each(function (i, item) {
                var params = {
                    id: $(this).find(".time div").attr('data-id'),
                    name: $(this).find(".price div").attr('data-name'),
                    condition: $(this).find(".price div").attr('data-condition'),
                    time: $(this).find(".price div").attr('data-time'),
                    discount: $(this).find(".price div").attr('data-discount'),
                    typebuy: $(this).find(".price div").attr('data-typebuy')

                }
                data[$(this).find(".price div").attr('data-id')] = params;
            })
            top.dialog({
                url: '<?php echo url('coupon/coupon/select_coupon', array('multiple' => 1))?>',
                title: '请选择优惠券',
                removeids: removeids,
                selected: data,
                width: 980,
                onclose: function () {
                    console.log(this.returnValue);
                    if (this.returnValue) {
                        $('.table .tr:gt(0)').remove();
                        var goodsRowHtml = template('prom_template', {'templateData': this.returnValue});
                        $('.table .tr').after(goodsRowHtml);
                    }
                }
            })
                .showModal();
        })
    })
</script>