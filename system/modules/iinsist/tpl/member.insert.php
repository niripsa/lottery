<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/child_area.js"></script>
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
<div class="bk10"></div>
<div class="table-listx lr10">
<!--start-->
<form name="myform" action="" method="post" enctype="multipart/form-data">
    <table width="100%" cellspacing="0">
        <tr>
            <td width="120" align="right">昵称：</td>
            <td><input type="text" name="username" value="<?php echo $member["username"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">邮箱：</td>
            <td><input type="text" name="email" value="<?php echo $member["email"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">手机：</td>
            <td><input type="text" name="mobile" value="<?php echo $member["mobile"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">密码：</td>
            <td><input type="text" name="password" value="" class="input-text">(不填写默认为原密码)</td>
        </tr>
        <tr>
            <td width="120" align="right">账户金额：</td>
            <td><input type="text" name="money" value="<?php echo $member["money"]; ?>" class="input-text">元</td>
        </tr>
        <tr>
            <td width="120" align="right">用户积分：</td>
            <td><input type="text" name="user_points" value="<?php echo $member["user_points"]; ?>" class="input-text">分</td>
        </tr>        
        <!-- <tr>
            <td width="120" align="right">经验值：</td>
            <td><input type="text" name="jingyan" value="<?php echo $member["jingyan"]; ?>" class="input-text"></td>
        </tr> -->
        <!-- <tr>
            <td width="120" align="right">积&nbsp;&nbsp;分：</td>
            <td><input type="text" name="score" value="<?php echo $member["score"]; ?>" class="input-text"></td>
        </tr> -->
        <tr>
            <td width="120" align="right">邮箱验证：</td>
            <td>
                <input type="hidden" name="emailcode" id="emailcode" value="<?php echo $member["emailcode"]; ?>">
                <script language="javascript">yg_close("-1,1|关闭,开启","txt","emailcode","<?php echo $member["emailcode"]; ?>");</script>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">手机验证：</td>
            <td>
                <input type="hidden" name="mobilecode" id="mobilecode" value="<?php echo $member["mobilecode"]; ?>">
                <script language="javascript">yg_close("-1,1|关闭,开启","txt","mobilecode","<?php echo $member["mobilecode"]; ?>");</script>
            </td>
        </tr>
        <tr>
            <!-- 管理员只能开通 一级管理商（县级管理商） -->
            <td width="120" align="right">管理商等级：</td>
            <td>
                <select name="manage_rank">
                <?php foreach ( (array)$manage_rank_arr as $k => $v ) { ?>
                    <option value="<?php echo $k; ?>" <?php echo $k == $member['manage_rank'] ? 'selected' : ''; ?> > <?php echo $v; ?> </option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">所属地区：</td>
            <td>
            <?php if ( $member['area_name'] ) { ?>
            当前地区：<?php echo $member['area_name']; ?>
            <?php } ?>
            <select id="province_se" onchange="province_change( this.value );">
                <option value=''>请选择</option>
                <?php foreach ( (array)$area_list as $k => $v ) { ?>
                <option value="<?php echo $v['area_id']; ?>"> <?php echo $v['area_name']; ?> </option>
                <?php } ?>
            </select>
            <select id="city_se" onchange="city_change( this.value );">
                <option value=''>请选择</option>
            </select>
            <select id="area_se" name="area_id">
                <option value=''>请选择</option>
            </select>
            </td>          
        </tr>
        <tr class="dsn">
            <td width="120" align="right">头像：</td>
            <td>                
                <img src="<?php echo G_UPLOAD_PATH . "/" . empty($member["img"]) ? "photo/member.jpg" : $member["img"]; ?>" style="height:80px;width:80px;border:1px solid #eee;padding:1px">
                <input type="text" id="imagetext" name="img" value="<?php echo empty($member["img"]) ? "photo/member.jpg" : $member["img"]; ?>" class="input-text wid300">
                <input type="button" class="button" onClick="GetUploadify('<?php echo WEB_PATH; ?>','uploadify','头像上传','user',1,'imagetext')" value="上传头像" />
            </td>
        </tr>
        <tr>
            <td width="120" align="right">银行卡号：</td>
            <td><input type="text" name="card_no" value="<?php echo $member["card_no"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">开户行：</td>
            <td><input type="text" name="bank_account" value="<?php echo $member["bank_account"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">签名：</td>
            <td>
                <textarea style="width:400px;height:100px;" name="qianming"><?php echo $member["qianming"]; ?></textarea>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"></td>
            <td>        
            <input type="submit" class="button" name="submit" value="提交" >
            </td>
        </tr>
</table>
</form>
</div><!--table-list end-->
<script>
function upImage(){
    return document.getElementById('imgfield').click();
}
</script>
</body>
</html> 