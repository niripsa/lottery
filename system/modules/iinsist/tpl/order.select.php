<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.header-data li{ line-height:40px;}
.soso_message{ text-align:center; height:80px; line-height:80px;  border-top:5px solid #3c8dbc;border-bottom:5px solid #3c8dbc;}
</style>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ment ); ?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
    <li> 结算金额：<?php echo $settle_money; ?> </li>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
    <li>
    订单号查询：
    <input type="type" id="ocode" name="ocode" class="input-text wid150" value="<?php echo $search['ocode'] ? : ''; ?>" />
    </li>

    <li>用户查询&nbsp;&nbsp;&nbsp;：
    <select name="user" id="user" style="width:100px">
        <option value="ouid" <?php echo $search['user'] == "ouid" ? 'selected' : ''; ?> > 用户UID </option>
        <option value="ou_name" <?php echo $search['user'] == "ou_name" ? 'selected' : ''; ?> > 用户名称 </option>
        <option value="manage1_id" <?php echo $search['user'] == "manage1_id" ? 'selected' : ''; ?> > 一级管理商UID </option>
        <option value="manage2_id" <?php echo $search['user'] == "manage2_id" ? 'selected' : ''; ?> > 二级管理商UID </option>
        <option value="manage3_id" <?php echo $search['user'] == "manage3_id" ? 'selected' : ''; ?> > 三级管理商UID </option>
    </select>
    <input type="type" id="user_val" name="user_val" class="input-text wid200" value="<?php echo $search['user_val'] ? : ''; ?>" />
    </li>

    <li>商品查询&nbsp;&nbsp;&nbsp;：
    <select name="goods" id="goods" style="width:100px">
        <option value="ogid"> 商品 ID </option>
    </select>    
    <input type="type" id="ogid" name="ogid" class="input-text wid200" value="<?php echo $search['ogid'] ? : ''; ?>" />
    </li>

    <li>
        购买日期:
        <input type="text" id="start_otime" name="start_otime" class="input-text posttime" value="<?php echo $search['start_otime'] ? : ''; ?>" readonly="readonly" />
        &nbsp; <===> &nbsp;
        <input type="text" id="end_otime" name="end_otime" class="input-text posttime" value="<?php echo $search['end_otime'] ? : ''; ?>" readonly="readonly" />
        <script type="text/javascript">
            date = new Date();
            Calendar.setup({
                inputField     :    "start_otime",
                ifFormat       :    "%Y-%m-%d %H:%M:%S",
                showsTime      :    true,
                timeFormat     :    "24"
            });
            Calendar.setup({
                inputField     :    "end_otime",
                ifFormat       :    "%Y-%m-%d %H:%M:%S",
                showsTime      :    true,
                timeFormat     :    "24"
            });
        </script>
    </li>
    <input type="button" class="button" onclick="search();" value=" 搜 索 ">
    <input type="button" class="button" onclick="location.href = '<?php echo $url; ?>'" value=" 撤 销 " />
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<?php if ( is_array( $record ) ) { ?>
<!-- Start -->
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">订单号</th>
            <th align="center">商品标题</th>
            <th align="center">购买次数</th>
            <th align="center">购买总价</th>
            <th align="center">购买日期</th>
            <th align="center">订单状态</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <?php foreach ( $record as $v ) { ?>
    <tbody>
        <tr>
        <td align="center"> <?php echo $v["ocode"]; ?> 
        <?php if ( $v["code_tmp"] ) { echo " <font color='#ff0000'>[多]</font>"; } ?> </td>
        <td align="center">(第 <?php echo $v["oqishu"]; ?> 期) <?php echo _strcut(_unser($v["og_title"], "g_title"), 0, 25); ?> </td>
        <td align="center"><?php echo $v["onum"]; ?>人次</td>
        <td align="center">￥<?php echo $v["omoney"]; ?>元</td>
        <td align="center"><?php echo date( "Y-m-d H:i:s", $v["otime"] ); ?></td>
        <td align="center"><?php echo $v["status_txt"]; ?></td>
        <td align="center"><a href="<?php echo G_MODULE_PATH ."/order/detail/". $v["oid"]; ?>">详细</a></td>
        </tr>
    </tbody>
    <?php } ?>
</table>
<!-- End -->
<?php } else { ?>
    <div class="soso_message">
        未搜索到信息.....
    </div>
<?php } ?>
<div id="pages"> <?php echo $page; ?> </div>
</div>

<script type="text/javascript">
/* 搜索 */
function search()
{
    var url = "<?php echo $url; ?>";
    /* 订单号查询 */
    var ocode = $( '#ocode' ).val();
    ocode     = ocode != '' ? ocode : '0';
    /* 用户查询  */
    var user     = $( '#user' ).val();
    var user_val = $( '#user_val' ).val();
    user_val     = user_val != '' ? user_val : '0';
    /* 商品查询 */
    var goods = $( '#goods' ).val();
    var ogid  = $( '#ogid' ).val();
    ogid      = ogid != '' ? ogid : '0';
    /* 购买日期 */
    var start_otime = $( '#start_otime' ).val();
    start_otime     = start_otime != '' ? start_otime : '0';
    var end_otime   = $( '#end_otime' ).val();
    end_otime       = end_otime != '' ? end_otime : '0';

    url = url + '/' + ocode + '/' + user + '/' + user_val + '/' + goods + '/' + ogid + '/' + start_otime + '/' + end_otime;
    location.href = url;
}
</script>

</body>
</html> 