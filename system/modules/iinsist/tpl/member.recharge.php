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
</style>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ment ); ?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
<div style="margin-bottom:5px;">搜索的总金额数：<span style="color:red;font-weight:bold;"><?php echo $summoeny; ?>元</span></div>
时间搜索: <input type="text" id="posttime1" class="input-text posttime" readonly="readonly" value="<?php echo $search['start_otime'] ? : ''; ?>" /> -  
<input type="text" id="posttime2" class="input-text posttime" readonly="readonly" value="<?php echo $search['end_otime'] ? : ''; ?>" />
<script type="text/javascript">
    date = new Date();
    Calendar.setup({
        inputField     :    "posttime1",
        ifFormat       :    "%Y-%m-%d %H:%M:%S",
        showsTime      :    true,
        timeFormat     :    "24"
    });
    Calendar.setup({
        inputField     :    "posttime2",
        ifFormat       :    "%Y-%m-%d %H:%M:%S",
        showsTime      :    true,
        timeFormat     :    "24"
    });
</script>
<select id="source">
    <option value='0'>请选择充值来源</option>
    <option value='1' <?php echo $search['source'] == '1' ? 'selected' : ''; ?> >通过网络充值</option>
</select>
<select id="user_type">
    <option value="0">请选择用户类型</option>
    <option value="uid" <?php echo $search['user_type'] == "uid" ? "selected" : ''; ?> >用户id</option>
    <option value="username" <?php echo $search['user_type'] == "username" ? "selected" : ''; ?> >用户名称</option>
    <option value="email" <?php echo $search['user_type'] == "email" ? "selected" : ''; ?> >用户邮箱</option>
    <option value="mobile" <?php echo $search['user_type'] == "mobile" ? "selected" : ''; ?> >用户手机</option>
</select>
<input type="text" id="type_val" class="input-text wid100" value="<?php echo $search['type_val'] ? : ''; ?>" />
<input type="button" class="button" onclick="search();" value="搜索" />
<input type="button" class="button" onclick="location.href = '<?php echo $url; ?>'" value=" 撤 销 " />
<input type="button" class="button" onclick="explode()" value=" 导出到EXCEL " />
</div>

<div class="bk10"></div>

<div class="table-list lr10">
<!--start-->
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th width="100px" align="center">用户名</th>
            <th width="100px" align="center">充值金额</th>
            <th width="100px" align="center">充值来源</th>
            <th width="100px" align="center">时间</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( (array)$recharge as $k => $v ) { ?>
        <tr>
            <td align="center"> <?php echo $members[$k]; ?> </td>
            <td align="center"> <?php echo $recharge[$k]["omoney"]; ?> </td>
            <td align="center"> <?php echo $recharge[$k]["oremark"] == "充值" ? "通过网络充值" : $recharge[$k]["oremark"]; ?> </td>  
            <td align="center"> <?php echo date("Y-m-d H:i:s", $recharge[$k]["otime"]); ?> </td>
        </tr>
    <?php } ?>
    </tbody>    
</table>
</div><!--table-list end-->
<div id="pages">
    <ul>
        <li>共 <?php echo $total; ?> 条</li>
        <?php echo $page; ?>
    </ul>
</div>

<script type="text/javascript">
/* 搜索 */
function search()
{
    var url = "<?php echo $url; ?>";

    /* 时间搜索 */
    var posttime1 = $( '#posttime1' ).val();
    posttime1     = posttime1 != '' ? posttime1 : '0';
    var posttime2 = $( '#posttime2' ).val();
    posttime2     = posttime2 != '' ? posttime2 : '0';
    /* 充值来源 */
    var source  = $( '#source' ).val();
    source      = source != '' ? source : '0';
    /* 用户类型 */
    var user_type  = $( '#user_type' ).val();
    user_type      = user_type != '' ? user_type : '0';
    /* 用户类型值 */
    var type_val  = $( '#type_val' ).val();
    type_val      = type_val != '' ? type_val : '0';

    url = url + '/' + posttime1 + '/' + posttime2 + '/' + source + '/' + user_type + '/' + type_val;
    location.href = url;
}

function explode()
{
    var url = "<?php echo G_ADMIN_PATH . "/" . ROUTE_C . "/" . 'explode_excel'; ?>";
    /* 时间搜索 */
    var posttime1 = $( '#posttime1' ).val();
    posttime1     = posttime1 != '' ? posttime1 : '0';
    var posttime2 = $( '#posttime2' ).val();
    posttime2     = posttime2 != '' ? posttime2 : '0';
    /* 充值来源 */
    var source  = $( '#source' ).val();
    source      = source != '' ? source : '0';
    /* 用户类型 */
    var user_type  = $( '#user_type' ).val();
    user_type      = user_type != '' ? user_type : '0';
    /* 用户类型值 */
    var type_val  = $( '#type_val' ).val();
    type_val      = type_val != '' ? type_val : '0';

    url = url + '/' + posttime1 + '/' + posttime2 + '/' + source + '/' + user_type + '/' + type_val;
    location.href = url;
}
</script>

</body>
</html> 