<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Facebook App details
| -------------------------------------------------------------------
|
| To get an facebook app details you have to be a registered developer
| at http://developer.facebook.com and create an app for your project.
|
|  facebook_app_id               string  Your facebook app ID.
|  facebook_app_secret           string  Your facebook app secret.
|  facebook_login_type           string  Set login type. (web, js, canvas)
|  facebook_login_redirect_url   string  URL tor redirect back to after login. Do not include domain.
|  facebook_logout_redirect_url  string  URL tor redirect back to after login. Do not include domain.
|  facebook_permissions          array   The permissions you need.
*/

// $config['facebook_app_id']              = '274031353062483'; //localhost facebook login app_id
// $config['facebook_app_secret']          = 'b28ceb4c64bf6b54fec6af604bdb1902'; //localhost facebook login 
$config['facebook_app_id']              = '135977850224986';
$config['facebook_app_secret']          = 'eaea450a73ad0ab4e3873cd98ae78b4e';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'authentication/social_media_resister';
$config['facebook_logout_redirect_url'] = 'authentication/social_media_resister';
$config['facebook_permissions']         = array('email,user_birthday,user_location,public_profile');
