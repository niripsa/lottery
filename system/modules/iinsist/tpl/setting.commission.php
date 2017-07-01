<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/from.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ments ); ?>
</div>
<div class="table-listx lr10">
<!--start-->
<form name="myform" action="" method="post">
<table width="100%" cellspacing="0" style="border: 0px;">
    <tr>
        <td width="220" align="right">佣金金额：</td>
        <td><input type="text" name="commission_amount" value="<?php echo $commission['commission_amount']; ?>" class="input-text" />【设置0关闭佣金】以1块钱为标准，假如设置0.2，那么这两毛钱按照下面的比例分配给分销商，如果不足三层，有几层发几层。</td>
    </tr>
    <tr>
        <td width="220" align="right">一层分佣：</td>
        <td><input type="text" name="commission_1" value="<?php echo $commission['commission_1']; ?>" class="input-text" />第一层分销商佣金比例</td>
    </tr>
    <tr>
        <td width="220" align="right">二层分佣：</td>
        <td><input type="text" name="commission_2" value="<?php echo $commission['commission_2']; ?>" class="input-text" />第二层分销商佣金比例</td>
    </tr>
    <tr>
        <td width="220" align="right">三层分佣：</td>
        <td><input type="text" name="commission_3" value="<?php echo $commission['commission_3']; ?>" class="input-text" />第三层分销商佣金比例</td>
    </tr>
    <tr>
        <td width="220" align="right"></td>
        <td><input type="submit" class="button" name="dosubmit" value=" 提交 " /></td>
    </tr>
</table>
</form>

</div><!--table-list end-->

</body>
</html> 