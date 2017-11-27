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

$route['provider'] = 'user_manage/provider_controller';
$route['provider_add'] = 'user_manage/provider_controller/additem';
$route['editProvider/(:any)'] = 'user_manage/provider_controller/edititem/$1';
$route['showProvider/(:any)'] = 'user_manage/provider_controller/showitem/$1';

$route['shipman_manage'] = 'product_manage/shipping_controller/shipman_manage';
$route['shipman_add'] = 'product_manage/shipping_controller/additem';
$route['shipman_show/(:any)'] = 'product_manage/shipping_controller/showitem/$1';
$route['shipman_edit/(:any)'] = 'product_manage/shipping_controller/edititem/$1';

$route['shipman'] = 'product_manage/shipping_controller';
$route['shipping'] = 'product_manage/shipping_controller/shipping';
$route['shipping_show/(:any)'] = 'product_manage/shipping_controller/showShipping/$1';

$route['shop'] = 'user_manage/shop_controller';
$route['showShopinfo/(:any)'] = 'user_manage/shop_controller/showitem/$1';

$route['product_format'] = 'product_manage/product_format_controller';
$route['product_format_add'] = 'product_manage/product_format_controller/additem';
$route['product_format_edit/(:any)'] = 'product_manage/product_format_controller/edititem/$1';
$route['product_format_show/(:any)'] = 'product_manage/product_format_controller/showitem/$1';

$route['product_type'] = 'product_manage/product_util_controller/productUtil/1';
$route['product_unit'] = 'product_manage/product_util_controller/productUtil/2';

$route['product_brand'] = 'product_manage/product_util_controller/productUtil/3';
$route['product_brand_add'] = 'product_manage/product_util_controller/additem';
$route['product_brand_edit/(:any)'] = 'product_manage/product_util_controller/edititem/$1';

$route['product'] = 'product_manage/product_controller';
$route['product_add'] = 'product_manage/product_controller/additem';
$route['product_edit/(:any)'] = 'product_manage/product_controller/edititem/$1';
$route['product_show/(:any)'] = 'product_manage/product_controller/showitem/$1';

$route['single_activity'] = 'activity_manage/single_activity_controller';
$route['single_activity_add'] = 'activity_manage/single_activity_controller/additem';
$route['single_activity_edit/(:any)'] = 'activity_manage/single_activity_controller/edititem/$1';
$route['single_activity_copy/(:any)'] = 'activity_manage/single_activity_controller/copyitem/$1';
$route['single_activity_show/(:any)'] = 'activity_manage/single_activity_controller/showitem/$1';

$route['multiple_activity'] = 'activity_manage/multiple_activity_controller';
$route['multiple_activity_add'] = 'activity_manage/multiple_activity_controller/additem';
$route['multiple_activity_edit/(:any)'] = 'activity_manage/multiple_activity_controller/edititem/$1';
$route['multiple_activity_copy/(:any)'] = 'activity_manage/multiple_activity_controller/copyitem/$1';
$route['multiple_activity_show/(:any)'] = 'activity_manage/multiple_activity_controller/showitem/$1';

$route['order'] = 'order_controller';
$route['order_show/(:any)'] = 'order_controller/showitem/$1';

$route['withdraw'] = 'transaction_manage/withdraw_controller';
$route['withdraw_add'] = 'transaction_manage/withdraw_controller/additem';
$route['withdraw_show/(:any)'] = 'transaction_manage/withdraw_controller/showitem/$1';
$route['withdraw_edit/(:any)'] = 'transaction_manage/withdraw_controller/edititem/$1';

$route['transaction'] = 'transaction_manage/transaction_controller';
$route['showMyMoney'] = 'transaction_manage/transaction_controller/showMyMoney';
$route['bankinfo_add'] = 'transaction_manage/transaction_controller/additem';
$route['bankinfo_show/(:any)'] = 'transaction_manage/transaction_controller/showitem/$1';

$route['coupon'] = 'coupon_controller';

$route['news'] = 'news_controller';

$route['shipman_statistics'] = 'statistics_controller/shipman_statistics';
$route['productsale'] = 'statistics_controller/productsale';
$route['shop_statistics'] = 'statistics_controller/shop_statistics';
$route['provider_statistics'] = 'statistics_controller/provider_statistics';
$route['brand_statistics'] = 'statistics_controller/brand_statistics';
$route['type_statistics'] = 'statistics_controller/type_statistics';
$route['sale_performance'] = 'statistics_controller/sale_performance';
$route['recommend_provider'] = 'statistics_controller/recommend_provider';
$route['recommend_shop'] = 'statistics_controller/recommend_shop';
$route['showShops/(:any)'] = 'statistics_controller/showShops/$1';
$route['showProviders/(:any)'] = 'statistics_controller/showProviders/$1';

$route['userlist'] = 'user_manage/user_controller';
$route['user_add'] = 'user_manage/user_controller/additem';
$route['user_edit/(:any)'] = 'user_manage/user_controller/edititem/$1';

$route['usermanage'] = 'usermanage';
$route['userCoListing/(:num)/(:any)'] = 'usermanage/userCollectListing/$1/$2';

$route['logout'] = 'systemmanage/logout';
$route['roleListing'] = 'systemmanage/roleListing';
$route['userListing'] = 'systemmanage/userListing';
$route['addNew'] = "systemmanage/addNew";

$route['addNewUser'] = "systemmanage/addNewUser";
$route['editOld'] = "systemmanage/editOld";
$route['editOld/(:any)'] = "systemmanage/editOld/$1";
$route['editUser'] = "systemmanage/editUser";
$route['deleteUser/(:any)'] = "systemmanage/deleteUser/$1";
$route['deleteRole/(:any)'] = "systemmanage/deleteRole/$1";
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

/////////////----------------REST API ---------------------------///////////////////////////////////////
$route['api/register'] = "api/Shop_Authorization/register_shop";
$route['api/upload_auth'] = "api/Shop_Authorization/authorization_info";
$route['api/reset_password'] = "api/Shop_Authorization/reset_password";
$route['api/login'] = "api/Shop_Authorization/login";

$route['api/menuAndCarouselInfos'] = "api/Shop_Authorization/menuAndCarouselInfos";

$route['api/productInfos'] = "api/Data_manager/activityInfosByTypeAndBrand";

$route['api/transactionInfos'] = "api/Data_manager/shop_transactions";
$route['api/newsInfos'] = "api/Data_manager/shop_news";
$route['api/getMyWallet'] = "api/Data_manager/get_shop_wallet";

$route['api/cartInfo'] = "api/Data_manager/shop_cartInfo";

$route['api/orderInfos'] = "api/Data_manager/shop_orders";
$route['api/orderRequest'] = "api/Data_manager/shop_order_request";
$route['api/orderRequests'] = "api/Data_manager/shop_order_requests";
$route['api/payOrderRequest'] = "api/Data_manager/shop_pay_order";
$route['api/payOrderRequests'] = "api/Data_manager/shop_pay_orders";
$route['api/cancelOrderRequest'] = "api/Data_manager/shop_order_cancel";

$route['api/addFavorite'] = "api/Data_manager/shop_set_favorite";
$route['api/removeFavorite'] = "api/Data_manager/shop_free_favorite";
$route['api/myFavorite'] = "api/Data_manager/shop_favoriteItems";

$route['api/addFeedback'] = "api/Data_manager/shop_feedback";

$route['api/activityStatus'] = "api/Data_manager/get_ActivityStatus";

////////-------------shipping-----------------------------/////////

$route['api/shipping_login'] = "api/Shop_Authorization/shipping_login";

$route['api/shippingComplete'] = "api/Data_manager/shipping_complete";
$route['api/shippingItems'] = "api/Data_manager/get_shippingItems";
$route['api/shippingHistory'] = "api/Data_manager/get_shippingHistory";

$route['api/setLocation'] = "api/Data_manager/setLocation";

$route['api/activity_detail'] = "api/Data_manager/activity_detail";
$route['api/provider_detail'] = "api/Data_manager/provider_detail";
$route['api/getNotifications'] = "api/Data_manager/getNotifications";

$route['api/test'] = "api/Data_manager/test";

$route['api/check_auth'] = "api/Shop_Authorization/check_auth";


/* End of file routes.php */
/* Location: ./application/config/routes.php */