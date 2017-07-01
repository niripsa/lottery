var addTimer = function(){
var list = [],
    interval;
    
return function(id,timeStamp){
    console.log( timeStamp );
    if(!interval){
        interval = setInterval(go,1);
    }
    list.push({ele:document.getElementById(id),time:timeStamp});
}

function go() {  
    for (var i = 0; i < list.length; i++) {  
        list[i].ele.innerHTML = changeTimeStamp( list[i].time );  
        if (!list[i].time)  
            list.splice(i--, 1);  
    }  
}

//传入unix时间戳，得到倒计时
function changeTimeStamp( timeStamp )
{
    var distancetime = new Date(timeStamp*1000).getTime() - new Date().getTime();
    if(distancetime > 0){ 
　　　　//如果大于0.说明尚未到达截止时间              
        var ms   = Math.floor( distancetime%1000 );
        var sec  = Math.floor( distancetime/1000%60 );
        var min  = Math.floor( distancetime/1000/60%60 );
        var hour = Math.floor( distancetime/1000/60/60 );
        
        ms = ms + '';
        ms.substring( 0, 2 );
        ms = ms + 1;
        if ( ms < 10 )
        {
            ms = '<b>' + ms + '</b>';
        }
        else
        {
            ms = ms + '';
            ms.split( '' );
            ms = '<b>'+ ms[0] +'</b>' + '<b>' + ms[1] + '</b>';
        }

        if ( sec < 10 )
        {
            sec = "<b>0</b>" + '<b>' + sec + '</b>';
        }
        else
        {
            sec = sec + '';
            sec.split( '' );
            sec = '<b>' + sec[0] + '</b>' + '<b>' + sec[1] + '</b>';
        }

        if ( min < 10 )
        {
            min = "<b>0</b>" + '<b>' + min + '</b>';
        }
        else
        {
            min = min + '';
            min.split( '' );
            min = '<b>' + min[0] + '</b>' + '<b>' + min[1] + '</b>';
        }

        if ( hour < 10 )
        {
            hour = "<b>0</b>" + '<b>' + hour + '</b>';
        }
        else
        {
            hour = hour + '';
            hour = hour.split( '' );
            hour = '<b>' + hour[0] + '</b>' + '<b>' + hour[1] + '</b>';
        }
        
        return hour + ":" +min + ":" +sec + ":" +ms;
    } else {
　　　　// 若否，就是已经到截止时间了
        return '已截止！';
    }    
}                
}();