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

$route['default_controller'] = "login_controller";
$route['404_override'] = 'error';

/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login_controller/loginMe';


$route['home'] = 'home_controller';

$route['carousel'] = 'carousel_controller';
$route['carousel_add'] = 'carousel_controller/additem';
$route['carousel_edit/(:any)'] = 'carousel_controller/edititem/$1';
$route['carousel_upload'] = 'carousel_controller/upload_img';


$route['provider'] = 'provider_controller';
$route['provider_add'] = 'provider_controller/additem';




$route['shop'] = 'shop';
$route['addshop'] = 'shop/addShop';
$route['shopListing/(:any)/(:num)'] = "shop/shoplisting/$1/$2";
$route['editshop/(:any)'] = 'shop/editshop/$1';
$route['showshop/(:any)'] = 'shop/showshop/$1';


$route['usermanage'] = 'usermanage';
$route['userCoListing/(:num)/(:any)'] = 'usermanage/userCollectListing/$1/$2';

$route['logout'] = 'systemmanage/logout';
$route['roleListing'] = 'systemmanage/roleListing';
$route['userListing'] = 'systemmanage/userListing';
$route['addNew'] = "systemmanage/addNew";

$route['addNewUser'] = "systemmanage/addNewUser";
$route['editOld'] = "systemmanage/editOld";
$route['editOld/(:num)'] = "systemmanage/editOld/$1";
$route['editUser'] = "systemmanage/editUser";
$route['deleteUser/(:num)'] = "systemmanage/deleteUser/$1";
$route['deleteRole/(:num)'] = "systemmanage/deleteRole/$1";
$route['loadChangePass'] = "systemmanage/loadChangePass";
$route['changePassword'] = "systemmanage/changePassword";
$route['updateUserPassword'] = "systemmanage/updateUserPassword";
$route['pageNotFound'] = "systemmanage/pageNotFound";
$route['checkEmailExists'] = "systemmanage/checkEmailExists";

$route['forgotPassword'] = "login_controller/forgotPassword";
$route['resetPasswordUser'] = "login_controller/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login_controller/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login_controller/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login_controller/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login_controller/createPasswordUser";

/* End of file routes.php */
/* Location: ./application/config/routes.php */