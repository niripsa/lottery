<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css" />
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css" />
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE;?>/global/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo G_TEMPLATES_JS;?>/cloud-zoom.min.js"></script>
<script type="text/javascript" src="<?php echo G_TEMPLATES_JS;?>/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo G_PLUGIN_PATH;?>/layer/layer.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ment ); ?>
    <span class="lr10"> </span><span class="lr10"> </span>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">期号</th>
            <th align="center">本期购买单大的数量</th>
            <th align="center">本期购买单小的数量</th>
            <th align="center">本期购买双大的数量</th>
            <th align="center">本期购买双小的数量</th>
            <th align="center">开奖时间</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( (array)$huizong_info as $v ) { ?>
        <tr>
            <td align="center"> 
                <?php echo $stage_no; ?> 
            </td>
            <td align="center">
                <?php echo $huizong_info[1]; ?> 
            </td>
            <td align="center">
                <?php echo $huizong_info[2];?>
            </td>
            <td align="center">
                <?php echo $huizong_info[3];?>
            </td>
            <td align="center">
                <?php echo $huizong_info[4]; ?>
            </td>
            <td align="center">
                <?php echo $kj_time;?>
            </td>
        </tr>
    <?php break; } ?>
    </tbody>
</table>

</div>
</body>
<script type="text/javascript">
   $("#submit").click(function(){
        var number = parseInt($("#setting_number").val());
        if(!number || number < 0 || number > 9){
            layer.alert("请输入0-9之间的数字!");
            layer.alert("请输入0-9之间的数字!");
            return false;
        }

        $.ajax({
            url:"/?/iinsist/lottery/modify_lottery_no",
            type:"post",
            dataType:"json",
            data:{number: number},
            success:function(data){
                $("#setting_number").val(data.number);
                alert("设置成功!");
                window.location.reload(true);
            },
            error:function(data){
                $("#setting_number").val(-1);
                alert("设置失败!");
            }
        });
   });
</script>
</html>