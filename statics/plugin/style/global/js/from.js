function yg_select(json,input_id,select_id){
    var html='';
    var i=0;
    var str="";
    for(var o in json){
        if($.trim(select_id)==''){
            if(i==0){               
                str="<div class='yb_selected' rel='"+input_id+"'>"+json[o]+"</div>";
                $("#"+input_id).val(o);
            }
        }else{
            
            if(o==select_id){           
                str="<div class='yb_selected' rel='"+input_id+"'>"+json[o]+"</div>";
            }
        }
        i++;
        html+="<div rel='"+o+"'>"+json[o]+"</div>";
    }
    
    html="<div class='yb_select' rel='1'>"+html+"</div>";
    document.write(str+html);
}
function yg_radio(json,input_id,select_id){
    var str='';
    var i=0;
    for(var o in json){
        if(select_id==''){
            if(i==0){
                str+="<div class='yb_radio checked lf' rel='"+o+"'><i></i>"+json[o]+"</div>";
            }else{
                str+="<div class='yb_radio lf' rel='"+o+"'><i></i>"+json[o]+"</div>";
            }
        }else{
            if(o==select_id){
                str+="<div class='yb_radio checked lf' rel='"+o+"'><i></i>"+json[o]+"</div>";
            }else{
                str+="<div class='yb_radio lf' rel='"+o+"'><i></i>"+json[o]+"</div>";
            }
        }
        i++;
    }
    document.write("<div class='yb_radio_box' rel='"+input_id+"'>"+str+"</div>");
}
function yg_checkbox(json,input_id,select_id){
    var str='';
    var i=0;
    for(var o in json){
        if(select_id !='' && ('#,'+select_id+',').indexOf(','+o+',')>0 ){
            str+="<div class='yb_check checked lf' rel='"+o+"'><i></i>"+json[o]+"</div>";
        }else{
            str+="<div class='yb_check lf' rel='"+o+"'><i></i>"+json[o]+"</div>";
        }

        i++;
    }
    document.write("<div class='yb_check_box' rel='"+input_id+"'>"+str+"</div>");
}
function yg_close(json,data_type,input_id,select_id){
    var no_val='';
    var no_text='';
    var yes_val='';
    var yes_text='';
    var str='';
    if(data_type=="json"){
        var i=0;
        for(var o in json){
            if(i==0){
                no_val=o;
                no_text=json[o];
            }else{
                yes_val=o;
                yes_text=json[o];
            }
            i++;
        }
    }
    if(data_type=='txt'){
        var tmp=json.split("|");
        for(var i=0;i<tmp.length;i++){
            t=tmp[i].split(",");
            if(i==0){
                no_val=t[0];
                yes_val=t[1];
            }else{
                no_text=t[0];
                yes_text=t[1];
            }
        }
    }
    str="<div class='sel_off yb_close cir_l' rel='"+no_val+"'>"+no_text+"</div>";
    if(select_id=='' || select_id==no_val){
        str="<div class='sel_off yb_close cir_l active' rel='"+no_val+"'>"+no_text+"</div>";
    }
    if(select_id==yes_val){
        str+="<div class='sel_on yb_close cir_r active' rel='"+yes_val+"'>"+yes_text+"</div>"
    }else{
        str+="<div class='sel_on yb_close cir_r' rel='"+yes_val+"'>"+yes_text+"</div>"
    }
    document.write("<div class='yb_close_box lf' rel='"+input_id+"'>"+str+"<div class='cl'></div></div>");
}
$(document).ready(function(){
    $(document).delegate(".yb_selected","click",function(){
        var obj=$(this).parent().find(".yb_select");
        var oo=$(this);
        var input_id=oo.attr("rel");
        var of=$(this).offset();
        obj.css("top",of.top);
        var x=of.left;
        var y=of.top;
        var w=obj.width();
        var h=obj.height();
        $(this).parent().find(".yb_select").show();
        $(this).parent().find(".yb_select div").mouseout(function(e){
            var xx= e.pageX;
            var yy= e.pageY;
            if(xx<x || xx >x+w || yy<y || yy>y+h){
                obj.hide();
            }
        });
        $(this).parent().find(".yb_select div").click(function(e){
             oo.html($(this).html());
            $("#"+input_id).val($(this).attr("rel"));
            obj.hide();
        });
    });
    $(document).delegate(".yb_radio","click",function(){
        var oo=$(this);
        var p=$(this).parent();
        var input_id= p.attr("rel");
        p.find(".yb_radio").removeClass("checked");
        oo.addClass("checked");
        $("#"+input_id).val(oo.attr("rel"));
        if($.trim($("#"+input_id).attr("callback"))!=""){
            var callback=$("#"+input_id).attr("callback");
            eval(callback+"()");
        }
    });
    $(document).delegate(".yb_check","click",function(){
        var oo=$(this);
        var p=$(this).parent();
        var obj=$(this).parent().parent();
        var input_id= p.attr("rel");
        if(oo.attr('class').indexOf("checked")>=0){
            oo.removeClass("checked");
        }else{
            oo.addClass("checked");
        }
        var val='';
        p.find(".yb_check").each(function(){
            if($(this).attr('class').indexOf("checked")>=0){
                if(val==''){
                    val=$(this).attr("rel");
                }else{
                    val+=","+$(this).attr("rel");
                }
            }
        });
        $("#"+input_id).val(val);
    });
    $(document).delegate(".yb_close","click",function(){
        var oo=$(this);
        var p=$(this).parent();
        var input_id= p.attr("rel");
        if($(this).attr('class').indexOf("sel_off")>=0){
            p.find(".sel_off").removeClass("active");
            p.find(".sel_on").addClass("active");
        }else{
            p.find(".sel_on").removeClass("active");
            p.find(".sel_off").addClass("active");
        }
        $("#"+input_id).val(p.find(".active").attr("rel"));
        if($.trim($("#"+input_id).attr("callback"))!=""){
            var callback=$("#"+input_id).attr("callback");
            eval(callback+"('"+input_id+"')");
        }
    });
});