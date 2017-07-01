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
          <td width="220" align="right">联系人：</td>
          <td><input type="text" name="contact" value="<?php echo $web["contact"]; ?>" class="input-text" /></td>
      </tr>
      <tr>
          <td width="220" align="right">电话：</td>
          <td><input type="text" name="tel" value="<?php echo $web["tel"]; ?>" class="input-text" /></td>
      </tr>
      <tr>
          <td width="220" align="right">邮箱：</td>
          <td><input type="text" name="email" value="<?php echo $web["email"]; ?>" class="input-text" />
              </td>
          </td>
      </tr>
      <tr>
          <td width="220" align="right">地址：</td>
          <td><input type="text" name="addr" value="<?php echo $web["addr"]; ?>" class="input-text" /></td>
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