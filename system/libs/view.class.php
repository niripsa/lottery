<?php
class view
{
    private $suffix;
    private $template;
    private $templatedir;
    private $g_templates;
    static  private $debug = false;
    static  private $file  = array( 'html' => false, 'tpl' => false );
    static  private $datas = array();
    static  private $skin  = false;

    public function __construct()
    {
        $domain = System::load_sys_config( 'domain' );
        $ini    = System::load_sys_config( 'view' );
        $this->suffix = $ini['config']['suffix'];

        if ( ! empty( $domain[$_SERVER['HTTP_HOST']]['templates'] ) ) 
        {
            $this->templates = $domain[$_SERVER['HTTP_HOST']]['templates'];
            $this->templatesdir = $ini['templates'][$this->templates]['html'];
        }
        else 
        {
            $this->templates    = G_IS_MOBILE ? $ini['skin']['mobile'] : $ini['skin']['pc'];
            $this->templatesdir = $ini['templates'][$this->templates]['html'];
        }

        $this->g_templates = G_TEMPLATES;

        if ( _getcookie( "skin" ) ) 
        {
            self::$skin = G_CACHES . "caches_codes" . DIRECTORY_SEPARATOR . "SkinChange.php";
            $skin = _encrypt( _getcookie("skin"), "DECODE" );

            if ( isset( $ini["templates"][$skin] ) ) 
            {
                $this->templates    = $skin;
                $this->templatesdir = $ini["templates"][$this->templates]["html"];
            }

            define( "G_IS_TEMPSKIN", 1 );
        }
        else 
        {
            define( "G_IS_TEMPSKIN", 0 );
        }

        define( "G_HTML_SUFFIX", $this->suffix );
        define( "G_STYLE",       $this->templates );
        define( "G_STYLE_HTML",  $this->templatesdir );
        define( "G_TEMPLATES_PATH",  G_WEB_PATH . "/" . G_STATICS_DIR . "/templates" );
        define( "G_TEMPLATES_STYLE", G_TEMPLATES_PATH . "/" . $this->templates );
        define( "G_TEMPLATES_CSS",   G_TEMPLATES_PATH . "/" . G_STYLE . "/css" );
        define( "G_TEMPLATES_JS",    G_TEMPLATES_PATH . "/" . G_STYLE . "/js" );
        define( "G_TEMPLATES_IMAGE", G_TEMPLATES_PATH . "/" . G_STYLE . "/images" );
    }

    public function data($name, $data)
    {
        self::$datas[$name] = $data;
        return $this;
    }

    public function show( $filename = NULL, $skin = NULL )
    {
        $filename = empty( $filename ) ? ROUTE_C . "." . ROUTE_A : $filename;
        $skin     = empty($skin) ? $this->templates : $skin;
        $filehtml = $this->g_templates . $skin . DIRECTORY_SEPARATOR . $this->templatesdir;
        $filehtml .= DIRECTORY_SEPARATOR . $filename . $this->suffix;
        $filetpl  = G_CACHES . "caches_template" . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR . $filename . ".tpl.php";
        self::$file["html"] = $filehtml;
        self::$file["tpl"]  = $filetpl;
        return $this;
    }

    public function tpl($filename = NULL, $module = NULL)
    {
        $module = ($module ? $module : ROUTE_M);
        self::$file["tpl"] = G_MODULES . $module . DIRECTORY_SEPARATOR . "tpl" . DIRECTORY_SEPARATOR . $filename . ".php";
        return $this;
    }

    public function debug($bool)
    {
        self::$debug = $bool;
        return $this;
    }

    static public function commit($t = NULL)
    {
        $filehtml = self::$file["html"];
        $filetpl = self::$file["tpl"];
        if (!self::$file["html"] && self::$file["tpl"]) {
            return self::view_send_to_html();
        }

        if (!self::$file["html"] && !self::$file["tpl"]) {
            return NULL;
        }

        System::load_sys_class("template")->init($filetpl, $filehtml);
        return $t == "includes" ? true : self::view_send_to_html();
    }

    static public function includes($filename = NULL)
    {
        self::$file["html"] = G_TEMPLATES . G_STYLE . DIRECTORY_SEPARATOR . G_STYLE_HTML;
        self::$file["html"] .= DIRECTORY_SEPARATOR . $filename . G_HTML_SUFFIX;
        self::$file["tpl"] = G_CACHES . "caches_template" . DIRECTORY_SEPARATOR . G_STYLE . DIRECTORY_SEPARATOR . $filename . ".tpl.php";
        self::commit("includes");
        return self::$file["tpl"];
    }

    static public function view_send_to_html()
    {
        if (G_IS_AJAX) {
            self::$debug ? true : ob_clean();
            echo json_encode(self::$datas);
            return NULL;
        }

        extract(self::$datas, EXTR_PREFIX_INVALID, "wc");
        self::$datas = NULL;
        self::$debug ? true : ob_clean();
        self::$skin ? include self::$skin : false;
        include self::$file["tpl"];
        return NULL;
    }

    public function __destruct()
    {

    }
}