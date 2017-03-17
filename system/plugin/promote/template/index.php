<?php include template('header', 'admin'); ?>
<script type="text/javascript" src="./statics/js/goods/goods_list.js"></script>
<div class="fixed-nav layout">
    <ul>
        <li class="first">插件配置</li>
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
            <p>- 第一次使用前，请务必手动更新缓存 !</p>
            <p>- 生成链接后，复制弹出框中的链接！</p>
        </div>
    </div>
    <div class="table-work border margin-tb">
        <div class="border border-white tw-wrap">
            <a href="<?php echo url("#adds"); ?>"><i class="ico_add" ></i>新增渠道</a>
            <div class="spacer-gray"></div>

        </div>
    </div>
    <div class="table resize-table paging-table check-table border clearfix">
        <div class="tr">
            <span class="th check-option" data-resize="false">
                <span></span>
            </span>
            <span class="th" data-width="15">
                <span class="td-con">渠道名称</span>
            </span>
            <span class="th" data-width="15">
                <span class="td-con">渠道标识</span>
            </span>
            <span class="th" data-width="8">
                <span class="td-con">点击量</span>
            </span>
            <span class="th" data-width="8">
                <span class="td-con">会员注册数</span>
            </span>
            <span class="th" data-width="8">
                <span class="td-con">订单商品量</span>
            </span>
            <span class="th" data-width="8">
                <span class="td-con">订单量</span>
            </span>
            <span class="th" data-width="8">
                <span class="td-con">成交商品量</span>
            </span>
            <span class="th" data-width="8">
                <span class="td-con">成交订单量</span>
            </span>
            <span class="th" data-width="9">
                <span class="td-con">微信点击量</span>
            </span>
            <span class="th" data-width="13">
                <span class="td-con">操作</span>
            </span>
        </div>
        <?php foreach ($lists AS $v) { ?>
            <div class="tr">
                <div class="td check-option"></div>
                <span class="td">
                    <span class="td-con"><?php echo $v['name'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['identity'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['click_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['member_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['good_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['order_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['dealgood_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['dealorder_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><?php echo $v['wx_num'] ?></span>
                </span>
                <span class="td">
                    <span class="td-con"><a href="javascript:;" class="shengc" name="<?php echo $v['identity'] ?>">生成</a> | <a data-message="是否确定删除所选？" href=javascript:if(confirm('确实要删除吗?'))location="<?php echo url("#del", array('id' => $v['id'])) ?>">删除</a></span>
                </span>
            </div>
        <?php } ?>
        <div class="paging padding-tb body-bg clearfix">
            <?php echo $pages ?>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script>
    $('.shengc').bind('click', function() {
        var id = $(this).attr('name');
        var url = "<?php echo url('goods/index/index', '', TRUE); ?>&promo_code=" + id + "";
        top.dialog({
            title: '推广链接',
            width: 800,
            content:'<input style="padding: 20px;font-size: 12px;margin: 20px 0;" class="input hd-input" type="text" value="'+url+'"><span style="padding: 0 20px;">请复制推广链接给您的推广员，同时我们也推荐一个网站给您，可以帮助您更自由的生成推广二维码：<a target="_blank" style="color:#498BC8" href="http://cli.im/">草料二维码</a></span>',
            okValue: '确定',
            ok: function() {
            }
        })
                .showModal();
        $('input[name=price]').val($('.promo-price em.text-mix').html());
    });
</script>
<script>
    $(".table").resizableColumns();
    $(".table").fixedPaging();

</script>

<?php include template('footer', 'admin'); ?>