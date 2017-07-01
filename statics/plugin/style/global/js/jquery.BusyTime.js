/**
 *  526541010@qq.com
 *  战线jquery倒计时扩展
 */

;
(function($){

    Date.prototype.getFormattedDate = function(pattern){
                function getFullStr(i){
                    return i>9?""+i:"0"+i;
                }
                pattern = pattern
                    .replace(/yyyy/,this.getFullYear())
                    .replace(/mm/,getFullStr(this.getMonth()+1))
                    .replace(/dd/,getFullStr(this.getDate()))
                    .replace(/hh/,getFullStr(this.getHours()))
                    .replace(/mm/,getFullStr(this.getMinutes()))
                    .replace(/ss/,getFullStr(this.getSeconds()))
                    .replace(/ms/,getFullStr(this.getMilliseconds()));

                return pattern;
    };


    var busytime = function(ele,opt){

        this.$element = ele;
        this.defaults = {
                        "millisec"  : 33,
                        "pattern"   : "dd hh:mm:ss ms",
                        "callback"  : function(){
                            //null
                        }
        };
        this.ti = {};
        this.options = $.extend({}, this.defaults, opt); //合并到一个空对象上
    }


    busytime.prototype = {
        csss : function() {
            return this.$element.css({
                'color': this.options.color,
                'fontSize': this.options.fontSize,
                'textDecoration': this.options.textDecoration
            });
        },

        begin : function(){

            var obj = this.$element[0];
            obj.innerHTML = (new Date()).getFormattedDate("hh : mm : ss");
            setInterval(function(){
                    obj.innerHTML = (new Date()).getFormattedDate("hh : mm : ss");
            }, 1000);
        },

        start : function(){
            var self = this
            this.$element.each(function(k,o){


                self.ti[k] = {};
                self.ti[k].pattern  = $(o).attr("pattern") ? $(o).attr("pattern") : self.options.pattern;
                self.ti[k].callback = $(o).attr("callback");
                self.ti[k].dom      = $(o);
                self.ti[k].time     = parseInt($(o).attr("time"));


                setTimeout(self.ti[k].fun= function(){
                    var subTime,dd,hh,mm,ss,ms

                    subTime = self.ti[k].time - (new Date().getTime());
                    subTime > 0 ? (function(){

                        dd =  parseInt((subTime/1000)/86400)
                        dd =  dd < 10 ? "0"+dd : dd;
                        hh =  parseInt((subTime/1000)%86400/3600)
                        hh =  hh < 10 ? "0"+hh : hh;
                        mm =  parseInt((subTime/1000)%3600/60);
                        mm =  mm < 10 ? "0"+mm : mm;
                        ss =  parseInt((subTime/1000)%60);
                        ss =  ss < 10 ? "0"+ss : ss;
                        ms =  (subTime%1000)
                        ms =  ms < 10 ? "0"+ms : String(ms).substr(0,2);

                        self.ti[k].dom.html(self.ti[k].pattern
                        .replace(/dd/,dd)
                        .replace(/hh/,hh)
                        .replace(/mm/,mm)
                        .replace(/ss/,ss)
                        .replace(/ms/,ms))

                        setTimeout(self.ti[k].fun, 1000/self.options.millisec);

                    })() : self.ti[k].callback ? (function(){

                        self.ti[k].dom.html(self.ti[k].pattern
                        .replace(/dd/,"00")
                        .replace(/hh/,"00")
                        .replace(/mm/,"00")
                        .replace(/ss/,"00")
                        .replace(/ms/,"00"));

                        setTimeout(eval(self.ti[k].callback+"(self.ti[k].dom)"),0)

                    }()) : void function(){

                        self.ti[k].dom.html(self.ti[k].pattern
                        .replace(/dd/,"00")
                        .replace(/hh/,"00")
                        .replace(/mm/,"00")
                        .replace(/ss/,"00")
                        .replace(/ms/,"00"));

                        setTimeout(self.options.callback(self.ti[k].dom),0)
                    }();
                },0)

            });

        }
    }


    $.fn.busytime = function(options) {
        return new busytime(this, options);
    }

})($)
;






