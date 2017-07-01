<?php

class sys_model extends model
{
    public $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
    }

    public function get_url($data)
    {
        $arr = array();
        $url = array();
        $cate_model = System::load_app_model("cate", "common");
        $goods_model = System::load_app_model("goods", "common");
        $article_model = System::load_app_model("article", "common");
        $share_model = System::load_app_model("share", "common");
        $member_model = System::load_app_model("member", "common");
        $url["url"] = G_WEB_PATH;
        $url["url_val"] = 1;
        $arr[$url["url"]] = $url;

        if (0 < strpos("#," . $data["map_link"] . ",", ",1,")) {
            $url["url"] = WEB_PATH . "/goods_list/";
            $url["url_val"] = 0.8;
            $arr[$url["url"]] = $url;
            $cate = $cate_model->get_cate_list("model=1", "cateid");

            foreach ($cate as $row ) {
                $url["url"] = WEB_PATH . "/goods_list/" . $row["cateid"] . "_0_0/";
                $url["url_val"] = 0.8;
                $arr[$url["url"]] = $url;
                $brand = $goods_model->get_brand("concat('#,',cateid,',') like '%," . $row["cateid"] . ",%'");

                foreach ($brand as $r ) {
                    $url["url"] = WEB_PATH . "/goods_list/" . $row["cateid"] . "_" . $r["id"] . "_0/";
                    $url["url_val"] = 0.8;
                    $arr[$url["url"]] = $url;
                }
            }

            $goods = $goods_model->get_goods("g_type=1", "gid");

            foreach ($goods as $row ) {
                $url["url"] = WEB_PATH . "/goods/" . $row["gid"] . "/";
                $url["url_val"] = 0.6;
                $arr[$url["url"]] = $url;
            }

            $url["url"] = WEB_PATH . "/cgoods_list/";
            $url["url_val"] = 0.8;
            $arr[$url["url"]] = $url;
            $cate = $cate_model->get_cate_list("model=1", "cateid");

            foreach ($cate as $row ) {
                $url["url"] = WEB_PATH . "/cgoods_list/" . $row["cateid"] . "_0_0/";
                $url["url_val"] = 0.8;
                $arr[$url["url"]] = $url;
                $brand = $goods_model->get_brand("concat('#,',cateid,',') like '%," . $row["cateid"] . ",%'");

                foreach ($brand as $r ) {
                    $url["url"] = WEB_PATH . "/cgoods_list/" . $row["cateid"] . "_" . $r["id"] . "_0/";
                    $url["url_val"] = 0.8;
                    $arr[$url["url"]] = $url;
                }
            }

            $goods = $goods_model->get_goods("g_type=3", "gid");

            foreach ($goods as $row ) {
                $url["url"] = WEB_PATH . "/cgoods/" . $row["gid"] . "/";
                $url["url_val"] = 0.6;
                $arr[$url["url"]] = $url;
            }
        }

        if (0 < strpos("#," . $data["map_link"] . ",", ",2,")) {
            $user = $member_model->get_user_list("", "uid");

            foreach ($user as $row ) {
                $url["url"] = WEB_PATH . "/uname/" . idjia($row["uid"]) . "/";
                $url["url_val"] = 0.6;
                $arr[$url["url"]] = $url;
            }
        }

        if (0 < strpos("#," . $data["map_link"] . ",", ",3,")) {
            $article = $article_model->get_articles("", "id");

            foreach ($article as $row ) {
                $url["url"] = WEB_PATH . "/article-" . $row["id"] . ".html";
                $url["url_val"] = 0.8;
                $arr[$url["url"]] = $url;
            }
        }

        if (0 < strpos("#," . $data["map_link"] . ",", ",4,")) {
            $share = $share_model->sharelist();

            foreach ($share as $row ) {
                $url["url"] = WEB_PATH . "/index/share/detail/" . $row["sd_id"] . "/";
                $url["url_val"] = 0.8;
                $arr[$url["url"]] = $url;
            }
        }

        return $this->create_map($arr, $data["map_path"]);
    }

    private function create_map($arr, $path = "/")
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n                <urlset>\r\n                </urlset>";
        $xml = simplexml_load_string($str);
        if (is_array($arr) && (0 < count($arr))) {
            foreach ($arr as $row ) {
                $item = $xml->addChild("url");
                $node = $item->addChild("loc", $row["url"]);
                $node = $item->addChild("priority", $row["url_val"]);
                $node = $item->addChild("lastmod", date("Y-m-d") . "T" . date("H:i:s") . "+00:00");
                $node = $item->addChild("changefreq", "Always");
            }
        }

        $xml_str = $xml->asXML();

        if (file_exists(G_APP_PATH . $path . "sitemap.xml")) {
            if (!is_writable(G_APP_PATH . $path . "sitemap.xml")) {
                _message("Please chmod  sitemap.xml  to 0777 !");
            }
        }

        return $ok = file_put_contents(G_APP_PATH . $path . "sitemap.xml", $xml_str);
    }

    public function account($str)
    {
        return $ok = file_put_contents(G_CONFIG . "acc.inc.php", $str);
    }

    public function web_verify($str)
    {
        return $ok = file_put_contents(G_CONFIG . "verify.inc.php", $str);
    }
}

System::load_sys_class("model", "sys", "no");

?>
