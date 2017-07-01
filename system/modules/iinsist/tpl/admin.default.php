
<?php

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>后台首页</title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/index.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n    <script type=\"text/javascript\" src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/jquery.min.js\"></script>\r\n    <script type=\"text/javascript\" src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/highcharts.js\"></script>\r\n    <script type=\"text/javascript\" src=\"";
echo G_GLOBAL_STYLE;
echo "/global/js/modules/exporting.js\"></script>\r\n    <script type=\"text/javascript\">\r\n\r\n        var chart;\r\n        $(document).ready(function() {\r\n            chart = new Highcharts.Chart({\r\n                chart: {\r\n                    renderTo: 'container'\r\n                },\r\n                credits:{\r\n                    enabled:false // 禁用版权信息\r\n                },\r\n                title: {\r\n                    text: '本月销售与订单数'\r\n                },\r\n                xAxis: {\r\n                    categories: [";
echo $sales_list["x"];
echo "]\r\n                },\r\n                yAxis: {\r\n                    title:''\r\n                },\r\n                tooltip: {\r\n                    formatter: function() {\r\n                        var s;\r\n                        if (this.point.name) { // the pie chart\r\n                            s = ''+\r\n                                this.point.name +': '+ this.y +' fruits';\r\n                        } else {\r\n                            s = ''+\r\n                                this.x  +': '+ this.y;\r\n                        }\r\n                        return s;\r\n                    }\r\n                },\r\n                series: [{\r\n                    type: 'column',\r\n                    name: '销量',\r\n                    data: [";
echo $sales_list["sales"];
echo "]\r\n                }, {\r\n                    type: 'spline',\r\n                    name: '订单',\r\n                    data: [";
echo $sales_list["order"];
echo "]\r\n                }]\r\n            });\r\n\r\n\r\n        });\r\n\r\n    </script>\r\n</head>\r\n<body>\r\n<div class=\"mt20 ml10\" style=\"padding-bottom: 100px;\">\r\n<div class=\"acc_box wid400 hig410 lf\">\r\n    <div class=\"acc_title\">本月销售前十的商品</div>\r\n    <div class=\"acc_goods_box ml15 mr15\">\r\n        <div class=\"acc_goods_row\">\r\n            <div class=\"lf wid220\">产品名称</div><div class=\"lf wid75\">夺宝次数</div><div class=\"lf wid75\">开奖次数</div><div class=\"cl\"></div>\r\n        </div>\r\n        ";
if (is_array($info) && (0 < count($info))) {
    foreach ($info as $row ) {
        echo "        <div class=\"acc_goods_row\">\r\n            <div class=\"lf wid220\">";
        echo $row["title"];
        echo "</div><div class=\"lf wid75\">";
        echo $row["a_sum"];
        echo "</div><div class=\"lf wid75\">";
        echo $row["b_num"];
        echo "</div><div class=\"cl\"></div>\r\n        </div>\r\n        ";
    }
}

echo "    </div>\r\n</div>\r\n<div class=\"acc_box wid400 hig410 ml10 lf\">\r\n    <div class=\"web_acc_title ml20 mr20\">网站信息统计</div>\r\n    <div class=\"acc_goods_box ml20 mr20 mt10\">\r\n        ";
if (is_array($web_acc) && (0 < count($web_acc))) {
    foreach ($web_acc as $row ) {
        echo "        <div class=\"acc_web_row\">\r\n            <div class=\"lf wid100\">";
        echo $row["name"];
        echo "：</div><div class=\"lf wid150\">";
        echo $row["val"];
        echo "</div><div class=\"cl\"></div>\r\n        </div>\r\n        ";
    }
}

echo "    </div>\r\n\r\n</div>\r\n<div id=\"container\" class=\"acc_box wid600 hig410 ml10 lf\"></div>\r\n<div class=\"cl\"></div>\r\n</div>\r\n</body>\r\n</html> \r\n";

?>
