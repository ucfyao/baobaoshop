<?php include template('header', 'admin'); ?>
<style>
    .form-group .icon-computer{width: 162px;height:134px;}
    .icon-computer{background:url("system/plugin/testdata/template/image/icon_computer.png") no-repeat center center;}
    /*.button:first-child{float: left;margin-left: 40px;}*/
    .button:last-child{margin-left: 40px;}
</style>
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
            <p>- 海盗云商演示数据插件仅用于系统数据演示添加测试使用。</p>
            <p>- 如果已经在系统录入任何重要数据，请勿使用本插件。</p>
            <p>- 导入演示数据，默认会将站点现有数据清空，请注意。</p>
            <p>- 清空演示数据会一并清空后续添加的所有数据，恢复出厂设置。</p>
            <p>- 首次使用务必手动更新缓存。</p>
        </div>
    </div>
    <div class="hr-gray"></div>

    <form action="" method="post">
        <dl class="form-group margin-top">
            <dt class="fl icon-computer margin-large-right"></dt>
            <dd class="fl padding-large-left">
                <h2 class="margin-big-bottom">
                    欢迎使用海盗云商演示数据插件
                </h2>
                <h5>
                    系统检测到你的系统版本为：Haidao v<?php echo HD_VERSION ?>
                </h5>
                <?php echo form::input('hidden', 'status', ''); ?>
                <button class="button bg-main margin-big-top" name="get">
                    导入测试数据
                </button>
                <button class="button bg-main margin-big-top" name="del">
                    清空测试数据
                </button>
            </dd>
        </dl>
    </form>
    <script>
        $('.button').on('click',function () {
            if(this.name == 'del'){
                if(!confirm('确认清除数据并回复出厂设置？')){
                    return false;
                }
            }
            $('[name=status]').val(this.name);
        })
    </script>
</div>