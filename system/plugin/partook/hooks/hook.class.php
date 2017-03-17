<?php

class plugin_partook_hook extends plugin
{

    /**
     * 使用百度分享代码
     */
    public function detail_behind_album()
    {
        $partook = cache('partook_status', '', 'plugin');
        $html = '';
        if ($partook['status'] == 1) {
            $html = '
<div class="bdsharebuttonbox">';
            if ($partook['qzone'] == 1) {
                $html .= '<a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
';
            };
            if ($partook['tsina'] == 1) {
                $html .= '<a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
';
            };
            if ($partook['tqq'] == 1) {
                $html .= '<a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
';
            };
            if ($partook['weixin'] == 1) {
                $html .= '<a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
';
            };
            if ($partook['douban'] == 1) {
                $html .= '<a href="#" class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a>
';
            };
            if ($partook['renren'] == 1) {
                $html .= '<a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a>
';
            };
            if ($partook['linkedin'] == 1) {
                $html .= '<a href="#" class="bds_linkedin" data-cmd="linkedin" title="分享到linkedin"></a>';
            };
            $html .= '</div><script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName(\'head\')[0]||body).appendChild(createElement(\'script\')).src=\'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=\'+~(-new Date()/36e5)];</script>';

            /*$html .='<style>';
            $html .='.ishare{padding-right:4px;}';
            $html .='.share-box .icon-share{width:16px;height: 17px;display: inline-block; margin-top:4px;margin-left: 7px;cursor: pointer;}';
            $html .='.icon-share{background: url(system/plugin/partook/images/icon_share%20.png);}';
            $html .='.icon-qzone{background-position: 0px -51px;}';
            $html .='.icon-tsina{background-position: 0px -103px;}';
            $html .='.icon-tencent{background-position: 0px -259px;}';
            $html .='.icon-wechat{background-position: 0px -1611px;}';
            $html .='.icon-douban{background-position: 0px -467px;}';
            $html .='.icon-renren{background-position: 0px -207px;}';
            $html .='.icon-linkedin{background-position: 0px -1663px;}';
             $html .='</style>';
            $html .='<script type="text/javascript" src="'.__ROOT__.'template/default/statics/js/share.js" ></script>';
            $html .='<span class="ishare margin-big-left margin-small-top fl">分享</span>';
            $html .='<div class="share-box fl bdsharebuttonbox" data-tag="share_1" >';
            if($partook['qzone']==1){$html .='<a class="icon-share icon-qzone" data-cmd="qzone"><a>';};
            if($partook['tsina']==1){$html .='<a class="icon-share icon-tsina" data-cmd="tsina"><a>';};
            if($partook['tqq']==1){$html .='<a class="icon-share icon-tencent" data-cmd="tqq"><a>';};
            if($partook['weixin']==1){$html .='<a class="icon-share icon-wechat" data-cmd="winxin"><a>';};
            if($partook['douban']==1){$html .='<a class="icon-share icon-douban" data-cmd="douban"><a>';};
            if($partook['renren']==1){$html .='<a class="icon-share icon-renren" data-cmd="renren"><a>';};
            if($partook['linkedin']==1){$html .='<a class="icon-share icon-linkedin" data-cmd="linkedin"><a>';};
            $html .='</div>';
            $html .='<script type="text/javascript">$(function(){var url=window.location.href;var title=$(document).attr("title");window._bd_share_config = {common : {bdText : title,bdDesc : title,bdUrl : url,bdPic : "",},share : [{"bdSize" : 16}],selectShare : [{"bdselectMiniList" : [\'qzone\',\'tsina\',\'tqq\',\'weixin\',\'douban\',\'renren\',\'linkedin\']}]}});</script>';
            $html .='<script type="text/javascript">$(function(){$(".bdsharebuttonbox").find("a").addClass(\'hidden\');$(".bdsharebuttonbox").find("a.icon-share").removeClass(\'hidden\');});</script>';
            $html .='<script type="text/javascript">$(function(){with(document)0[(getElementsByTagName(\'head\')[0]||body).appendChild(createElement(\'script\')).src=\'http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion=\'+~(-new Date()/36e5)];});</script>';*/
        }
        echo $html;
    }

}