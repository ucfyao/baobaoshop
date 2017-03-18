	<?php include template('header', 'admin'); ?>
<script type="text/javascript" src="./statics/js/template.js" ></script>
<div class="fixed-nav layout">
    <ul>
        <li class="first">插件配置</li>
    </ul>
</div>
<div class="content padding-big have-fixed-nav">

    <div class="hr-gray"></div>
    <form action="<?php echo url("#promote");?>" method="post">
        <?php echo form::input('text', 'name', '', '推广渠道名称：', ''); ?>
        <?php echo form::input('text', 'identity', '', '推广渠道标示：', '不填写标示，系统自动生成标示。'); ?>
 
        <div class="form-group">
            <input type="submit" class="button bg-main" value="确定"/>
            <a class="button margin-left bg-gray" href="">返回</a>
        </div>
    </form>
</div>
