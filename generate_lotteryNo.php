<?php
//crontab定时任务 每10分钟跑一次 判断是否应该开奖 同时生成下一期开奖号码
if(php_sapi_name() != 'cli'){
	exit("could not be called by api!");
}
set_time_limit(0);
$sNow = date('Y-m-d H:i:s');

$sIp = "127.0.0.1";
$sPort = 3306;
$sUserName = "duobao";
$sUserPass = "lkjoe990kldskfj";
$sDatabase = "duobao";
$iTime = 5; //5分钟开一轮

$con = mysqli_connect($sIp, $sUserName, $sUserPass, $sDatabase, $sPort);

$sql = "select * from `yg_lottery_stage` where `status`= 1 order by `begin_time` desc limit 1";
$result = mysqli_query($con, $sql);
$aRes = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (empty($aRes)) {
	//说明是第一期
	$stage_no = date("Ymd") . "-0001";
	$sStartTime = date("Y-m-d H:i:s");
	$sEndTime = date("Y-m-d H:i:s", strtotime("+$iTime minutes"));
	$sInsertSql = "insert into `yg_lottery_stage` set `stage_no` = '$stage_no', `begin_time`='$sStartTime', `end_time`='$sEndTime', `status` = 1";

	mysqli_query($con, $sInsertSql);
}else{
	//分别计算单大、单小、双大、双小购买的人数，选其中最少的作为开奖抽取范围
	$stage_no = strval($aRes['stage_no']);
	$sql = "select * from `yg_user_buy_lottery` where stage_no = '$stage_no' and `status` = 1";
	$result = mysqli_query($con, $sql);
	$aAllUserBuyInfo = array();
	while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	    $aAllUserBuyInfo[] = $row;
	}
	if (!empty($aAllUserBuyInfo)) {
		/* [1]--单大 [2]--单小 [3]--双大 [4]--双小 */	
		$aHuiZong = array();
	    $aHuiZong[1] = 0;
	    $aHuiZong[2] = 0;
	    $aHuiZong[3] = 0;
	    $aHuiZong[4] = 0;
		foreach ($aAllUserBuyInfo as $aUserBuyInfo) {
		    if(!empty($aUserBuyInfo['buy_content_id'])){
		        $buy = intval($aUserBuyInfo['buy_content_id']);
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
		asort($aHuiZong, SORT_NUMERIC);
		$iRange = array_keys($aHuiZong)[0];
		/* $iRange为1-4的整数，同$aHuiZong */		
	}

	if(!empty($aRes['end_time'])){
		while (time() < strtotime($aRes['end_time'])) {
			sleep(1);
		}
	}


	$iRange = @$iRange ? : 0;
	//运行到这里说明到时间开奖了
	$iLotteryNo = generateLotteryNo( $iRange );
	$sql = "select * from `yg_lottery_stage` where `status`= 2 order by `begin_time` desc limit 1";
	$result = mysqli_query($con, $sql);
	$aLastRes = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($iLotteryNo == intval($aLastRes['lottery_number'])){
		//如果与上一期一样 则再生成一次
		$iLotteryNo = generateLotteryNo( $iRange );
	}

	//更新中奖号码
	$id = $aRes['id'];
	$sUpdateSql = "update `yg_lottery_stage` set `lottery_number` = $iLotteryNo, `status` = 2 where id = $id";
	
	mysqli_query($con, $sUpdateSql);
	//要把数组中的lottery_number也改为正确值
	$aRes['lottery_number'] = intval($iLotteryNo);

	//插入新的一期
	$sDay = strval(explode("-", $aRes['stage_no'])[0]);
	$iTodayNo = intval(explode("-", $aRes['stage_no'])[1]);
	if(empty($iTodayNo)){
		$iTodayNo = 1;
	}
	
	if(date("Ymd") == $sDay){
		++$iTodayNo;
		$stage_no = $sDay . "-" . str_pad($iTodayNo, 4, '0', STR_PAD_LEFT);
	}else{
		$stage_no = date("Ymd") . '-0001';
	}

	$sStartTime = date("Y-m-d H:i:s");
	$sEndTime = date("Y-m-d H:i:s", strtotime("+$iTime minutes"));
	$sInsertSql = "insert into `yg_lottery_stage` set `stage_no` = '$stage_no', `begin_time`='$sStartTime', `end_time`='$sEndTime', `status` = 1";
	mysqli_query($con, $sInsertSql);

	//将购买上一期的所有订单根据是否中奖情况更新订单状态
	$sLastStageNo = strval($aRes['stage_no']);
	$iLotteryNo = intval($aRes['setting_number']) == -1?intval($aRes['lottery_number']) : intval($aRes['setting_number']);
	$i = 0;
	while (true) {
		$sql = "select * from `yg_user_buy_lottery` where `stage_no` = '$sLastStageNo' order by id limit $i,1";
		$result = mysqli_query($con, $sql);
		$aUserBuyInfo = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (empty($aUserBuyInfo)) {
			break;
		}

		if($aUserBuyInfo['status'] != 1){
			continue;
		}

		++$i;

		//根据用户购买内容觉得是否中奖
		if(!empty($aUserBuyInfo['buy_content_id'])){
			$aBuyContent = str_split($aUserBuyInfo['buy_content_id']);
			$buy1 = intval($aBuyContent[0]);
			$buy2 = intval($aBuyContent[1]);
			$buy_money = intval($aUserBuyInfo['buy_money']);
			/* 如果同时买单双和买大小,每注4夺宝币;如果仅买一项,每注2夺宝币 */
			/* 可以根据金额和购买方式确定注数 */
			if ($buy1 && $buy2) {
				$multiple = intval($buy_money/4);
			}elseif ($buy1 || $buy2) {
				$multiple = intval($buy_money/2);
			}

			//buy1代表是单双 buy2代表是大小
			$fUserPoints = 0;

			//如果开奖号码为单数 并且购买的是单数 则中奖
			if($iLotteryNo%2 == 1 && $buy1 == 1){
				$fUserPoints += 3.8*$multiple;
			}elseif($iLotteryNo%2 == 0 && $buy1 == 2){
				//如果开奖号码为双数 并且购买的是双数 则中奖
				$fUserPoints += 3.8*$multiple;
			}

			//大小同上
			if($iLotteryNo>=5&&$iLotteryNo<=9&&$buy2 == 1){
				$fUserPoints += 3.8*$multiple;
			}elseif($iLotteryNo>=0&&$iLotteryNo<=4&&$buy2 == 2){
				$fUserPoints += 3.8*$multiple;
			}

			$id = $aUserBuyInfo['id'];
			$sql = "update `yg_user_buy_lottery` set `award_points` = $fUserPoints, `status` = 3 where id = $id AND `status` = 1 AND `stage_no` = '$sLastStageNo'";
			
			mysqli_query($con, $sql);

			echo "sql:" . $sql . '|lottery_no:' . $iLotteryNo . PHP_EOL;

			//同时更新用户积分字段
			$uid = intval($aUserBuyInfo['user_id']);
			$sql = "update `yg_user` set `user_points` = `user_points` + $fUserPoints where `uid`= $uid";

			mysqli_query($con, $sql);
		}//end of if
	}

}


//生成指定范围内的号码作为开奖号码
function generateLotteryNo( $iRange=0 ){
	/* 
		$iRange=0 -- 无限制(0-9)
		1 --- 单大 --- 5,7,9
		2 --- 单小 --- 1,3
		3 --- 双大 --- 6,8
		4 --- 双小 ---,0,2,4
	*/
	//将指定范围内的数字按照类似圆桌排列,使用生成的随机数字作为索引,得到指定范围内的某个数字
	switch ( $iRange ) {
		case 1:
			$aRange = [5,7,9];
			break;
		case 2:
			$aRange = [1,3];
			break;
		case 3:
			$aRange = [6,8];
			break;						
		case 4:
			$aRange = [0,2,4];
			break;
		case 0:
			$aRange = [0,1,2,3,4,5,6,7,8,9];
			break;					
		default:
			$aRange = [0,1,2,3,4,5,6,7,8,9];
			break;
	}

	mt_srand(time() - mt_rand(10000, 100000));
	$iPow1 = mt_rand(1, 50);
	mt_srand(time() - mt_rand(10000, 100000));
	$iPow2 = mt_rand(1, 50);
	if(function_exists('pi')){
		defined('PI') or define('PI', pi());
	}else{
		defined('PI') or define('PI', 3.1415926);
	}

	$aRandCals = array('+', '-', '*', '/');
	shuffle($aRandCals);

	$fSum1 = pow(PI, $iPow1);
	$fSum2 = exp($iPow2);
	eval("\$fSum=\$fSum1 $aRandCals[0] \$fSum2;");

	$sSum1 = str_replace(array('.', 'e+'), '', strval(strtolower($fSum1)));
	$sSum2 = str_replace(array('.', 'e+'), '', strval(strtolower($fSum2)));
	$sSum = $sSum1 . $sSum2;

	$iIndex = substr($sSum, mt_rand(0,strlen($sSum) -1),1);
	return $aRange[$iIndex % count($aRange)];
}

mysqli_close($con);

echo "generateLotteryNo end_time: " . date("Y-m-d H:i:s") . PHP_EOL;

/*$test = [];
for($i=0; $i<=9; $i++){
	$test[$i] = 0;
}

for($i=1; $i<=10000; $i++){
	$test[generateLotteryNo()]++;
}*/
