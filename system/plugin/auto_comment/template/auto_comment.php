<?php include template('header', 'admin'); ?>
<style>
    .form-group .icon-computer{width: 162px;height:134px;}
    .icon-computer{background:url("system/plugin/auto_comment/template/image/icon_computer.png") no-repeat center center;}
    /*.button:first-child{float: left;margin-left: 40px;}*/
    .button:last-child{margin-left: 40px;}
</style>
<div class="fixed-nav layout">
    <ul>
        <li class="first">插件配置</li>
        <li class="spacer-gray"></li>
        <li><a <?php if (empty($_GET['setting'])){echo 'class="current"';}?> href="<?php echo url('admin/app/module',array('mod'=>'auto_comment')); ?>">首页</a></li>
        <li><a <?php if ($_GET['setting']){echo 'class="current"';}?> href="<?php echo url('admin/app/module',array('mod'=>'auto_comment','action'=>'setting')); ?>">设置</a></li>
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
            <p>- 手动设置自动好评天数，默认为7天。</p>
            <p>- 自动好评的商品订单为收货后符合设置天数，并且未做评价的订单。</p>
        </div>
    </div>
    <div class="hr-gray"></div>

    <form action="" method="post" id="editForm">
        <dl class="form-group margin-top">
            <dt class="fl icon-computer margin-large-right"></dt>
            <dd class="fl padding-large-left">
                <h2 class="margin-big-bottom">
                    欢迎使用海盗云商自动好评插件
                </h2>
                <h5>
                    系统检测到你的系统版本为：Haidao v<?php echo HD_VERSION ?>
                </h5>
                <br>
                <h3 class="margin-big-bottom">
                您当前有<b><?php echo $lists['count'];?></b>个订单未评价
                </h3>
                <?php echo form::input('hidden', 'status', ''); ?>
                <!-- <button class="button bg-main margin-big-top" name="auto_comment">
                    设置
                </button> -->
                <button class="button bg-main margin-big-top" name="auto_comment">
                    自动好评
                </button>
            </dd>
        </dl>
    </form>
    <script>
        $('.button').on('click',function () {
            if(this.name == 'auto_comment'){
                if(!confirm('确定一键好评')){
                    return false;
                }
            }
            $("#editForm").submit();
            $('[name=status]').val(this.name);
        })

    </script>
</div>