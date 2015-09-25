<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */
//お支払方法
define('PAYMENT_CREDIT',0);
define('PAYMENT_BANK'  ,1);
define('PAYMENT_CASH'  ,2);
define('PAYMENT_CONVINI',3);
define('PAYMENT_MEMBER',4);

//最大販売数量の初期値(DBの初期値 adveteise_product:max_quantity)
define('MAX_SALE_QUANTITY',30);

//配達方法
define('DELIVER_MEMBER' ,0);
define('DELIVER_KURONEKO',1);

//配送区分（バラ売り)
define('TEMP_NORMAL',1);
define('TEMP_COLD',2);
define('TEMP_FREEZE',3);
define('TEMP_ICE',4);

//最大梱包重量
define('MAX_WEIGHT',15000);

/* 重量サンプル　*/
define('SAMPLE_VOLUME',4551050);
define('SAMPLE_WEIGHT',461);
//温度帯
//define('NORMAL',1);
//define('COLD',2);
//define('FREEZE',3);
//define('ICE',4);

/*　商品状態 */
define('ONSALE',1);
define('DISCON',0);

/* 商品の受注状態 */
define('NOORDER',0);
define('RECIEVED',1);
define('CANCELED',2);
define('ORDERED',3);
define('DELIVERED',4);

/* 入金　*/
define('NOPAID',0);
define('PAID',1);

/*PATH*/
define('IMAGE_PATH','products/');
define('PRODUCT_IMAGE_PATH','images/products/');
define('ICON_PATH','icon/');
define('AD_IMAGE_PATH','images/advertise/');
define('DELIVERY_IMAGE_PATH','images/delivery/');
define('MAINVISUAL_IMAGE_PATH','images/mainvisual/');
define('BANNER_IMAGE_PATH','images/banner/');
define('INFORMATION_IMAGE_PATH','images/information/');
define('AD_PRODUCT_IMAGE_PATH','images/ad_products/');

/*MAIL*/
define('ORDER_TEMPLATE_FOR_USER',3);
define('ORDER_TEMPLATE_FOR_ADMIN',4);
define('ACCOUNT_CHANGE_FOR_USER',5);
define('ACCOUNT_CHANGE_FOR_ADMIN',6);
define('QUESTION_FOR_USER',1);
define('QUESTION_FOR_ADMIN',2);
define('ORDER_CHANGE_FOR_USER',7);
define('ORDER_CHANGE_FOR_ADMIN',8);

