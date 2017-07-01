var default_html = "<option value=''> 请选择 </option>";
/**
 * 省份改变
 */
function province_change( area_id )
{
    var url = '/?/iinsist/area/get_clild/' + area_id;
    $.get( url, '', function( res ) {
        var html = default_html;
        var list = res['data'];
        if ( res['status'] == 1 )
        {
            for ( k in list )
            {
                html += '<option value='+ list[k]['area_id'] +'>'+ list[k]['area_name'] +'</option>';
            }
            $( '#city_se' ).html( html );
            $( '#area_se' ).html( default_html );
        }
    }, 'json' );
}

/**
 * 城市改变
 */
function city_change( area_id )
{
    var url = '/?/iinsist/area/get_clild/' + area_id;
    $.get( url, '', function( res ) {
        var html = '';
        var list = res['data'];
        if ( res['status'] == 1 )
        {
            for ( k in list )
            {
                html += '<option value='+ list[k]['area_id'] +'>'+ list[k]['area_name'] +'</option>';
            }
            $( '#area_se' ).html( html );
        }
    }, 'json' );
}