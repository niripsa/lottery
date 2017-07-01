
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

!function ($) {
    $.extend({
        _jsonp : {
            scripts : {},
            counter : 1,
            charset : "utf8",
            head : document.getElementsByTagName("head")[0],
            name : function (callback) {
                var name = "_jsonp_" + (new Date).getTime() + "_" + this.counter;
                this.counter++;
                var cb = function (json) {
                    eval("delete " + name),
                    callback(json),
                    $._jsonp.head.removeChild($._jsonp.scripts[name]),
                    delete $._jsonp.scripts[name]
                };
                return eval(name + " = cb"),
                name
            },
            load : function (a, b) {
                var c = document.createElement("script");
                c.type = "text/javascript",
                c.charset = this.charset,
                c.src = a,
                this.head.appendChild(c),
                this.scripts[b] = c
            }
        },
        getJSONP : function (a, b) {
            var c = $._jsonp.name(b),
            a = a.replace(/{callback};/, c);
            return $._jsonp.load(a, c),
            this
        }
    })
}
(jQuery);

var iplocation = {};    // 省份
var provinceCityJson = {};  // 城市
/* 小计： ajax从从后端拉取 一级 二级 地区json格式 */
var url = '?/index/index/index_area';
$.ajaxSetup({ async : false });
$.get( url, '', function( res ) {
    iplocation = res['province'];
    provinceCityJson = res['city'];
}, 'json' );

var cName = "ipLocation";
var currentLocation   = "北京";
var currentProvinceId = 1;

var isUseServiceLoc = true; //是否默认使用服务端地址
var province_html = '';
for ( k in iplocation )
{
    province_html += "<li><a href='#none' data-value='" + k + "'>" + iplocation[ k ] + "</a></li>";
}
var provinceHtml = '<div class="content"><div data-widget="tabs" class="m JD-stock" id="JD-stock">'
                    +'<div class="mt">'
                    +'    <ul class="tab">'
                    +'        <li data-index="0" data-widget="tab-item" class="curr"><a href="#none" class="hover"><em>请选择</em><i></i></a></li>'
                    +'        <li data-index="1" data-widget="tab-item" style="display:none;"><a href="#none" class=""><em>请选择</em><i></i></a></li>'
                    +'        <li data-index="2" data-widget="tab-item" style="display:none;"><a href="#none" class=""><em>请选择</em><i></i></a></li>'
                    +'        <li data-index="3" data-widget="tab-item" style="display:none;"><a href="#none" class=""><em>请选择</em><i></i></a></li>'
                    +'    </ul>'
                    +'    <div class="stock-line"></div>'
                    +'</div>'
                    +'<div class="mc" data-area="0" data-widget="tab-content" id="stock_province_item">'
                    +'    <ul class="area-list">'
                    + province_html
                    +'    </ul>'
                    +'</div>'
                    +'<div class="mc" data-area="1" data-widget="tab-content" id="stock_city_item"></div>'
                    +'<div class="mc" data-area="2" data-widget="tab-content" id="stock_area_item"></div>'
                    +'<div class="mc" data-area="3" data-widget="tab-content" id="stock_town_item"></div>'
                    +'</div></div>';

// 根据省份ID获取名称
function getNameById( provinceId ) {
    for ( var o in iplocation ) {
        if ( o == provinceId ) {
            return iplocation[o];
        }
    }
    return "北京";
}
/* 点 省 时候 把城市传入 显示第二栏 */
function getAreaList(result){
    var html = ["<ul class='area-list'>"];
    var longhtml = [];
    var longerhtml = [];
    if (result&&result.length > 0){
        for (var i=0,j=result.length;i<j ;i++ ){
            result[i].name = result[i].name.replace(" ","");
            if(result[i].name.length > 12){
                longerhtml.push("<li class='longer-area'><a href='#none' data-value='"+result[i].id+"'>"+result[i].name+"</a></li>");
            }
            else if(result[i].name.length > 5){
                longhtml.push("<li class='long-area'><a href='#none' data-value='"+result[i].id+"'>"+result[i].name+"</a></li>");
            }
            else{
                html.push("<li><a href='#none' data-value='"+result[i].id+"'>"+result[i].name+"</a></li>");
            }
        }
    }
    else{
        html.push("<li><a href='#none' data-value='"+currentAreaInfo.currentFid+"'> </a></li>");
    }
    html.push(longhtml.join(""));
    html.push(longerhtml.join(""));
    html.push("</ul>");
    return html.join("");
}

/**
 * 追加 县 html
 */
function getCountyList( result )
{
    var county_html = '';
    county_html += "<ul class='area-list'>";
    for ( i in result )
    {
        county_html += "<li><a onclick=set_area_id("+result[i]['area_id']+ ",'" + result[i]['area_name'] +"')>"+result[i]['area_name']+"</a></li>"
    }    
    county_html += "</ul>";
    $( '#stock_area_item' ).html( county_html );
}

function cleanKuohao(str){
    if(str&&str.indexOf("(")>0){
        str = str.substring(0,str.indexOf("("));
    }
    if(str&&str.indexOf("（")>0){
        str = str.substring(0,str.indexOf("（"));
    }
    return str;
}

function getStockOpt(id,name){
    if(currentAreaInfo.currentLevel==3){
        currentAreaInfo.currentAreaId = id;
        currentAreaInfo.currentAreaName = name;
        if(!page_load){
            currentAreaInfo.currentTownId = 0;
            currentAreaInfo.currentTownName = "";
        }
    }
    else if(currentAreaInfo.currentLevel==4){
        currentAreaInfo.currentTownId = id;
        currentAreaInfo.currentTownName = name;
    }

    $('#store-selector').removeClass('hover');
    if(page_load){
        page_load = false;
    }
    //替换gSC
    var address = currentAreaInfo.currentProvinceName+currentAreaInfo.currentCityName+currentAreaInfo.currentAreaName+currentAreaInfo.currentTownName;
    $("#store-selector .text div").html(currentAreaInfo.currentProvinceName+cleanKuohao(currentAreaInfo.currentCityName)+cleanKuohao(currentAreaInfo.currentAreaName)+cleanKuohao(currentAreaInfo.currentTownName)).attr("title",address);
}
function getAreaListcallback(r){
    currentDom.html(getAreaList(r));
    if (currentAreaInfo.currentLevel >= 2){
        currentDom.find("a").click(function(){
            if(page_load){
                page_load = false;
            }
            if(currentDom.attr("id")=="stock_area_item"){
                currentAreaInfo.currentLevel=3;
            }
            else if(currentDom.attr("id")=="stock_town_item"){
                currentAreaInfo.currentLevel=4;
            }
            getStockOpt($(this).attr("data-value"),$(this).html());
        });
        if(page_load){ //初始化加载
            currentAreaInfo.currentLevel = currentAreaInfo.currentLevel==2?3:4;
            if(currentAreaInfo.currentAreaId&&new Number(currentAreaInfo.currentAreaId)>0){
                getStockOpt(currentAreaInfo.currentAreaId,currentDom.find("a[data-value='"+currentAreaInfo.currentAreaId+"']").html());
            }
            else{
                getStockOpt(currentDom.find("a").eq(0).attr("data-value"),currentDom.find("a").eq(0).html());
            }
        }
    }
}
function chooseProvince(provinceId){
    provinceContainer.hide();
    currentAreaInfo.currentLevel = 1;
    currentAreaInfo.currentProvinceId = provinceId;
    currentAreaInfo.currentProvinceName = getNameById(provinceId);
    if(!page_load){
        currentAreaInfo.currentCityId = 0;
        currentAreaInfo.currentCityName = "";
        currentAreaInfo.currentAreaId = 0;
        currentAreaInfo.currentAreaName = "";
        currentAreaInfo.currentTownId = 0;
        currentAreaInfo.currentTownName = "";
    }
    areaTabContainer.eq(0).removeClass("curr").find("em").html(currentAreaInfo.currentProvinceName);
    areaTabContainer.eq(1).addClass("curr").show().find("em").html("请选择");
    areaTabContainer.eq(2).hide();
    areaTabContainer.eq(3).hide();
    cityContainer.show();
    areaContainer.hide();
    townaContainer.hide();
    if(provinceCityJson[""+provinceId]){
        cityContainer.html(getAreaList(provinceCityJson[""+provinceId]));
        cityContainer.find("a").click(function(){
            if(page_load){
                page_load = false;
            }
            $("#store-selector").unbind("mouseout");
            chooseCity($(this).attr("data-value"),$(this).html());
        });
        if(page_load){ //初始化加载
            if(currentAreaInfo.currentCityId&&new Number(currentAreaInfo.currentCityId)>0){
                chooseCity(currentAreaInfo.currentCityId,cityContainer.find("a[data-value='"+currentAreaInfo.currentCityId+"']").html());
            }
            else{
                chooseCity(cityContainer.find("a").eq(0).attr("data-value"),cityContainer.find("a").eq(0).html());
            }
        }
    }
}
function chooseCity(cityId,cityName){
    provinceContainer.hide();
    cityContainer.hide();
    currentAreaInfo.currentLevel = 2;
    currentAreaInfo.currentCityId = cityId;
    currentAreaInfo.currentCityName = cityName;
    if(!page_load){
        currentAreaInfo.currentAreaId = 0;
        currentAreaInfo.currentAreaName = "";
        currentAreaInfo.currentTownId = 0;
        currentAreaInfo.currentTownName = "";
    }
    areaTabContainer.eq(1).removeClass("curr").find("em").html(cityName);
    areaTabContainer.eq(2).addClass("curr").show().find("em").html("请选择");
    areaTabContainer.eq(3).hide();
    areaContainer.show().html("<div class='iloading'>正在加载中，请稍候...</div>");
    townaContainer.hide();
    currentDom = areaContainer;
    var child_area = '';
    var child_url = '?/index/index/get_clild_area/' + cityId;
    $.get( child_url, '', function( res ) {
        child_area = res;
        getCountyList( child_area );
    }, 'json' );
}
function chooseArea(areaId,areaName){
    provinceContainer.hide();
    cityContainer.hide();
    areaContainer.hide();
    currentAreaInfo.currentLevel = 3;
    currentAreaInfo.currentAreaId = areaId;
    currentAreaInfo.currentAreaName = areaName;
    if(!page_load){
        currentAreaInfo.currentTownId = 0;
        currentAreaInfo.currentTownName = "";
    }
    areaTabContainer.eq(2).removeClass("curr").find("em").html(areaName);
    areaTabContainer.eq(3).addClass("curr").show().find("em").html("请选择");
    townaContainer.show().html("<div class='iloading'>正在加载中，请稍候...</div>");
    currentDom = townaContainer;
}

$("#store-selector .text").after(provinceHtml);
var areaTabContainer  = $("#JD-stock .tab li");
var provinceContainer = $("#stock_province_item");
var cityContainer     = $("#stock_city_item");
var areaContainer     = $("#stock_area_item");
var townaContainer    = $("#stock_town_item");
var currentDom        = provinceContainer;
//当前地域信息
var currentAreaInfo;
//初始化当前地域信息
function CurrentAreaInfoInit(){
    currentAreaInfo =  {
        "currentLevel": 1,
        "currentProvinceId": 1,
        "currentProvinceName":"北京",
        "currentCityId": 0,
        "currentCityName":"",
        "currentAreaId": 0,
        "currentAreaName":"",
        "currentTownId":0,
        "currentTownName":""
    };
    var ipLoc = getCookie("ipLoc-djd");
    ipLoc = ipLoc ? ipLoc.split("-") : [18,287,3024,0];
    if(ipLoc.length>0&&ipLoc[0]){
        currentAreaInfo.currentProvinceId = ipLoc[0];
        currentAreaInfo.currentProvinceName = getNameById(ipLoc[0]);
    }
    if(ipLoc.length>1&&ipLoc[1]){
        currentAreaInfo.currentCityId = ipLoc[1];
    }
    if(ipLoc.length>2&&ipLoc[2]){
        currentAreaInfo.currentAreaId = ipLoc[2];
    }
    if(ipLoc.length>3&&ipLoc[3]){
        currentAreaInfo.currentTownId = ipLoc[3];
    }
    var current_area_id = getCookie( 'area_id' );
    if ( current_area_id == null )
    {
        /* 默认城市 湖南省 娄底市 双峰县 */
        set_area_id( 3024 );
    }
    else
    {
        /* 调用地区名称 */
        var url = '?/index/index/get_area_name/' + current_area_id;
        $.ajaxSetup({ async : false });
        $.get( url, '', function( res ) {
            $( '#show_area_name' ).html( res );
        } );
    }
}
var page_load = true;
(function(){
    $("#store-selector").unbind("mouseover").bind("mouseover",function(){
        $('#store-selector').addClass('hover');
        $("#store-selector .content,#JD-stock").show();
    }).find("dl").remove();
    CurrentAreaInfoInit();
    areaTabContainer.eq(0).find("a").click(function(){
        areaTabContainer.removeClass("curr");
        areaTabContainer.eq(0).addClass("curr").show();
        provinceContainer.show();
        cityContainer.hide();
        areaContainer.hide();
        townaContainer.hide();
        areaTabContainer.eq(1).hide();
        areaTabContainer.eq(2).hide();
        areaTabContainer.eq(3).hide();
    });
    areaTabContainer.eq(1).find("a").click(function(){
        areaTabContainer.removeClass("curr");
        areaTabContainer.eq(1).addClass("curr").show();
        provinceContainer.hide();
        cityContainer.show();
        areaContainer.hide();
        townaContainer.hide();
        areaTabContainer.eq(2).hide();
        areaTabContainer.eq(3).hide();
    });
    areaTabContainer.eq(2).find("a").click(function(){
        areaTabContainer.removeClass("curr");
        areaTabContainer.eq(2).addClass("curr").show();
        provinceContainer.hide();
        cityContainer.hide();
        areaContainer.show();
        townaContainer.hide();
        areaTabContainer.eq(3).hide();
    });
    provinceContainer.find("a").click(function() {
        if(page_load){
            page_load = false;
        }
        $("#store-selector").unbind("mouseout");
        chooseProvince($(this).attr("data-value"));
    }).end();
    chooseProvince(currentAreaInfo.currentProvinceId);
})();

/**
 * 设置地区ID
 * @author Yusure  http://yusure.cn
 * @date   2016-09-24
 * @param  [param]
 */
function set_area_id( area_id )
{
    setcookie( 'area_id', area_id );
    location.reload();
}

