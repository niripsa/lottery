<?php 

class CloudTocode {

    private $go_list;
    public $go_code;
    public $go_content;
    public $cyrs;
    public $shop;
    public $count_time='';
    
    
    public function __construct() {
        $this->db = System::load_sys_class("model");
    }   

    public function config($shop=null,$type=null){
        $this->shop = $shop;        
    }
    
    public function get_run_tocode(){
        
    }
    
    public function returns(){
    
    
    }
    
    
    public function run_tocode(&$time='',$num=100,$cyrs='233'){
        if(empty($time))return false;
        if(empty($num))return false;
        if(empty($cyrs))return false;
        $this->times = $time;
        $this->num = $num;
        $this->cyrs = $cyrs;
        $this->get_code_user_html();
        $this->get_user_go_code();
    }

    private function get_code_dabai(){
    //时时彩start
             //分解购买结束时的时间
            $sj_y=date("Y",microtime(true));
            $sj_m=date("m",microtime(true));
            $sj_d=date("d",microtime(true));
            $sj_h=date("H",microtime(true));
            $sj_i=date("i",microtime(true));
            $sj_s=date("s",microtime(true));
            $shi_1=substr(date("i",microtime(true)),0,1);
            $shi_2=substr(date("i",microtime(true)),1,2);
            $zjsi=(int)($sj_h.$sj_i.$sj_s);
            //中间时时彩没有开奖的区域
            if($zjsi>=15600&&$zjsi<=95100){
                $this->ssc_code=0;$this->ssc_opentime=0;$this->ssc_phase=0; 
            }
            else{
                $ssccode=$this->ssccode();
                if($ssccode){
                    $Lotteryconf=include G_CONFIG.'dolotterycon.inc.php';               
                    $ssc_opentime=$Lotteryconf['open'];
                if($this->times-$ssc_opentime<600){
                    $this->ssc_code=$Lotteryconf['code'];
                    $this->ssc_opentime=$Lotteryconf['open'];
                    $this->ssc_phase=$Lotteryconf['phase'];
                }
                }else{
                $this->ssc_code=0;$this->ssc_opentime=0;$this->ssc_phase=0;
                }
            }

         //时时彩end
        $go_list = $this->go_list;
        $html=array();
        $count_time = 0;
            foreach($go_list as $key=>$v){
                $html[$key]['time'] = $v['time'];   
                $html[$key]['username'] = $v['username'];   
                $html[$key]['uid'] = $v['uid'];
                $html[$key]['shopid'] = $v['shopid'];   
                $html[$key]['shopname'] = $v['shopname'];   
                $html[$key]['shopqishu'] = $v['shopqishu'];
                $html[$key]['gonumber'] = $v['gonumber'];           
                $h=abs(date("H",$v['time']));
                $i=date("i",$v['time']);
                $s=date("s",$v['time']);    
                list($time,$ms) = explode(".",$v['time']);
                $time = $h.$i.$s.$ms;
                $html[$key]['time_add'] = $time;
                $count_time += $time;   
            }   
        $this->go_content = serialize($html);
    
        $this->count_time=$count_time;      
        $countadd=$count_time+$this->ssc_code;  
        if(!$this->count_time){
        sleep(1);
        }
        
        $this->go_code = 10000001+fmod($countadd,$this->cyrs);
    
    }
    
    private function get_code_yibai(){  
    //时时彩start
             //分解购买结束时的时间
            $sj_y=date("Y",microtime(true));
            $sj_m=date("m",microtime(true));
            $sj_d=date("d",microtime(true));
            $sj_h=date("H",microtime(true));
            $sj_i=date("i",microtime(true));
            $sj_s=date("s",microtime(true));
            $shi_1=substr(date("i",microtime(true)),0,1);
            $shi_2=substr(date("i",microtime(true)),1,2);
            $zjsi=(int)($sj_h.$sj_i.$sj_s);
            //中间时时彩没有开奖的区域
            if($zjsi>=15600&&$zjsi<=95100){
                $this->ssc_code=0;$this->ssc_opentime=0;$this->ssc_phase=0; 
            }
            else{
                $ssccode=$this->ssccode();
                if($ssccode){
                    $Lotteryconf=include G_CONFIG.'dolotterycon.inc.php';               
                    $ssc_opentime=$Lotteryconf['open'];
                if($this->times-$ssc_opentime<600){
                    $this->ssc_code=$Lotteryconf['code'];
                    $this->ssc_opentime=$Lotteryconf['open'];
                    $this->ssc_phase=$Lotteryconf['phase'];
                }
                }else{
                $this->ssc_code=0;$this->ssc_opentime=0;$this->ssc_phase=0;
                }
            }
            
         //时时彩end
        $time = $this->times;
        $cyrs = $this->cyrs;        
        $h=abs(date("H",$time));
        $i=date("i",$time);
        $s=date("s",$time);     
        $w=substr($time,11,3);
        $num= $h.$i.$s.$w;
        $num=$num+$this->ssc_code;
        if(!$cyrs)$cyrs=1;
        $this->go_code = 10000001+fmod($num*100,$cyrs);
        $this->go_content = false;
    }
    
        //时时彩开奖
    public function  ssccode(){
        if(file_exists(G_SYSTEM.'modules/ssclottery/')){
                $ssclottery="ssclottery";
                $config = $this->db->GetOne("select value from `@#_caches` where `key`='$ssclottery'");
                if(isset($config)&&$config['value']==1){
                date_default_timezone_set('PRC');
                $Lotteryconf= include G_CONFIG.'dolotterycon.inc.php';
                //判断今天的开奖号出来没有
                $url="http://c.opencai.net/vrk5s9F9NKtqkqwWcbo1RxDR/cqssc.json";//彩票链接
                $url_content = mb_convert_encoding((String)file_get_contents($url),'utf-8','gbk');//获取彩票全部内容    
                $url_content = (Array)json_decode(trim($url_content));
                $ssc_data = (Array)($url_content['data']);
                $ssc_datetime=$ssc_data[0]->opentime;//中奖时间显示
                $ssc_open=strtotime($ssc_datetime);//时时彩开奖时间戳
                if($Lotteryconf['open']==$ssc_open){//判断日志文件中是否记录此条数据
                    $ssc_data = null;
                    $url_content = null;
                    return true;
                }
                else{       
                    $ssc_phase=$ssc_data[0]->expect;//时时彩期数                                
                    $code=$ssc_data[0]->opencode;    
                    $code=str_replace(',','',$code);    //时时彩中奖号码
                    $this->ssc_code=$Lotteryconf['code'];
                    $this->ssc_opentime=$Lotteryconf['open'];
                    $this->ssc_phase=$Lotteryconf['phase'];                    
                    //记录时时彩开奖信息open->开奖时间戳,code->开奖号码,phase->开奖期数
                    $html = "<?php return array('open'=>'".$ssc_open."','code'=>'".$code."','phase'=>'".$ssc_phase."'); ?>";         
                    file_put_contents(G_CONFIG.'dolotterycon.inc.php',$html);
                    $ssc_data = null;
                    $url_content = null;
                    return true;
                }
                
            }
            else {
            return false;
            }       
            }   
        else {
            return false;
            }   
    }
    
    
    private function get_user_go_code(){
        
        if(file_exists(G_SYSTEM.'modules/goodspecify/lib/'.'itocode.class.php')):
            $itocode = System::load_app_class("itocode","goodspecify");
            $itocode->go_itocode($this->shop,$this->go_code,$this->go_content,$this->count_time);
        endif;
        $this->get_code_user_html();
    }
        
    private function get_code_user_html(){      
        $time = $this->times;
        $num  = $this->num;
        $update_pay = System::load_app_model("UserPay","common");//加载购买商品model
        $wherewords="`ur_time` < '$time' order by `ur_id` DESC limit 0,{$num}";         
        $this->go_list = $update_pay->SelectRecord($wherewords,'*','GetList');
        if($this->go_list  && count($this->go_list) >= $this->num){
            $this->get_code_dabai();
        }else{
            $this->get_code_yibai();
        }
    }
    
}