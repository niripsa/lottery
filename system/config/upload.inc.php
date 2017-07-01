<?php 

			/*
				上传和水印配置
				@up_image_type 		上传图片类型
				@up_soft_type		上传附件类型
				@up_media_type		上传媒体类型
			    @upimgsize			允许图片最大大小
				@upfilesize			允许附件最大大小
				@watermark_off		水印开启
				@watermark_type		水印类型
				@watermark_condition	水印添加条件
				@watermark_text		文本水印配置
				@watermark_image	图片水印地址
				@watermark_position 水印位置
			    @watermark_apache	透明度
			    @watermark_good		清晰度

				@thumb_user         用户头像缩略图
				@thumb_goods        商品图片缩略图
			*/
			
return array (
  'up_image_type' => 'png,jpg,jpeg',
  'up_soft_type' => 'zip,gz,rar,iso,doc,ppt,wps,xls',
  'up_media_type' => 'swf,flv,mp3,wav,wma,rmvb',
  'upimgsize' => '1024000',
  'upfilesize' => '1024000',
  'thumb_user' => 
  array (
    30 => '30',
    80 => '80',
    160 => '160',
  ),
  'thumb_goods' => 
  array (
    80 => '80',
    200 => '200',
    400 => '400',
  ),
  'watermark_off' => '',
  'watermark_condition' => 
  array (
    'width' => '100',
    'height' => '100',
  ),
  'watermark_type' => 'image',
  'watermark_text' => 
  array (
    'text' => '',
    'color' => '#996633',
    'size' => '15',
    'font' => 'statics/uploads/banner/yahei.ttf',
  ),
  'watermark_image' => 'banner/20150507/63bf061116.jpg',
  'watermark_position' => 's',
  'watermark_apache' => '50',
  'watermark_good' => '80',
);
