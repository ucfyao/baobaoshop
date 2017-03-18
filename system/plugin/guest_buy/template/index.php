<?php include template('header', 'admin'); ?>
<?php include(PLUGIN_PATH . PLUGIN_ID . '/template/common/nav.php'); ?>
<div class="content padding-big have-fixed-nav">
    <?php include(PLUGIN_PATH . PLUGIN_ID . '/template/common/tip.php'); ?>
    <form action="" method="post">
        <?php echo form::input('radio', 'type', $config['type'] ?: 1, '游客密码生成规则：', '', array('items' => $type)); ?>

        <?php echo form::input('text', 'custom', $config['custom'] ?: '123456', '默认生成会员密码：','不建议过于简单，不能少于6个字符，只能使用英文或数字组合。(如果留空则为123456)'); ?>

        <div class="form-group">
            <input type="submit" class="button bg-main" value="提交"/>
            <a class="button margin-left bg-gray" href="">返回</a>
        </div>
    </form>
</div>
<script>
    $(function () {
        var t = "<?php echo $config['type'] ?: 1;?>";
        if (t != 4) $('[name=custom]').parents('.form-group').hide();
    });

    /**
     * 自定义密码文本框状态
     */
    $('input[name=type]').on('change', function () {
        var i = $(this).val();
        if (i == 4) {
            $('[name=custom]').parents('.form-group').show();
        } else {
            $('[name=custom]').parents('.form-group').hide();
        }
    });
</script>


