<?php _PluginIncludeHTML("index.header"); ?>


<div class="fundCon">
    <div class="fImg">
        <img src="<?php echo G_TEMPLATES_STYLE; ?>/images/welfare_03.jpg" alt="">
        <img src="<?php echo G_TEMPLATES_STYLE; ?>/images/welfare_05.jpg" alt="">
        <img src="<?php echo G_TEMPLATES_STYLE; ?>/images/welfare_07.jpg" alt="">
    </div>
    <div class="fInfo">
        <div class="tit1">
            <s class="welfare"></s>
        </div>
        <div class="step">
            <ul>
                <li class="sTop"></li>
                <li class="scen">
                    <s class="welfare s1"></s>
                    <dl><p>云基金是<?php echo _cfg("web_name_two"); ?>创始人发起成立的以公益事业为主要方向的爱心基金。云基金本着“我为人人，人人为我”的社会责任，向需要帮助的困难人们提供爱心捐助。</p></dl>
                </li>
                <li class="sBottom"></li>
            </ul>
            <ul>
                <li class="sTop"></li>
                <li class="scen">
                    <s class="welfare s2"></s>
                    <dl><p>每位在<?php echo _cfg("web_name_two"); ?>进行分享购物的朋友，您的每次参与都将是为我们的公益事业做出一份贡献。当您每参与1人次<?php echo L("html.key");?>，将由<?php echo _cfg("web_name_two"); ?>出资为云基金筹款0.01元，所筹款项将全部用于云基金。</p></dl>
                </li>
                <li class="sBottom"></li>
            </ul>
            <ul>
                <li class="sTop"></li>
                <li class="scen">
                    <s class="welfare s3"></s>
                    <dl>云基金将会以第1种途径或第2种途径进行使用：<br>
                    1、<?php echo _cfg("web_name_two"); ?>全体员工将组织向身边的公益事业进行捐赠与关怀活动。活动内容包括：资金、所需用品以及探望与协助等，每次捐赠与关怀活动结束后云基金将公布活动详情以及基金详细使用报告。<br>
                    2、云基金通过腾讯公益或壹基金等公益组织进行爱心捐赠。</dl>
                </li>
                <li class="sBottom"></li>
            </ul>
            <ul>
                <li class="sTop"></li>
                <li class="scen">
                    <s class="welfare s4"></s>
                    <dl><p>包括云基金的捐赠活动，我们不定期开展内部全体员工对身边更多公益事业或实时公益事业进行爱心捐赠的社会活动。</p>
                    <p>我们还将不定期邀请幸运者参与并见证我们的基金社会活动，共同为我们的社会责任付出一份爱心与力量。当活动启动前我们会将活动进行公告，您可自愿或自行组织参与，组成<?php echo _cfg("web_name_two"); ?>网大家庭，共同开启活动之行。凡参与社会活动的幸运者均能获得<?php echo _cfg("web_name_two"); ?>网为您精心准备的公益爱心礼品一份。</p></dl>
                </li>
                <li class="sBottom"></li>
            </ul>
        </div>
    </div>

    <div class="fAccount">
        <div class="tit2">
            <s class="welfare"></s>
        </div>
        <div class="value">
            <dt><?php echo _cfg("web_name_two"); ?>公益账户累计总额：</dt>
            <dl>( 其中<?php echo $F['fund_ymoney'] ?>为<?php echo _cfg("web_name_two"); ?>网预先注入启动资金 )</dl>
            <p><b id="pFundMoney"><?php echo $F['fund_cmoney'] ?></b><span>元</span></p>
        </div>
        <dl><img src="<?php echo G_TEMPLATES_STYLE; ?>/images/welfare_15.gif" alt=""></dl>
        <div class="fCon">
        <div class="funding" id="divFundList">
            <!--最新购买记录-->
            <?php
                $order_db = System::load_app_model("order","common");
                $r_record = $order_db -> ready_order('1 order by `otime` desc  limit 0,7',1);
                if(!$r_record){$r_record=array();}
            ?>
            <?php
                foreach($r_record as $record){
            ?>

            <ul>
                <li><a href="<?php echo WEB_PATH;?>/uname/<?php echo idjia($record['ouid']);?>" target="_blank">
                <?php if(get_user_key($record['ouid'],'img','8080')=='null'){?>
                <img id="imgUserPhoto" src="<?php echo G_UPLOAD_PATH;?>/photo/member.jpg.8080.jpg"width="58"height="58"></a>
                <?php }else {?>
                <img id="imgUserPhoto"src="<?php echo G_UPLOAD_PATH;?>/<?php echo  Getuserimg($record['ouid'])?>.8080.jpg" width="58"height="58"></a>
                <?php }?>
                </a></li>
                <li><a href="<?php echo WEB_PATH;?>/uname/<?php echo idjia($record['ouid']);?>" target="_blank">

                </a>刚刚<?php echo L("html.key"); ?><?php echo $record['onum']; ?>人次，贡献<?php echo $record['onum'];?>份爱心</li>
            </ul>
            <?php } ?>
            <!--最新购买记录-->
        </div>

        <div class="btn"><a href="<?php echo WEB_PATH; ?>" title="立即参与<?php echo _cfg("web_name");?> 贡献爱心"><img src="<?php echo G_TEMPLATES_STYLE; ?>/images/btn_41.gif" border="0" alt=""></a></div>
    </div>
</div>
</div>

<?php _PluginIncludeHTML("index.footer"); ?>

