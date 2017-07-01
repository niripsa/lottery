<?php defined("G_EXECMODE") or die("I'm sorry, you don't have the access"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Uploadify</title>
<link rel="stylesheet" type="text/css" href="<?php echo G_PLUGIN_PATH; ?>/uploadify/uploadify.css" />
</head>
<body>
<div class="W">
    <div class="Bg"></div>
    <div class="Wrap" id="Wrap">
        <div class="Title">
            <h3 class="MainTit" id="MainTit"><?php echo $title; ?></h3>
            <a href="javascript:Close();" title="关闭" class="Close"></a>
        </div>
        <div class="Cont">
            <p class="Note">最多上传<strong><?php echo $num; ?></strong>个附件,单文件最大<strong><?php echo $size_str; ?></strong>,类型<strong><?php echo $uptype; ?></strong></p>
            <div class="flashWrap">
                <input name="uploadify" id="uploadify" type="file" multiple="true" />
                <span>
                    <?php if($admincheck){ ?>
                    <input type="checkbox" name="iswatermark" id="iswatermark" /><label>是否添加水印</label>
                    <?php } ?>
                </span>
            </div>
            <div class="fileWarp">
                <fieldset>
                    <legend>列表</legend>
                    <ul>
                    </ul>
                    <div id="fileQueue">
                    </div>
                </fieldset>
            </div>
            <div class="btnBox">
                <button class="btn" id="SaveBtn">保存</button>
                &nbsp;
                <button class="btn" id="CancelBtn">取消</button>
            </div>
        </div>
        <!--[if IE 6]>
        <iframe frameborder="0" style="width:100%;height:100px;background-color:transparent;position:absolute;top:0;left:0;z-index:-1;"></iframe>
        <![endif]-->
    </div><!--Wrap end-->
</div><!--W end-->

<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/jquery.min.js" type="text/javascript"></script> 
<!--防止客户端缓存文件，造成uploadify.js不更新，而引起的“喔唷，崩溃啦”-->           
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/jquery.uploadify.js?ver=<?php echo rand(0,9999);?>" type="text/javascript"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/uploadify-move.js" type="text/javascript"></script>


<script type="text/javascript">
function Close(){
        $("#<?php echo $frame ;?>", window.parent.document).remove();
}
$("#CancelBtn").click(function(){
        $("#<?php echo $frame ;?>", window.parent.document).remove();
        //$('#uploadify').uploadifyClearQueue();
        //$(".fileWarp ul li").remove();
});
</script>

<script type="text/javascript">
/*实例化上传控件 */
$(function() {
setTimeout( function (){
    $('#uploadify').uploadify({
            'formData'        : {},
            'auto'            : true,
            'method'          : 'post',
            'multi'           : true,
            'swf'             : '<?php echo G_PLUGIN_PATH; ?>/uploadify/uploadify.swf',
            'uploader'        : '<?php echo G_WEB_PATH; ?>/?plugin=true&api=Upload&action=upload&save=<?php echo $dir;?>',
            'queueSizeLimit'  : '<?php echo $num; ?>',
            'fileSizeLimit'   : '<?php echo $size; ?>',
            'fileTypeExts'    : '<?php echo $uptype; ?>',
            'fileTypeDesc'    : '<?php echo $desc; ?>',
            'buttonImage'     : '<?php echo G_PLUGIN_PATH; ?>/uploadify/select.png',
            'queueID'         : 'fileQueue',
            'onUploadStart'   : function(file){
                $('#uploadify').uploadify('settings', 'formData',{'iswatermark':$("#iswatermark").is(':checked')});             
            },
            'onUploadSuccess' : function(file, data, response){
                $(".fileWarp ul").append(SetImgContent(data));
                SetUploadFile();
            }
        }); 
        
    },50);
});



//显示上传的图片
function SetImgContent(data){
    var obj=eval('('+data+')');  
    if(obj.status == -1){
        //window.parent.message(obj.text,8,2);              
        alert(obj.msg)
        return;
    }else{
        var sLi = "";
        sLi += '<li class="img">';
        sLi += '<img src="' + obj.url_quan + '" width="100" height="100">';
        sLi += '<input type="hidden" name="fileurl_tmp[]" urls="'+obj.url_quan+'" value="' + obj.url_ban + '">';
        sLi += '<a href="javascript:void(0);">删除</a>';
        sLi += '</li>';
        return sLi;
    }
}

//删除上传元素DOM并清除目录文件
function SetUploadFile(){
    $("ul li").each(function(l_i){
        $(this).attr("id", "li_" + l_i);
    })
    $("ul li a").each(function(a_i){
        $(this).attr("rel", "li_" + a_i);
    }).click(function(){
        var urls = '<?php echo G_WEB_PATH; ?>/?plugin=true&api=Upload&action=del';
        $.post(urls,{filename:$(this).prev().val()},function(data){
            //alert(data);
        });
        $("#" + this.rel).remove();
    })
}
    
    /*点击保存按钮时
     *判断允许上传数，检测是单一文件上传还是组文件上传
     *如果是单一文件，上传结束后将地址存入$input元素
     *如果是组文件上传，则创建input样式，添加到$input后面
     *隐藏父框架，清空列队，移除已上传文件样式*/
$("#SaveBtn").click(function(){ 
 var callback = "<?php echo $func; ?>";
 var num = <?php echo $num; ?>;
 var fileurl_tmp_ban = [];
 var fileurl_tmp_quan = [];

  
    if(callback){   
        if(num > 1){    
             $("input[name^='fileurl_tmp']").each(function(index,dom){
                fileurl_tmp_ban[index] = dom.value;
                fileurl_tmp_quan[index] = $(dom).attr("urls");      
             });
            
             
        }else{
            fileurl_tmp_ban[0] = $("input[name^='fileurl_tmp']").val(); 
            fileurl_tmp_quan[0] = $("input[name^='fileurl_tmp']").attr("urls"); 
        }   
        
        eval('window.parent.'+callback+'(fileurl_tmp_ban,fileurl_tmp_quan)');
        $(window.parent.document).find("#<?php echo $frame ;?>").remove();
        return;
    }   
                
    /* 未设置回调   */   
    var windom = $(window.parent.document).find("#<?php echo $input; ?>");  
    if(num > 1){
            var fileurl_tmp = "";
            $("input[name^='fileurl_tmp']").each(function(index,dom){
                
                if($(windom).attr("urls")=="1"){
                    fileurl_tmp += '<li rel="'+ dom.value +'"><input class="input-text" type="text" name="<?php echo $input;?>[]"  value="'+ $(dom).attr("urls") +'" /><a href="javascript:void(0);" onclick="ClearPicArr(\''+ dom.value +'\',\'<?php echo WEB_PATH; ?>\')">删除</a></li>';     
                }else{
                    fileurl_tmp += '<li rel="'+ dom.value +'"><input class="input-text" type="text" name="<?php echo $input;?>[]"  value="'+ dom.value +'" /><a href="javascript:void(0);" onclick="ClearPicArr(\''+ dom.value +'\',\'<?php echo WEB_PATH; ?>\')">删除</a></li>';   
                }
            });         
            $(windom).append(fileurl_tmp);
    }else{
        
            if($(windom).attr("urls")=="1"){
                $(window.parent.document).find("#<?php echo $input; ?>").val($("input[name^='fileurl_tmp']").attr("urls"));             
            }else{
                $(window.parent.document).find("#<?php echo $input; ?>").val($("input[name^='fileurl_tmp']").val());
                /* 缩略图展示  Yusure  */
                $(window.parent.document).find("#<?php echo $input; ?>").parent().find('img').attr('src', $("input[name^='fileurl_tmp']").attr("urls") );
            }           
    }

    $(window.parent.document).find("#<?php echo $frame ;?>").remove();
});


</script>
</body>
</html>