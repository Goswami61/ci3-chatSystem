<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['default_controller'] = 'Chat_controller/index';
$route['register'] = 'Chat_controller/register';
$route['login'] = 'Chat_controller/login_form';
$route['user-login'] = 'Chat_controller/user_login';
$route['dashboard'] = 'Chat_controller/dashboard';
$route['logout'] = 'Chat_controller/logout';
$route['start'] = 'Chat_controller/start';
$route['chat/(:num)'] = 'Chat_controller/chat/$1';
$route['send-message'] = 'Chat_controller/send_message';
$route['get-message'] = 'Chat_controller/get_msg';

// update Profile

$route['profile'] = 'Chat_controller/get_profile';
$route['update-profile'] = 'Chat_controller/update_profile';

// Change Password

$route['change-password'] = 'Chat_controller/change_password';

// Api 

$route['api/register-user'] = 'Api/index';
$route['api/login'] = 'Api/login';
$route['api/dashboard'] = 'Api/dashboard';
$route['api/get-message'] = 'Api/get_message';
$route['api/send-message'] = 'Api/send_message';
$route['api/get-user'] = 'Api/get_user';
$route['api/update-profile'] = 'Api/update_profile';
$route['api/change-password'] = 'Api/change_password';
// print_r($route); exit;