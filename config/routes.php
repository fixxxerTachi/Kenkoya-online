<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
/* front_area */
$route['area/check_area/:any'] = 'front_area/check_area';
$route['area/search_area'] = 'front_area/search_area';
/* front_customer */
$route['customer/show_policy'] = 'front_customer/show_policy';
$route['customer/show_policy/:any'] = 'front_customer/show_policy';
$route['customer/confirm_customer'] = 'front_customer/confirm_customer';
$route['customer/confirm_customer/:any'] = 'front_customer/confirm_customer';
$route['customer/complete_register'] = 'front_customer/complete_register';
$route['customer/login_action'] = 'front_customer/login_action';
$route['customer/login_action/:any'] = 'front_customer/login_action';
$route['customer/set_username'] = 'front_customer/set_username';
$route['customer/notice/:any'] = 'front_customer/notice';
$route['customer/reset_password'] = 'front_customer/reset_password';
$route['customer/set_password'] = 'front_customer/set_password';
$route['customer/renew_user'] = 'front_customer/renew_user';
$route['customer/logout_action'] = 'front_customer/logout_action';
/* front_cart */
$route['cart/show_cart'] = 'front_cart/show_cart';
$route['cart/empty_cart'] = 'front_cart/empty_cart';
$route['cart/change_quantity/:num'] ='front_cart/change_quantity';
$route['cart/change_quantity/:num/:any'] = 'front_cart/change_quantity';
$route['cart/delete_item/:num'] = 'front_cart/delete_item';
$route['cart/delete_item/:num/:any'] = 'front_cart/delete_item';
/* front_advertise */
$route['yotsuba'] = 'front_advertise/index';
$route['yotsuba/detail_advertise/:any'] = 'front_advertise/detail_advertise';
/* front_order */
$route['order/delivery_info'] = 'front_order/delivery_info';
$route['order/input_payment'] = 'front_order/input_payment';
$route['order/confirm_order'] = 'front_order/confirm_order';
$route['order/complete'] = 'front_order/complete';
$route['order/order_process'] = 'front_order/order_process';
$route['order/complete'] = 'front_order/complete';
/*front_contact*/
$route['contact'] = 'front_contact/index';
$route['contact/contact'] = 'front_contact/contact';
$route['contact/confirm_contact'] = 'front_contact/confirm_contact';
$route['contact/complete'] = 'front_contact/complete';
$route['contact/takuhai_member'] = 'front_contact/takuhai_member';
$route['contact/takuhai_member_confirm'] = 'front_contact/takuhai_member_confirm';
/*qustion*/
$route['question'] = 'front_question';
$route['question/index/:num'] = 'front_question/index';
$route['question/detail/:num'] = 'front_question/detail';

$route['default_controller'] = "index";
$route['404_override'] = '';
$route['customer/add_customer'] = 'front_customer/add_customer';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
