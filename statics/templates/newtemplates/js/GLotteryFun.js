                function gg_show_Time_fun(times,objc,uhtml,data){               
                    time = times - (new Date().getTime());
                    i =  parseInt((time/1000)/60);
                    s =  parseInt((time/1000)%60);
                    ms =  String(Math.floor(time%1000));
                    ms = parseInt(ms.substr(0,2));
                    if(i<10)i='0'+i;
                    if(s<10)s='0'+s;
                    if(ms<0)ms='00';
                    objc.html('<span class="minute ">'+i+'</span>：<span class="minute ">'+s+'</span>：<span class="minute ">'+ms+'</span>');               
                    if(time<=0){                        
                    var obj = objc.parent();                    
                            obj.find(".shi").html('<span class="minute">请稍后…</span>');   
                             setTimeout(function(){
                                obj.html(uhtml);                        
                                obj.attr('class',"wenzi");
                                $.ajaxSetup({
                                    async : false
                                });                             
                                $.post(data['path']+"/index/getshop/lottery_shop_set/",{'lottery_sub':'true','gid':data['id']},null);
                            },5000);                             
                            return;                     
                    }
                    
                     setTimeout(function(){                                         
                            gg_show_Time_fun(times,objc,uhtml,data);                 
                     },30); 
                
                }
                function gg_show_time_add_li(div,path,info){            
                    var html= '';   
                    html+= '<li class="b_gray">';       
                    html+= '<div class="print">';       
                    html+= '<p>用户：<a  href="'+path+'/cgdataserver/'+info.id+'" target="_blank" class="c_red">马上揭晓</a></p>';      
                    html+= '<p>花费：<a  href="'+path+'/cgdataserver/'+info.id+'" target="_blank" class="c_red">马上揭晓</a></p>';  
                    html+= '<a href="'+path+'/cgdataserver/'+info.id+'" target="_blank"><p class="c_black">'+info.title+'</p></a>'; 
                    html+= '<p class="mt30">离开奖还有';    
                    html+='</p><span class="shi"><span class="minute">99</span>:<span class="second">99</span>:<span class="millisecond">99</span></span>';
                    html+='</span>';                        
                    html+= '</div>';    
                    html+= '<div class="w_goods_pic">'; 
                    html+= '<a href="'+path+'/cgdataserver/'+info.id+'" target="_blank"><img src="'+info.upload+'/'+info.thumb+'"></a>';                            
                    html+= '</div>';            
                    html+= '</li>';         
                    
                    
                    var uhtml = '';     
                    uhtml+= '<div class="print">';                      
                    uhtml+= '<p>用户：<a href="'+path+'/uname/'+(1000000000 + parseInt(info.uid))+'"  target="_blank" class="c_red">'+info.user+'</a></p>';     
                    uhtml+= '<p>花费 <span class="c_red">'+info.huafei+'</span> '+info.currency+'，获得了</p>'; 
                    uhtml+= '<a href="'+path+'/cgdataserver/'+info.id+'" target="_blank"><p class="c_black">'+info.title+'</p></a>' ;   
                    uhtml+= '<p class="mt30">回报率：<span class="c_red t18">'+info.huibaolv+'</span> 倍</p>';      
                    uhtml+= '</div>';   
                    uhtml+= '<div class="w_goods_pic">';    
                    uhtml+= '<a href="'+path+'/cgdataserver/'+info.id+'" target="_blank"><img src="'+info.upload+'/'+info.thumb+'"></a>';
                    uhtml+= '</div>';                                   

                    var divl = $("#"+div).find('li');
                    var len = divl.length;          
                    if(len==3 && len  >0){
                        var this_len = len - 1;
                        divl.eq(this_len).remove();
                    }           
                    $("#"+div).prepend(html);                   
                    var div_li_obj = $(".print .shi").eq(0);
                    var data = new Array();
                        data['id'] = info.id;
                        data['path'] = path;                            
                    info.times = (new Date().getTime())+(parseInt(info.times))*1000;                    
                    gg_show_Time_fun(info.times,div_li_obj,uhtml,data,info.id);             
                }
                
                function gg_show_time_init(div,path,gid){   
                    window.setTimeout("gg_show_time_init()",5000);  
                    if(!window.GG_SHOP_TIME){   
                        window.GG_SHOP_TIME = {
                            gid : '',
                            path : path,
                            div : div,
                            arr : new Array()
                        };
                    }
                    $.get(GG_SHOP_TIME.path+"/index/getshop/lottery_shop_json/"+new Date().getTime(),{'gid':GG_SHOP_TIME.gid},function(indexData){  
                        var info = jQuery.parseJSON(indexData);                                 
                            if(info.error == '0' && info.id != 'null'){                         
                                if(!GG_SHOP_TIME.arr[info.id]){
                                            GG_SHOP_TIME.gid =  GG_SHOP_TIME.gid +'_'+info.id;      
                                            GG_SHOP_TIME.arr[info.id] = true;                                           
                                            gg_show_time_add_li(GG_SHOP_TIME.div,GG_SHOP_TIME.path,info);                           
                                }           
                            }           
                    });                         
                }
                
 //首页倒计时end               
                
                function gg_show_Time_funlist(times,objc,uhtml,data){               
                    time = times - (new Date().getTime());
                    i =  parseInt((time/1000)/60);
                    s =  parseInt((time/1000)%60);
                    ms =  String(Math.floor(time%1000));
                    ms = parseInt(ms.substr(0,2));
                    if(i<10)i='0'+i;
                    if(s<10)s='0'+s;
                    if(ms<0)ms='00';
                    objc.html('<span class="minute ">'+i+'</span>：<span class="minute ">'+s+'</span>：<span class="minute ">'+ms+'</span>');               
                    if(time<=0){                        
                    var obj = objc.parent();                    
                            obj.find(".shi").html('<span class="minute">请稍后…</span>');   
                             setTimeout(function(){
                                obj.html(uhtml);                        
                                obj.attr('class',"wenzi");
                                $.ajaxSetup({
                                    async : false
                                });                             
                                $.post(data['path']+"/index/getshop/lottery_shop_set/",{'lottery_sub':'true','gid':data['id']},null);
                            },5000);                             
                            return;                     
                    }
                    
                     setTimeout(function(){                                         
                            gg_show_Time_funlist(times,objc,uhtml,data);                 
                     },30); 
                
                }
                function gg_show_time_add_list(div,path,info){          
                    var html= '';                   
                    html+= '<li class="Cursor b_pink">';
                    html+= '<div class="cprint">';
                    html+= '<a class="fl goodsimg" href="'+path+'/cgdataserver/'+info.id+'" target="_blank">';
                    html+= '<img  src="'+info.upload+'/'+info.thumb+'">';
                    html+= '</a>';
                    html+= '<div class="publishC-tit">';
                    html+= '<h3><a href="'+path+'/cgdataserver/'+info.id+'" target="_blank" class="gray01">(第'+info.qishu+'期)'+info.title+'</a></h3>';
                    html+= '<p class="money">商品价值：<span class="rmb">'+info.g_money+'</span>'+info.currency+'</p>';
                    html+= '</div>';
                    html+= '<p class="mt30">离开奖还有';
                    html+='</p><span class="shi"><span class="minute">99</span>:<span class="second">99</span>:<span class="millisecond">99</span></span>';
                    html+='</span>';
                    html+='</div>';                 
                    html+= '</li>';
                    
                    var uhtml = '';
                    uhtml+= '<div class="cprint">';     
                    uhtml+= '<a class="fl goodsimg" href="'+path+'/cgdataserver/'+info.id+'" target="_blank">'; 
                    uhtml+= '<img  src="'+info.upload+'/'+info.thumb+'"></a>';
                    uhtml+= '<div class="publishC-Member gray02">';
                    uhtml+= '<a class="fl headimg" href="'+path+'/uname/'+(1000000000 + parseInt(info.uid))+'" target="_blank">';
                    if(info.uid){
                    uhtml+= '<img id="imgUserPhoto" src="'+info.upload+'/photo/member.jpg.8080.jpg" width="50" height="50" border="0">';
                    }else{
                    uhtml+= ' <img id="imgUserPhoto" src="'+info.upload+info.u_thumb+'" width="50" height="50" border="0">';                    
                    }
                    uhtml+= '</a>';
                    uhtml+= '<p>获得者：<a class="blue Fb" href="'+path+'/uname/'+(1000000000 + parseInt(info.uid))+'" target="_blank">'+info.user+'</a></p>';
                    uhtml+= '<p>夺宝：<em class="c_red Fb">'+info.shopsum+'</em>人次</p>';
                    uhtml+= '</div>';
                    uhtml+= '<div class="publishC-tit">';
                    uhtml+= '<h3><a href="'+path+'/cgdataserver/'+info.id+'" target="_blank" class="gray01">(第'+info.qishu+'期)'+info.title+'</a></h3>';
                    uhtml+= '<p class="money">商品价值：<span class="rmb">'+info.g_money+'</span>'+info.currency+'</p>';
                    uhtml+= '<p class="Announced-time gray02">揭晓时间：'+info.q_external_time+'</p>';
                    uhtml+= '</div>';
                    uhtml+= '<div class="details bg_pink">';
                    uhtml+= '<p class="fl details-Code">幸运夺宝码：<em class="c_red Fb">'+info.q_user_code+'</em></p>';
                    uhtml+= '<a class="fl details-A c_red" href="'+path+'/cgdataserver/'+info.id+'" rel="nofollow" target="_blank">查看详情</a>';
                    uhtml+= '</div>';
                    uhtml+= '</div>';

                    var divl = $("#"+div).find('li');
                    var len = divl.length;          
                    if(len==10 && len  >0){
                        var this_len = len - 1;
                        divl.eq(this_len).remove();
                    }                           
                    $("#"+div).prepend(html);                   
                    var div_li_obj = $(".cprint .shi").eq(0);
                    var data = new Array();
                        data['id'] = info.id;
                        data['path'] = path;                            
                    info.times = (new Date().getTime())+(parseInt(info.times))*1000;                    
                    gg_show_Time_funlist(info.times,div_li_obj,uhtml,data,info.id);             
                }               
                function gg_show_time_list(div,path,gid){   
                    window.setTimeout("gg_show_time_list()",5000);  
                    if(!window.GG_SHOP_TIME){   
                        window.GG_SHOP_TIME = {
                            gid : '',
                            path : path,
                            div : div,
                            arr : new Array()
                        };
                    }
                    $.get(GG_SHOP_TIME.path+"/index/getshop/lottery_shop_jsonlottery/"+new Date().getTime(),{'gid':GG_SHOP_TIME.gid},function(indexData){   
                        var info = jQuery.parseJSON(indexData);                                 
                            if(info.error == '0' && info.id != 'null'){                         
                                if(!GG_SHOP_TIME.arr[info.id]){
                                            GG_SHOP_TIME.gid =  GG_SHOP_TIME.gid +'_'+info.id;      
                                            GG_SHOP_TIME.arr[info.id] = true;                                           
                                            gg_show_time_add_list(GG_SHOP_TIME.div,GG_SHOP_TIME.path,info);                         
                                }           
                            }           
                    });                         
                }                
                
//最新揭晓倒计时end                     