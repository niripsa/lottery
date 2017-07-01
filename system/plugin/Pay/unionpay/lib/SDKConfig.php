<?php


// cvn2加密 1：加密 0:不加密
const SDK_CVN2_ENC = 0;
// 有效期加密 1:加密 0:不加密
const SDK_DATE_ENC = 0;
// 卡号加密 1：加密 0:不加密
const SDK_PAN_ENC = 0;
 
// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######
// 签名证书路径
const SDK_SIGN_CERT_PATH = 'D:\wamp\www\YunGouCmsm\system\modules\pay\lib\unionpay\unionpay.pfx';

// 签名证书密码
 const SDK_SIGN_CERT_PWD = '123456';
 
// 验签证书
const SDK_VERIFY_CERT_PATH = '';

// 密码加密证书
const SDK_ENCRYPT_CERT_PATH = '';

// 验签证书路径
const SDK_VERIFY_CERT_DIR = '';

// 前台请求地址
const SDK_FRONT_TRANS_URL = '';

// 后台请求地址
const SDK_BACK_TRANS_URL = '';

// 批量交易
const SDK_BATCH_TRANS_URL = '';

//单笔查询请求地址
const SDK_SINGLE_QUERY_URL = '';

//文件传输请求地址
const SDK_FILE_QUERY_URL = '';

//有卡交易地址
const SDK_Card_Request_Url = '';

//App交易地址
const SDK_App_Request_Url = '';


// 前台通知地址 (商户自行配置通知地址)
const SDK_FRONT_NOTIFY_URL = 'http://ceshi.yyyg.com/YunGouCmsm/?/pay/unionpay_url/qiantai';
// 后台通知地址 (商户自行配置通知地址)
const SDK_BACK_NOTIFY_URL = 'http://ceshi.yyyg.com/YunGouCmsm/?/pay/unionpay_url/houtai';


//文件下载目录 
const SDK_FILE_DOWN_PATH = 'd:\wamp\www\YunGouCmsm\system\modules\pay\lib\unionpay\file/';

//日志 目录 
const SDK_LOG_FILE_PATH = 'd:\wamp\www\YunGouCmsm\system\modules\pay\lib\unionpay\logs/';


//日志级别
const SDK_LOG_LEVEL = 'INFO';


    
?>