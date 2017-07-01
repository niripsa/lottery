(function(){

    //立即购买
    var  jwebox = {};
         jwebox.DOMS = {};
    var _x,_y,m,allscreen=false;         
    jwebox.heredoc = function(fn) {
         //return fn.toString().split('\n').slice(1,-1).join('\n') + '\n'
          //return fn.toString().match(/\/\*!?(?:\@preserve)?[ \t]*(?:\r\n|\n)([\s\S]*?)(?:\r\n|\n)\s*\*\//)[1];
    }

    jwebox.strToDom = function (arg) {
    　　 var objE = document.createElement("div");
    　　 objE.innerHTML = arg;
    　　 return objE.childNodes;
    };
    var jwebox_shopid="";
    jwebox.goshopnow = function(shopid,path){
        if(jwebox_shopid && jwebox_shopid==shopid && jwebox.DOMS['goshopnow']){      
            jwebox.DOMS['goshopnow'].show();return;     
        }else{
            var html = jwebox.heredoc(function(){ });                
            var dom = jwebox.strToDom(html);
            $(document.body).append($(dom));
            jwebox.DOMS['goshopnow'] = $(".jwebox_showbox");        

            var left=parseInt(($(window).width() - 600) / 2);
            var top=parseInt(($(window).height() - 280) / 2.5);         
            $('.jwebox_webox').css({left:left,top:top});
            $(".jwebox_goshopc").attr("href",path+"/cgoods/"+shopid);                   
            $('.jwebox_locked').mousedown(function(e){
                if(e.which){
                    m=true;
                    _x=e.pageX-parseInt($('.jwebox_webox').css('left'));
                    _y=e.pageY-parseInt($('.jwebox_webox').css('top'));
                }
            }).dblclick(function(){
                if(allscreen){
                    $('.jwebox_webox').css({height:height,width:width});
                    $('.jwebox_webox').css({left:left,top:top});
                    allscreen = false;
                }else{
                    allscreen=true;
                    var screenHeight = $(window).height();
                    var screenWidth = $(window).width();
                    $('.jwebox_webox').css({'width':screenWidth-18,'height':screenHeight-18,'top':'0px','left':'0px'});
                }
            }).mousemove(function(e){
                if(m && !allscreen){
                    var x=e.pageX-_x;
                    var y=e.pageY-_y;
                    $('.jwebox_webox').css({left:x});
                    $('.jwebox_webox').css({top:y});
                }
            }).mouseup(function(){
                m=false;
                }); 
            $(window).resize(function(){
                if(allscreen){
                    var screenHeight = $(window).height();
                    var screenWidth = $(window).width();
                    $('.jwebox_webox').css({'width':screenWidth-18,'height':screenHeight-18,'top':'0px','left':'0px'});
                }
            }); 
            
            //商品信息获取  
            var syrs='';
            var shopinfo='';
            var shopnum = $(".ynum_dig");
            var jwebox_sum="";
            var umoney="";
            $.get(path+"/member/cart/Fastpay/",{'shopid':shopid},function(cgoodsdata){
                var cgoodsinfo = jQuery.parseJSON(cgoodsdata);
                if(cgoodsinfo.ustatus==2){
                    $('.jwebox_mainlist').hide();
                    $('.jwebox_mainlists').html(cgoodsinfo.umoney);
                    $('.jwebox_locked .span').click(function(){
                        window.location.reload();
                        /*$('.jwebox_showbox').css({display:'none'});
                         if(jwebox_shopid&&jwebox_shopid==shopid){
                         $(".ynum_dig").val($(".ynum_dig").val());
                         }else{
                         $(".ynum_dig").val('1');
                         }      */
                    });
                }else{
                    $('.jwebox_showbox').css({display:'block'});
                    syrs=cgoodsinfo.zongrenshu-cgoodsinfo.canyurenshu;
                    shopinfo={'shopid':shopid,'price':cgoodsinfo.price,'shenyu':syrs,'zongrenshu':cgoodsinfo.zongrenshu,'canyurenshu':cgoodsinfo.canyurenshu};
                    $('.jwebox_goshopc').text(cgoodsinfo.cg_title);
                    jwebox_sum=cgoodsinfo.price*parseInt(shopnum.val());
                    $(".jwebox_sum").text(jwebox_sum);
                    $(".jwebox_uspecies").text(cgoodsinfo.umoney);
                    umoney=cgoodsinfo.umoney;
                    if(jwebox_sum>cgoodsinfo.umoney){
                        $(".jwebox_Det_Shopnow").hide();
                        $(".jwebox_Det_Shopnow_add").show();
                    }else{
                        $(".jwebox_Det_Shopnow").show();
                        $(".jwebox_Det_Shopnow_add").hide();
                    }
                    if(cgoodsinfo.ustatus==0){
                        $(".jwebox_mainlist_uspecies").html(cgoodsinfo.umoney+"<span>请先</span><a href='"+path+"/login/'> 登录>> </a>");
                        $(".jwebox_mainlist_uspecies").css("color","red");
                        $(".jwebox_mainlist_uspecies span").css("color","#000");
                        $(".jwebox_mainlist_uspecies a").css("color","red");
                        $(".jwebox_mainlist_uspecies a").css("letterSpacing","3px");
                        $(".jwebox_mainlist_uspecies a").css("fontWeight","bold");
                        $(".jwebox_mainlist_uspecies a").css("fontSize","14px");
                        $(".jwebox_shop_buttom").hide();
                        $('.jwebox_locked .span').click(function(){
                            window.location.reload();
                        });
                    }else if(cgoodsinfo.ustatus==1){
                        $('.jwebox_locked .span').click(function(){
                            $('.jwebox_showbox').hide();
                        });
                        $("#jwebox_Det_Shopnow").click(function(){
                            $(".jwebox_shop_buttom").hide();
                            var info = {};
                            var shopid=shopinfo['shopid'];
                            var shopnum =parseInt($(".ynum_dig").val());
                            if(shopnum>3000){
                                $(".jwebox_shop_relog").text('一次购买量大于3000会产生多条购买记录！');
                                setTimeout(function(){
                                $(".jwebox_shop_relog").text();
                                },3000);                            
                            }
                            $.post(path+"/member/cart/Fastpaysubmit/",{'shopid':shopid,'num':shopnum,'shenyu':shopinfo['shenyu'],'money':shopinfo['price'],'MoenyCount':'0.00'},function(cgoodsdata){
                                var cgoodsinfo = jQuery.parseJSON(cgoodsdata);
                                var i=3;
                                cgoodsinfo.error=cgoodsinfo.error;
                                $('.jwebox_locked .span').click(function(){
                                    window.location.reload();
                                });
                                $(".jwebox_shop_relog").text(cgoodsinfo.error);
                                var timer = setInterval(function(){
                                    if(i==1){
                                        clearInterval(timer);
                                        window.location.reload();
                                    }
                                    document.getElementById("sd_time").innerHTML = cgoodsinfo.error+(i--)+"秒后窗口将自行关闭";
                                },1000);
                            });
                        });
                    }
                }

            });
            function baifenshua(aa,n){
                n = n || 2;
                return ( Math.round( aa * Math.pow( 10, n + 2 ) ) / Math.pow( 10, n ) ).toFixed( n ) + '%';
            }           
            shopnum.keyup(function(){
                if(isNaN(shopnum.val())){                   
                 shopnum.val(0);    
                }else{
                    if(shopnum.val()<0){                    
                     shopnum.val(0);    
                    }           
                    if(shopnum.val()>syrs){
                        shopnum.val(syrs);
                        jwebox_sum=shopinfo['price']*syrs;
                        $(".jwebox_sum").text(jwebox_sum);
                        if(jwebox_sum>umoney){
                            $(".jwebox_Det_Shopnow").hide();
                            $(".jwebox_Det_Shopnow_add").show();
                        }               
                    }else{
                        jwebox_sum=shopinfo['price']*shopnum.val();
                        $(".jwebox_sum").text(jwebox_sum);  
                    }               
                    var numshop=shopnum.val();
                    if(numshop==shopinfo['zongrenshu']){
                        var baifenbi='100%';
                    }else{
                        var showbaifen=numshop/shopinfo['zongrenshu'];
                        var baifenbi=baifenshua(showbaifen,2);
                    }                       
                    
                }               
            
            
            }); 
            
            $(".yshopadd").click(function(){        
                var shopnum = $(".ynum_dig");
                    var resshopnump='';             
                    var num = parseInt(shopnum.val());      
                    if(num+1 >syrs){
                    num=syrs;                   
                        shopnum.val(syrs);
                        resshopnump = syrs;
                    }else{
                        num=parseInt(shopnum.val())+1;
                        resshopnump=parseInt(shopnum.val())+1;
                        shopnum.val(resshopnump);   
                        if(shopnum.val()>=syrs){
                            num=shopinfo['shenyu'];
                            resshopnump=shopinfo['shenyu'];
                            shopnum.val(resshopnump);
                        }
                    }   
                jwebox_sum=shopinfo['price']*num;
                $(".jwebox_sum").text(jwebox_sum);
                if(jwebox_sum>umoney){
                    $(".jwebox_Det_Shopnow").hide();
                    $(".jwebox_Det_Shopnow_add").show();
                }
                if(resshopnump==shopinfo['zongrenshu']){
                    var baifenbi='100%';
                }else{
                    var showbaifen=resshopnump/shopinfo['zongrenshu'];
                    var baifenbi=baifenshua(showbaifen,2);
                }
                $("#yshopchance").html("<span style='color:red'>获得机率"+baifenbi+"</span>");                                      
            }); 
            $(".yshopsub").click(function(){
                var shopnum = $(".ynum_dig");
                var num = parseInt(shopnum.val());
                if(num<2){
                    num=1;
                    shopnum.val(1);         
                }else{
                    num=parseInt(shopnum.val())-1
                    shopnum.val(parseInt(shopnum.val())-1);
                }
                jwebox_sum=shopinfo['price']*num;
                $(".jwebox_sum").text(jwebox_sum);  
                if(jwebox_sum>umoney){
                    $("#jwebox_Det_Shopnow").hide();
                    $(".jwebox_Det_Shopnow_add").show();
                }else{
                    $("#jwebox_Det_Shopnow").show();
                    $(".jwebox_Det_Shopnow_add").hide();
                }
                var shopnums=parseInt(shopnum.val());
                if(shopnums==shopinfo['zongrenshu']){
                        var baifenbi='100%';
                    }else{
                        var showbaifen=shopnums/shopinfo['zongrenshu'];
                        var baifenbi=baifenshua(showbaifen,2);
                    }
                    $("#yshopchance").html("<span style='color:red'>获得机率"+baifenbi+"</span>");                  
            });
            $(".jwebox_Det_Shopnow_add").click(function(){
                window.open(path+"/member/account/userrecharge/","_blank");
            });

            jwebox_shopid=shopid;           
        }   
    }
    
    window.jwebox = jwebox;
    
})();
