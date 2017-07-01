<?php
class tpshop extends SystemAction{
	const SECRET = "TPSHOP#@198712%adsadjfasadfsadf#$%@12";
	private function _verifySign($sSign, $sData){
		if(md5(self::SECRET . $sData) == $sSign){
			return true;
		}else{
			return false;
		}
	}

	public function get_cate_info(){
		echo json_encode(GetCate('1',5,4,1,"ASC"), JSON_UNESCAPED_UNICODE);
	}

	public function get_user_info(){
		$uid  = intval( $_REQUEST['uid'] );
		if(!$this->_verifySign($_REQUEST['sign'], $uid)){
			exit();
		}

		$userdb = System::load_app_model("user", "common");
        $info = $userdb->SelectUserUid( $uid );
		echo json_encode($info);
	}

	public function payby_money_points(){
		$aPostInfo = $_POST;

		$aRet = array();
		if(!$this->_verifySign($aPostInfo['sign'], $aPostInfo['user_id'])){
			$aRet['errno'] = 1;
			exit(json_encode($aRet));
		}

		$mysql_model = System::load_sys_class("model");

		//减掉用户的积分和money
		$this->Userid = $aPostInfo['user_id'];
		if(!empty($this->Userid)){
			$fNeedMoney  = floatval($aPostInfo['need_money']);
			$fNeedPoints = floatval($aPostInfo['need_points']);

			$sql = "update `@#_user` set `money` = `money` - ${fNeedMoney}, `user_points` = `user_points` - ${fNeedPoints} where `uid` = $this->Userid AND `money` >= ${fNeedMoney} AND `user_points` >= ${fNeedPoints}";
			$mysql_model->Query($sql);
			$iAffectedRows = intval($mysql_model->affected_rows());

			if(empty($iAffectedRows)){
				$aRet['errno']  = 2;
				$aRet['errmsg'] = '余额不足请充值';
				exit(json_encode($aRet));
			}

			//成功扣除
			$aRet['errno'] = 0;
			exit(json_encode($aRet));
		}else{
			$aRet['errno'] = 3;
			exit(json_encode($aRet));
		}
	}

	public function integralMall(){
		$url = "/tpshop/index.php?m=Home&c=Goods&a=integralMall";
		header("Location:$url");
	}
}