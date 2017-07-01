
<?php

function get_iframe_url($row)
{
    if ($row["url"]) {
        echo WEB_PATH . "/" . $row["url"];
        return NULL;
    }
    else {
        if ($row["m"]) {
            $url = WEB_PATH . "/" . $row["m"] . "/" . $row["c"] . "/" . $row["a"];
        }
        else {
            $url = G_MODULE_PATH . "/" . $row["c"] . "/" . $row["a"];
        }

        if ($row["d"]) {
            $url = $url . "/" . $row["d"];
        }

        echo $url;
        return NULL;
    }
}

defined("G_IN_ADMIN") || exit("No permission resources.");
echo "<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title></title>\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/global.css\" type=\"text/css\">\r\n<link rel=\"stylesheet\" href=\"";
echo G_GLOBAL_STYLE;
echo "/global/css/style.css\" type=\"text/css\">\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=7\">\r\n<title>后台地图</title>\r\n<style>\r\nbody{color:#444; height:100%}a{text-decoration:none; color:#444; margin:20px;}\r\n.pad-6{padding:6px; overflow:hidden}.pad-10{padding:10px; overflow:hidden}.pad_10,.pad-lr-10{padding:0 10px}.pad-lr-6{padding:0 6px}\r\n.div1{color:#fff;background:#4C8CCF;width:67%;height:35px;line-height:35px;font-size:0.5cm;}\r\n.div2{height:35px;line-height:35px;float:right;padding-right:10px;}\r\n\r\n.map-menu ul{ margin:5px;width:200px;border:1px solid #A4C0F7;}\r\n.map-menu ul li.title{background:#EDF2F8;font-weight:bold;padding-left:5px;}\r\n.map-menu ul li.title2{font-weight:bold;padding-left:10px;}\r\n.map-menu ul li{padding:4px;padding-left:20px;}\r\n\r\nbody,h1,h2,h3,h4,h5,h6,hr,p,blockquote,dl,dt,dd,ul,ol,li,pre,form,fieldset,legend,button,input,textarea,th,td{margin:0;padding:0;word-wrap:break-word}\r\nbody,html,input{font:12px/1.5 tahoma,arial,\5b8b\4f53,sans-serif;}\r\nul,ol,li{list-style:none;}\r\na{text-decoration:none;}\r\na:hover{text-decoration:underline;}\r\n/*通用样式*/\r\n.lf{float: left}.rt{float: right}.pr{ position:relative}.pa{ position:absolute}\r\n\t</style>\r\n</head>\r\n<body>\r\n";
if (is_array($menu) && (0 < count($menu))) {
    foreach ($menu as $k => $row ) {
        echo "    <div class=\"map-menu lf\">\r\n        <ul>\r\n        <li class=\"title\">";
        echo $row["name"];
        echo "</li>\r\n    ";

        if (is_array($row["sub"])) {
            echo "    ";

            foreach ($row["sub"] as $rr ) {
                echo "            <li class=\"title2\">";
                echo $rr["name"];
                echo "</li>\r\n            ";

                if (is_array($rr["sub"])) {
                    echo "                ";

                    foreach ($rr["sub"] as $r ) {
                        echo "                    <li><a href=\"";
                        get_iframe_url($r);
                        echo "\">";
                        echo $r["name"];
                        echo "</a></li>\r\n                ";
                    }

                    echo "            ";
                }

                echo "    ";
            }

            echo "    ";
        }

        echo "        </ul>\r\n    </div>\r\n";
    }
}

echo "\r\n\r\n</body>\r\n</html>";

?>
