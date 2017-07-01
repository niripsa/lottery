<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ments ); ?>
</div>
<div class="bk10"></div>

<div class="header-data lr10">
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/day_new'" value=" 今日新增 ">    
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/day_shop'" value=" 今日消费 ">
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/noreg'" value=" 未认证 ">
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/del'" value=" 已删除 "> 
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/rank1'" value=" 一级 "> 
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/rank2'" value=" 二级 "> 
    <span class="lr5"></span>
    <input class="button" type="button" onClick="window.location.href='<?php echo $this_path; ?>/rank3'" value=" 三级 "> 
    <span class="lr5"></span>
    排序:
    <select id="user_paixu">
    <?php foreach ( (array)$user_ziduan_arr as $k => $v ) { ?>
        <option <?php echo $user_ziduan == $k ? 'selected' : ''; ?> value="<?php echo $k; ?>"> <?php echo $v; ?> </option>
    <?php } ?>
    </select>
    <input class="button" type="button" onClick="order_fun_sub('desc')" value="倒序">
    <input class="button" type="button" onClick="order_fun_sub('asc')" value="正序">
    
    <script>
        var user_paixu_value = 'uid';
        function order_fun_sub( type ) {
            window.location.href = '<?php echo $this_path . "/" . $user_type . "/"; ?>' + user_paixu_value + "/" + type;  
        }
        document.getElementById("user_paixu").onchange=function() {
            user_paixu_value = this.value;      
        }
    </script>   
    
    <div class="lr10" style="display:inline-block;margin-left:20px;">
        共有会员: <font color="#0c0"> <?php echo $member_count_num; ?> </font>人
        <span class="lr10"></span>
        今日注册: <font color="#f60"> <?php echo $member_new_num; ?> </font>人
    </div>
</div>

<div class="lr10" style="line-height:30px;color:#3c8dbc">
    <b>会员列表:</b> <?php echo $select_where; ?> &nbsp;&nbsp;&nbsp; 共找到 <?php echo $total; ?> 个会员
</div>
<div class="table-list lr10">        
  <!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">UID</th>
            <th align="center">用户名</th>
            <th align="center">邮箱</th>
            <th align="center">手机</th>
            <th align="center">余额</th>
            <th align="center">所属地区</th> 
            <th align="center">管理商等级</th>
            <th align="center">登陆时间,地址,IP</th>
            <th align="center">注册时间</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
    <?php if ( is_array( $members ) && ( 0 < count( $members ) ) ) { ?>
    <?php foreach ( $members as $v ) { ?>
        <tr>
            <td align="center"> <?php echo $v["uid"]; ?> </td>
            <td align="center"><a href="<?php echo WEB_PATH . '/uname/' . idjia($v["uid"]); ?>" target="_blank"><?php echo $v["username"]; ?></a></td>
            <td align="center"> <?php echo $v["email"]; ?> 
            <?php if ($v["emailcode"] == 1) { ?>
            <span style="color:#0c0">√</span>
            <?php } else { ?>
            <span style="color:red">×</span>
            <?php } ?>
            </td>   
            <td align="center"> <?php echo $v["mobile"]; ?> 
            <?php if ($v["mobilecode"] == 1) { ?>
            <span style="color:#0c0">√</span>
            <?php } else { ?>
            <span style="color:red">×</span>
            <?php } ?>
            </td>
            <td align="center"> <?php echo $v["money"]; ?> </td>
            <td align="center"> <?php echo $v["area_name"]; ?> </td>
            <td align="center"> <?php echo $manage_rank_arr[ $v['manage_rank'] ]; ?> </td>               
            <td align="center"><?php echo _put_time($v["login_time"], "未登录"); ?>,<?php echo trim($v["user_ip"], ","); ?></td>   
            <td align="center"> <?php echo _put_time($v["time"]); ?> </td>
            <td align="center">
            <?php if ($v["status"] == "-1") { ?>
                [<a href="<?php echo G_MODULE_PATH; ?>/member/huifu/<?php echo $v["uid"]; ?>">恢复</a>]
                [<a href="<?php echo G_MODULE_PATH; ?>/member/del_true/<?php echo $v["uid"]; ?>" onClick="return confirm('是否真的删除！');">删除</a>]
            <?php } else { ?>
                [<a href="<?php echo G_MODULE_PATH; ?>/index/manage/<?php echo $v["uid"]; ?>" target="_blank">代管</a>]
                [<a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v["uid"]; ?>">改</a>]
                [<a href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v["uid"]; ?>" onClick="return confirm('是否真的删除！');">删</a>]
            <?php } ?>
            </td>                
        </tr>
    <?php } } ?>
    </tbody>
</table>
</div><!--table-list end-->

<div id="pages" style="margin:10px 10px">       
    <ul>
        <li>共 <?php echo $total; ?> 条</li>
        <?php echo $page; ?>
    </ul>
</div>
<script>
</script>
</body>
</html> 