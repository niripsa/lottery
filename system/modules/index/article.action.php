<?php

class article extends SystemAction
{
    public function lists()
    {
    }

    public function show()
    {
        $aid = (int) $this->segment(4);
        $adb = System::load_app_model("article", "common");
        ($article = $adb->GetArticleAid($aid)) || $this->SendStatus(404);

        if ($article) {
            $cateinfo = $adb->GetOne("SELECT * FROM `@#_cate` where `cateid` = '{$article["cateid"]}' LIMIT 1");
        }
        else {
            $cateinfo = array("info" => NULL);
        }

        $home_title = findconfig("seo", "article_title");
        $home_keywords = findconfig("seo", "article_keywords");
        $home_desc = findconfig("seo", "article_desc");

        if (!$home_title) {
            $home_title .= _cfg("web_name") . $article["title"];
        }

        if (!$home_title) {
            $home_keywords .= (empty($article["keywords"]) ? _cfg("web_name") . $article["keywords"] : $info["meta_title"]);
        }

        if (!$home_title) {
            $home_desc .= (empty($article["description"]) ? _cfg("web_name") . $article["description"] : _strcut($article["content"], 100));
        }

        $seoinfo = array();
        $seoinfo["title"] = $article["title"];
        $seoinfo["keywords"] = $article["keywords"];
        $seoinfo["description"] = $article["description"];
        seo("title", $home_title, "article", $seoinfo);
        seo("keywords", $home_keywords, "article", $seoinfo);
        seo("description", $home_desc, "article", $seoinfo);
        $info = unserialize($cateinfo["info"]);

        if (!isset($info["template_show"])) {
            $info["template_show"] = "article_show.help.html";
        }

        $template = explode(".", $info["template_show"]);
        array_pop($template);
        $data = $adb->get_cate_list("parentid = 1");
        $this->view->show(implode(".", $template))->data("article", $article)->data("data", $data)->data("cateid", $aid);
    }

    public function single()
    {
        $single = safe_replace($this->segment(4));
        $this->model = System::load_app_model("index", "common");

        if (intval($single)) {
            $wherewords = "`cateid` = '$single'";
            $article = $this->model->findcate($wherewords);
        }
        else {
            $wherewords = "`catdir` = '$single'";
            $article = $this->model->findcate($wherewords);
        }

        if (!$article) {
            _message(l("html.key.err"));
        }

        $info = unserialize($article["info"]);
        $article["thumb"] = $info["thumb"];
        $article["des"] = $info["des"];
        $article["content"] = base64_decode($info["content"]);
        $home_title = (empty($info["meta_title"]) ? _cfg("web_name") . $article["name"] : $info["meta_title"]);
        $home_keywords = _cfg("web_name") . $info["meta_keywords"];
        $home_desc = _cfg("web_name") . $info["meta_description"];
        seo("title", $home_title, $info["meta_title"]);
        seo("keywords", $home_keywords, $info["meta_keywords"]);
        seo("description", $home_desc, $info["meta_description"]);
        $template = explode(".", $info["template"]);
        $show = "$template[0].$template[1]";
        $this->view->show($show)->data("info", $article);
    }
}


?>
