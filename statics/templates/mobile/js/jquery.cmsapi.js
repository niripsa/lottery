/**
 *  526541010@qq.com
 *  cms提供的api访问接口
 *  function ClassA() {}function ClassB() {ClassA.call(this);}ClassB.prototype = new ClassA();
 *
 */

/**

        HTMLElement.prototype.__defineGetter__("description", function () {
            return this.desc;
        });

        HTMLElement.prototype.__defineSetter__("description", function (val) {
            this.desc = val;
        });
        document.body.description = "Beautiful body";

 */

;(function(){

    var yuncms = function(){

            this.author = "<战线>526541010@qq.com";
            this.url    = APP.WEB_PATH;

        }

        //首页
        yuncms.prototype.Loop = function(def){

            var opt = {
                "timer"     : 8000,
                "callback"  : function(){}
            }
            var self = this;
            var gids = '';
            var loopdata;

            opt = $.extend({}, opt, def);

            $.get(self.url+"/index/getshop/lottery_shop_json/",{gid:gids},function(loopdata){
                    if(loopdata.error == '0' && loopdata.id != 'null'){
                        gids = gids ? gids +'_'+loopdata.id : loopdata.id
                        return opt.callback(loopdata);
                    }
            },'json');

            setInterval(function(){
                $.get(self.url+"/index/getshop/lottery_shop_json/",{gid:gids},function(loopdata){
                    if(loopdata.error == '0' && loopdata.id != 'null'){
                        gids = gids ? gids +'_'+loopdata.id : loopdata.id
                        return opt.callback(loopdata);
                    }
                },'json');
            },opt.timer)

        }

        yuncms.prototype.Navs = {
            "#a" : 0,
            "#b" : 1,
            "#c" : 2,
            "#d" : 3,
            "#e" : 4
        };

        yuncms.prototype.ToAjax = function(dom,e){
            var stopDefault = function ( e ) {       if ( e && e.preventDefault ){   e.preventDefault();                 }else{                 window.event.returnValue = false;                   return false;                }       }
            $.get(dom.href,function($data){
                $data=jQuery.parseJSON($data)
                if(!$data.defurl||$data.defurl==null){
                    $.PageDialog.ok($data.string);
                }else{
                    $.PageDialog.ok($data.string,function(){
                        location.href=$data.defurl;
                    });
                }
            });
            stopDefault();
        }


        yuncms.prototype.SetTopStyle = function(def){

            var opt = {
                "Title" : "",
                "Home"  : false,
                "Member": false,
                "Shop"  : false,
                "Balance"  : false,
                "Method"  : false
            }
            opt = $.extend({}, opt, def);
            $("#top_title").text(opt.Title)
            opt.Home ? $("#top_index").show() : null;
            opt.Member ? $("#top_userindex").show() : null;
            opt.Shop ? $("#top_btnCart").show() : null;
            opt.Balance ? $("#top_userbalance").show() : null;
            opt.Method ? $("#top_btnCalMethod").show() : null;
        }


        $.extend({YunCmsApi:new yuncms()});
}());




