<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class lottery extends admin{
    public function kj_record(){
        $num   = 15;
        $mysql_model = System::load_sys_class("model");
        $total = $mysql_model->data_num("lottery_stage");
        $page  = System::load_sys_class("page");
        $page->config( $total, $num );
        //status=2 代表已开奖 status = 1代表还未开奖
        $sql = "select * from `@#_lottery_stage` where `status` in (1,2) order by `end_time` desc " . $page->setlimit(0);
        $mysql_model = System::load_sys_class("model");
        $aLotteryLists = $mysql_model->GetList($sql);

        $this->view->data('lottery_lists', $aLotteryLists);     
        $this->view->data( "page", $page->show( "li", true ) );
        $this->view->tpl( "lottery_stage.list" );
    }

    public function modify_lottery_no(){
        $iNumber = intval($_POST['number']);
        if($iNumber < 0 || $iNumber > 9){
            exit;
        }

        $sql =  "select * from `@#_lottery_stage` where `status` in (1) order by `end_time` desc limit 1";
        $mysql_model = System::load_sys_class("model");
        $aLotteryInfo = $mysql_model->GetOne($sql);
        if(empty($aLotteryInfo) || time() >= strtotime($aLotteryInfo['end_time']) + 3){
            exit();
        }

        $sNow = date("Y-m-d H:i:s");
        $id = intval($aLotteryInfo['id']);
        $sql = "update `@#_lottery_stage` set setting_number = $iNumber, setting_time = '$sNow' where id = $id";
        $mysql_model->Query($sql);

        $aRet = [];
        $aRet['errno'] = 0;
        exit(json_encode($aRet));
    }

    public function buy_record(){
        $num   = 15;
        $mysql_model = System::load_sys_class("model");
        $total = $mysql_model->data_num("user_buy_lottery");
        $page  = System::load_sys_class("page");
        $page->config( $total, $num );

        $order = "";
        if ( isset( $_POST["paixu_submit"] ) ){
            $paixu = $_POST["paixu"];
            switch ( $paixu )
            {
                case 'time1':
                    $order .= " `buy_time` DESC";
                break;
                case 'time2':
                    $order .= " `buy_time` ASC";
                break;
            }
        }else{
            $order .= " `buy_time` DESC";
            $paixu = "time1";
        }

        $sql = "select * from `@#_user_buy_lottery` order by $order " . $page->setlimit(0);
        $recordlist = $mysql_model->GetList($sql);

        foreach ($recordlist as $id => $aOneRecord) {
            if($recordlist[$id]['status'] == 1){
                $recordlist[$id]['status_txt'] = '未开奖';
            }elseif($recordlist[$id]['status'] == 2) {
                $recordlist[$id]['status_txt'] = '已撤单';
            }elseif($recordlist[$id]['status'] == 3){
                $recordlist[$id]['status_txt'] = '已开奖';
            }
        }

        $this->view->data( "recordlist", $recordlist );
        $this->view->data( "page", $page->show( "li", true ) );
        $this->view->tpl( "lottery_order.list" );
    }

    public function buy_huizong(){
        $sql = "select * from `@#_lottery_stage` where `status` in (1) order by `end_time` desc limit 1";
        $mysql_model = System::load_sys_class("model");
        $aLotteryInfo = $mysql_model->GetOne($sql);
        if(empty($aLotteryInfo)){
            exit();
        }

        $stage_no = strval($aLotteryInfo['stage_no']);

        $sql = "select * from `@#_user_buy_lottery` where stage_no = '$stage_no' and `status` = 1";
        $aStageNos = $mysql_model->GetList($sql);

        $aHuiZong = array();
        $aHuiZong[1] = 0;
        $aHuiZong[2] = 0;
        $aHuiZong[3] = 0;
        $aHuiZong[4] = 0;
        /* $aHuiZong[1]--单大 [2]--单小 [3]--双大 [4]--双小 */
        foreach($aStageNos as $id => $aOneStage){
            if(!empty($aOneStage['buy_content_id'])){
                //$aBuyInfo = str_split($aOneStage['buy_content_id']);
                $buy = intval($aOneStage['buy_content_id']);
                if ($buy == 11) {
                    $aHuiZong[1]++;
                }elseif ($buy == 12) {
                    $aHuiZong[2]++;
                }elseif ($buy == 21) {
                    $aHuiZong[3]++;
                }elseif ($buy == 22) {
                    $aHuiZong[4]++;
                }elseif ($buy == 10) {
                    $aHuiZong[1]++;
                    $aHuiZong[2]++;
                }elseif ($buy == 20) {
                    $aHuiZong[3]++;
                    $aHuiZong[4]++;
                }elseif ($buy == 1) {
                    $aHuiZong[1]++;
                    $aHuiZong[3]++;
                }elseif ($buy == 2) {
                    $aHuiZong[2]++;
                    $aHuiZong[4]++;
                }
            }
        }

        $this->view->data('stage_no', $stage_no);
        $this->view->data('kj_time', $aLotteryInfo['end_time']);
        $this->view->data('huizong_info', $aHuiZong);
        $this->view->tpl( "lottery_huizong.list" );
    }
}
?>
