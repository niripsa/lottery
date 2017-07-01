<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ment ); ?>
</div>
<div class="bk10"></div>
<div class="table-listx con-tab lr10" id="con-tab">
    <div class="ml10 ft14 fwb">基本选项</div>
    <div name='con-tabv' class="con-tabv">
        <form action="" id="form" method="post" enctype="multipart/form-data">
        <table width="100%">
            <tr>
              <td width="200" class="tar">上级地区：</td>
              <td>
                  <select name="info[area_parent_id]" class="wid150">
                  <option value="">≡ 作为一级地区 ≡</option>
                  <?php echo $categoryshtml; ?>
                  </select>
              </td>
            </tr>
            <tr>
                <td class="tar">地区名称：</td>
                <td><input type="text" name="info[area_name]" class="input-text wid140" value="">
                    <span><font color="#0c0">※ </font>请输入地区名称</span>
                </td>
            </tr>
        </table>
        <div class="table-button lr10">   
            <input type="button" value=" 提交 " onClick="checkform();" class="button">
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function checkform()
{
    var form=document.getElementById('form');
    var error=null;
    if(form.elements[1].value==''){error='请输入地区名称!';}
    if(error!=null){window.parent.message(error,8,2);return false;}
    form.submit();  
}
</script>
</body>
</html> 