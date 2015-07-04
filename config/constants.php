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

//温度帯
//define('NORMAL',1);
//define('COLD',2);
//define('FREEZE',3);
//define('ICE',4);

