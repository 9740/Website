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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'Home';
$route['home'] = $route['default_controller'];
$route['faq'] = 'home/faq';
$route['terms-and-conditions'] ='home/terms_conditions';
$route['contact-us'] = 'home/contact_us';
$route['activity'] = 'home/whats_new';
$route['careers'] = 'home/careers';
$route['doctor-lounge'] = 'services/coming_soon';
$route['channels'] = 'channels/channels';
$route['corporate-health-checkup'] = 'channels/corporate';
$route['symptoms'] = 'home/symptom';
$route['backsymptoms'] = 'home/backsymptom';
$route['retail'] = 'channels/retail';
$route['school'] = 'channels/school';
$route['my-benefits'] = 'home/how_it_works';
$route['health-tips'] = 'home/health_tips';
$route['tax-reduction-on-preventive-health-checkup'] = 'Blogs/index';


$route['offers'] = 'Marketing/offers';
$route['grabon_offer']='Marketing/grabon';
$route['womensday']='Marketing/womensday_offer';
$route['demo_dia'] = 'Marketing/demo_dia';
$route['about-welezo-health/(:any)'] = 'Marketing/landing_page1/$2';
$route['home-health-check-up'] = 'Marketing/landing_page_home_health';
$route['master-health-checkup-in-bangalore-offer'] = 'Marketing/master_health_checkup_landing';
$route['sinior-citizen-health-checkup-in-bangalore'] = 'Marketing/senior_health_checkup_landing';
$route['executive-health-checkup-in-bangalore'] = 'Marketing/executive_health_checkup_landing';
$route['platinum-health-checkup-in-bangalore'] = 'Marketing/platinum_health_checkup_landing';
$route['well-women-health-checkup-in-bangalore'] = 'Marketing/well_women_health_checkup_landing';
$route['family-health-checkup-in-bangalore'] = 'Marketing/family_health_checkup_landing';
$route['welezo-republic-day-offers'] = 'Marketing/welezo_republic_day_offers';
$route['welezo-republic-day-offers-fb'] = 'Marketing/welezo_republic_day_offers_fb';

$route['login']='authentication/cutomer_login';
$route['logout']='authentication/logout';
$route['billing-detail']='Authentication/billing_address';
$route['login-authentication']='Authentication/billing_login';
$route['conform_payment']='Authentication/redirect_ccavanue';
$route['payment-success']='Authentication/redirect_form';
$route['corporate-payment-success']='Authentication/redirect_form1';

$route['transaction-error']='Authentication/transaction_error';

$route['Home-health'] ='Homehealth/index';
$route['homehealth-packages'] = 'Homehealth/packages';
$route['homehealthcheck/(:any)'] = 'Homehealth/home_health_detail/$1';
$route['package/(:any)'] = 'packages/package_detail/$1';

$route['about-us'] = 'about_us/index';
$route['management-team'] = 'about_us/management_team';
$route['advisory-board'] = 'about_us/advisary_board';
$route['packages'] = 'packages/index';
$route['health-check-packages'] = 'packages/health_check_packages';
$route['trelleborg-package'] = 'packages/trelleborg_package';
$route['welezoTest'] = 'packages/index';
$route['pharmacy-partners'] = 'pharmacy/index';
$route['pharmacy-partners/(:any)'] = 'pharmacy/index/$1';
$route['network-partners'] = 'network_partners/index';
$route['network-partners/(:any)'] = 'network_partners/index/$1';

$route['dentistry'] ='Services/dentistry';
$route['health-check'] ='Services/health_check';
$route['consultation'] = 'Services/consultation';
$route['otp-valid'] = 'Authentication/validation';
$route['otp-validation'] = 'Authentication/get_offer';

//customer
$route['customer'] = 'Customer/index';
$route['user-profile']='Customer/profile';
$route['user']='Customer/user';
$route['customer-family']='Customer/customer_family';
$route['product-detail']='Customer/Packages_detail';
$route['setting']='Customer/setting';
$route['reports']='Customer/reports';
$route['Help']='customer/askquestion';
$route['book-appoinment'] = 'customer/book_appointment';
$route['customer-dashboard'] = 'customer/customer_dashboard';
$route['download/(:any)'] = "/hospital/download/$1";
$route['delete_record/(:any)'] = "/Customer/delete_record/$1";
$route['view-detail/(:any)'] = 'customer/view_Packages_detail/$1';





// corporate

$route['corporate'] = 'corporate';
$route['filter-result'] = 'corporate/filter_data';
$route['corporate-dashboard'] = 'Corporate/corporate_dashboard';
$route['branch-detail'] = 'Corporate/corporate_branch';
$route['product/(:any)'] = 'Corporate/corporate_service/$2';
$route['reports/(:any)'] = 'Corporate/reports/$2';  
$route['misreport/(:any)'] = 'Corporate/misreport/$2';




$route['error'] = $route['default_controller'];
$route['checkout'] = 'Checkout/index';
$route['service-network-partners/(:any)'] = 'network_partners/service_network_hospitals/$1';
$route['service-network-partners/(:any)/(:any)'] = 'network_partners/service_network_hospitals/$1/$2';

//$route['search/(:any)'] = 'network_partners/search/$1';
//$route['search/(:any)/(:any)'] = 'network_partners/search/$1/$2';


//$route['network-partners/(:any)'] = 'network_partners/hospital_details/$1';

$route['(:any)'] = 'packages/product_detail/$1';


$route['translate_uri_dashes'] = FALSE;




