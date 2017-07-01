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
          <td width="220" align="right">首次注册：</td>
          <td><input type="text" name="first_reg" value="<?php echo $money['first_reg']; ?>" class="input-text" />（元）</td>
      </tr>
      <tr>
          <td width="220" align="right">充值返点：</td>
          <td><input type="text" name="rebate" value="<?php echo $money['rebate']; ?>" class="input-text" />充值金额/100*10的返点</td>
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