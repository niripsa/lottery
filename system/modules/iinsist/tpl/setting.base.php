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
  <input type="hidden" name="tablepre" value="<?php echo $web["tablepre"]; ?>" />
      <tr>
          <td width="220" align="right">网站URL地址：</td>
          <td><input type="text" name="web_path" value="<?php echo $web["web_path"]; ?>" class="input-text" /></td>
      </tr>
      <tr>
          <td width="220" align="right">默认地区ID：</td>
          <td><input type="text" name="default_area_id" value="<?php echo $web["default_area_id"]; ?>" class="input-text"></td>
      </tr>
      <tr>
          <td width="220" align="right">网站LOGO：</td>
          <td><div class="lf"><input type="text" id="imagetext" name="web_logo" value="<?php echo $web["web_logo"]; ?>" class="input-text lh20"></div>
              <div class="button lf" onClick="GetUploadify( '<?php echo WEB_PATH; ?>', 'uploadify', 'LOGO上传', 'banner', 1, 'imagetext' );">上传</div>
          </td>
      </tr>
      <tr>
          <td width="220" align="right">网站全称：</td>
          <td><input type="text" name="web_name" id="web_name" value="<?php echo $web["web_name"]; ?>" class="input-text">
              </td>
          </td>
      </tr>
      <tr>
        <td width="220" align="right">网站短名称：</td>
        <td>
            <input type="text" id="web_name_two" name="web_name_two" value="<?php echo $web["web_name_two"]; ?>" class="input-text">
        </td>
      </tr>
      <tr>
          <td width="220" align="right">网站字符集：</td>
          <td>
              <input type="hidden" name="charset" id="charset_id" value="<?php echo $web["charset"]; ?>" />
              <script language="javascript">yg_select( <?php echo $charset; ?>, "charset_id", "<?php echo $web["charset"]; ?>" );</script>
          </td>
      </tr>
        <tr>
            <td width="220" align="right">网站时区：</td>
            <td>
                <input type="hidden" name="timezone" id="timezone_id" value="<?php echo $web["timezone"]; ?>">
                <script language="javascript">yg_select( <?php echo $timezone; ?>, "timezone_id", "<?php echo $web["timezone"]; ?>" );</script>
            </td>
        </tr>
      <tr>
          <td width="220" align="right">加密KEY：</td>
          <td>
              <input type="text" name="cryptkey" value="<?php echo $web["cryptkey"]; ?>" class="input-text" />
          </td>
      </tr>
      <tr>
          <td width="220" align="right">360SQL安全：</td>
          <td>
              <input type="hidden" name="sqlsafe" id="sqlsafe" value="<?php echo $web["sqlsafe"]; ?>" />
              <script language="javascript">yg_close( "0,1|关闭,开启", "txt", "sqlsafe", "<?php echo $web["sqlsafe"]; ?>" );</script>
          </td>
      </tr>
      <tr>
          <td width="220" align="right">SESSION存储类型：</td>
          <td>
              <input type="text" name="session_storage" value="<?php echo $web["session_storage"]; ?>" class="input-text" />
          </td>
      </tr>
      <tr>
          <td width="220" align="right">SESSION存储周期：</td>
          <td>
              <input type="text" name="session_ttl" value="<?php echo $web["session_ttl"]; ?>" class="input-text">
          </td>
      </tr>
      <tr>
          <td width="220" align="right">SESSION存储路径：</td>
          <td>
              <input type="text" name="session_savepath" value="<?php echo $web["session_savepath"]; ?>" class="input-text" />
          </td>
      </tr>


      <tr>
          <td width="220" align="right">COOKIE作用域：</td>
          <td>
              <input type="text" name="cookie_domain" value="<?php echo $web["cookie_domain"]; ?>" class="input-text" />
          </td>
      </tr>
      <tr>
          <td width="220" align="right">COOKIE路径：</td>
          <td>
              <input type="text" name="cookie_path" value="<?php echo $web["cookie_path"]; ?>" class="input-text" />
          </td>
      </tr>
      <tr>
          <td width="220" align="right">COOKIE前缀：</td>
          <td>
              <input type="text" name="cookie_pre" value="<?php echo $web["cookie_pre"]; ?>" class="input-text" />
          </td>
      </tr>
      <tr>
          <td width="220" align="right">COOKIE生命周期：</td>
          <td>
              <input type="text" name="cookie_ttl" value="<?php echo $web["cookie_ttl"]; ?>" class="input-text" />
          </td>
      </tr>
      <tr>
          <td width="220" align="right">COOKIE加密KEY：</td>
          <td>
              <input type="text" name="cookie_hash" value="<?php echo $web["cookie_hash"]; ?>" class="input-text" />
          </td>
      </tr>

        <tr>
            <td width="220" align="right">开奖动画分钟数:</td>
            <td><input type="text" name="goods_end_time" value="<?php echo $web["goods_end_time"]; ?>" class="input-text" />
            <span>单位(秒),不低于30秒，不大于300秒</span>
            </td>
        </tr>
        <tr>
            <td width="220" align="right">保存错误日志：</td>
            <td>
                <input type="hidden" name="error" id="error" value="<?php echo $web["error"]; ?>" />
                <script language="javascript">yg_close( "0,1|关闭,开启", "txt", "error", "<?php echo $web["error"]; ?>" );</script>
                <span class="lr10 lf">错误信息输出到页面还是保存到日志</span>
                <div class="cl"></div>
            </td>
        </tr>
        <tr>
            <td width="220" align="right">Gzip压缩：</td>
            <td>
                <input type="hidden" name="gzip" id="gzip" value="<?php echo $web["gzip"]; ?>" />
                <script language="javascript">yg_close("0,1|关闭,开启","txt","gzip","<?php echo $web["gzip"]; ?>");</script>
                <span class="lr10 lf">请先确定服务器是否支持gzip，在开启</span>
                <div class="cl"></div>
            </td>
        </tr>               
        <tr>
            <td width="220" align="right">隐藏index.php：</td>
            <td><input type="text" name="index_name" value="<?php echo $web["index_name"]; ?>" class="input-text" />
                <span>如果写了伪静态请<font color="red">留空</font>，支持pathinfo模式填写:<font color="red">index.php</font> 不支持填写: <font color="red">?</font></span>
            </td>
        </tr>
        <tr>
            <td width="220" align="right">客服QQ：</td>
            <td><input type="text" name="qq" value="<?php echo $web["qq"]; ?>" class="input-text" /></td>
        </tr>   
        <tr>
            <td width="220" align="right">QQ群：</td>
            <td><textarea name="qq_qun" class="wid300" style="height:30px"><?php echo $web["qq_qun"]; ?></textarea>
            多个QQ群请用 | 号分割开</td>
        </tr>
        <tr>
            <td width="220" align="right">联系电话:</td>
            <td><input type="text" name="cell" value="<?php echo $web["cell"]; ?>" class="input-text" /></td>
        </tr>           
        <tr>
            <td width="220" align="right">url分隔符号：</td>
            <td><input type="text" name="expstr" value="<?php echo $web["expstr"]; ?>" class="input-text"></td>
        </tr>       <tr>
            <td width="220" align="right">后台管理文件夹：</td>
            <td><input type="text" name="admindir" value="<?php echo $web["admindir"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="220" align="right">网站是否开启：</td>
            <td>
                <input type="hidden" name="web_off" id="web_off" value="<?php echo $web["web_off"]; ?>" />
                <script language="javascript">yg_close( "0,1|关闭,开启", "txt", "web_off", "<?php echo $web["web_off"]; ?>" );</script>
            </td>
        </tr>   
        <tr>
            <td width="220" align="right">关闭原因：</td>
            <td>
            <textarea name="web_off_text" class="wid300" style="height:35px"><?php echo $web["web_off_text"]; ?></textarea>
            </td>
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