
/* 设置 cookies */
function setcookie( name, value, days ) 
{
    name = cookie_pre + name;
    var argc = setcookie.arguments.length;
    var argv = setcookie.arguments;
    var secure = (argc > 5) ? argv[5] : false;
    var expire = new Date();
    if(days==null || days==0) days=1;
    expire.setTime(expire.getTime() + 3600000*24*days);
    document.cookie = name + "=" + escape(value) + ("; path=" + cookie_path) + ((cookie_domain == '') ? "" : ("; domain=" + cookie_domain)) + ((secure == true) ? "; secure" : "") + ";expires="+expire.toGMTString();
}
/* 获取 cookies */
function getCookie( name )
{
    name = cookie_pre + name;
    var start = document.cookie.indexOf(name + "=");
    var len = start + name.length + 1;
    if ((!start) && (name != document.cookie.substring(0, name.length))) {
        return null;
    }
    if (start == -1) return null;
    var end = document.cookie.indexOf(';', len);
    if (end == -1) end = document.cookie.length;
    return unescape(document.cookie.substring(len, end));
}

/**
 * 倒计时函数
 */
function timer( intDiff, k, interval )
{
    window.setInterval( function() {
        var day = 0,
        hour   = 0,
        minute = 0,
        second = 0;//时间默认值

    if ( intDiff > 0 )
    {
        day    = Math.floor(intDiff / (60 * 60 * 24));
        hour   = Math.floor(intDiff / (60 * 60)) - (day * 24);
        minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
        second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
    }

    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    hour = hour + (day * 24);
    $('#hour_' + k).html( hour+interval);
    $('#minute_' + k).html( minute+interval );
    $('#second_' + k).html( second );
    intDiff--;
    }, 1000);
}

/**
 * 基于微信 weui 的alert 弹框
 */
function dialog_alert( title, info, action )
{
    var dialog_alert_html = '';
    dialog_alert_html += '<div class="weui_dialog_alert">';
    dialog_alert_html += '<div class="weui_mask"></div>';
    dialog_alert_html += '<div class="weui_dialog">';
    dialog_alert_html += '<div class="weui_dialog_hd"><strong class="weui_dialog_title">'+ title +'</strong></div>';
    dialog_alert_html += '<div class="weui_dialog_bd">'+ info +'</div>';
    dialog_alert_html += '<div class="weui_dialog_ft">';
    dialog_alert_html += "<a onclick=$('.weui_dialog_alert').remove();"+ action +" class='weui_btn_dialog primary'>确定</a>";
    dialog_alert_html += '</div></div></div>';
    $('#dialog_alert').html( dialog_alert_html );
}