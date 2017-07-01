<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<style>
body{ background-color:#fff}
.header-data{
    border: 1px solid #3c8dbc;
    zoom: 1;
    background: #FFFCED;
    padding: 8px 10px;
    line-height: 20px;
}
.table-list  tr {
    text-align:center;
}
</style>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ments ); ?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">

加入时间: <input type="text" name="posttime1" id="posttime1" class="input-text posttime" readonly="readonly" value="<?php echo $search['start_time'] ? : ''; ?>" /> -  
<input type="text" name="posttime2" id="posttime2" class="input-text posttime" readonly="readonly" value="<?php echo $search['end_time'] ? : ''; ?>" />
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

用户名：<input type="text" id="username" name="username" class="input-text wid100" value="<?php echo $search['username'] ? : ''; ?>" />
<input type="button" class="button" onclick="search();" value="搜索" />
<input type="button" class="button" onclick="location.href = '<?php echo $url; ?>'" value=" 撤 销 " />

</div>
<div class="bk10"></div>
<form action="" method="post" name="myform">
<div class="table-list lr10">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>用户ID</th>
                <th>用户名</th>
                <th>分销佣金</th>
                <th>加入时间</th>
                <th>管理</th>
            </tr>
        </thead>
        <tbody>
        <?php if ( is_array( $distributor_list ) )  { ?>
        <?php foreach ( (array)$distributor_list as $v ) { ?>
            <tr>
                <td> <?php echo $v['uid']; ?> </td>
                <td> <?php echo $v['username']; ?> </td>
                <td> <?php echo $v['dis_money']; ?> </td>
                <td> <?php echo date( "Y-m-d H:i:s", $v['add_time'] ); ?> </td>
                <td class="action">
                <a href="<?php echo G_ADMIN_PATH; ?>/distributor/distributor_info/<?php echo $v['dis_id']; ?>">
                详细
                </a>|
                <a href="<?php echo G_ADMIN_PATH; ?>/distributor/distributor_list&parent_id=<?php echo $v['uid']; ?>">
                查看下级
                </a>
                </td>
            </tr>
        <?php } } ?>
        </tbody>
    </table>
</form>
<div id="pages">
<ul>
<li>共 <?php echo $total; ?> 条</li>
<?php echo $page; ?>
</ul>
</div>

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
        /* 用户名 */
        var username = $( '#username' ).val();
        username = username != '' ? username : '0';

        url = url + '/' + posttime1 + '/' + posttime2 + '/' + username + '/';
        location.href = url;
    }
</script>
</body>
</html>