$(document).ready( function() {
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
});


/**
 * 省 选择
 */
function province( id )
{
    var child_area = '';
    var url = '?/index/index/get_clild_area/' + id;
    $.get( url, '', function( res ) {
        child_area = res;
        render_area( 1, child_area );
    }, 'json' );
}

/**
 * 市 选择
 */
function city( id )
{
    var child_area = '';
    var url = '?/index/index/get_clild_area/' + id;
    $.get( url, '', function( res ) {
        child_area = res;
        render_area( 2, child_area );
    }, 'json' );
}

/**
 * 县 选择
 */
function county( id )
{
    var child_area = '';
    var url = '?/index/index/get_clild_area/' + id;
    $.get( url, '', function( res ) {
        child_area = res;
        render_area( 3, child_area );
    }, 'json' );
}

/**
 * 渲染地区
 */
function render_area( deep, child_area )
{
    $( '#areaSelect' ).show();
    var html = '';
    var onclick_func = '';
    switch ( deep )
    {
        case 1:
        onclick_func = 'city';
        break;
        case 2:
        onclick_func = 'county';
        break;
        case 3:
        onclick_func = 'set_area_id';
        break;
    }
    
    for ( k in child_area )
    {
        html += '<li onclick='+ onclick_func+ '('+ child_area[k]['area_id'] +')' +' id="' + child_area[k]['area_id'] + '">' + child_area[k]['area_name'] + '</li>';
    }
    $( '#area_title' ).html( '选择您所在的地区' );
    $( '#area_list' ).html( html );
}

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