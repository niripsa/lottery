<?php
class partner extends SystemAction
{

    /**
     * 品牌合作商主页
     */
    public function index()
    {
        $home_title    = '品牌合作商';
        $home_keywords = '品牌合作商';
        $home_desc     = '品牌合作商';
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $this->view->show("partner.index");
    }
}