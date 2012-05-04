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
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'staticpages';
$route['404_override'] = '';


$route['signup'] =      'auth/register';
$route['login'] =       'auth/login';
$route['logout'] =      'auth/logout';
$route['help'] =        'staticpages/help';
$route['account'] =     'auth/account';





$route['tt/(:any)/activities'] =                    'timetracker/activities/$1';
$route['tt/(:any)/activity/(:num)'] =               'timetracker/activity/$1/$2';
$route['tt/(:any)/activity/(:num)/edit'] =          'timetracker/activity_edit/$1/$2';
$route['tt/(:any)/activity/(:num)/stop'] =          'timetracker/stop/$1/$2';
$route['tt/(:any)/summary/activity/(:num)'] =       'timetracker/summary/activity/$1/$2';
$route['tt/(:any)/stats/activity/(:num)'] =         'timetracker/stats/activity/$1/$2';

$route['tt/(:any)/categories'] =                    'timetracker/categories/$1';
$route['tt/(:any)/categorie/(:num)'] =              'timetracker/categorie/$1/$2';
$route['tt/(:any)/categorie/(:num)/edit'] =         'timetracker/categorie_edit/$1/$2';
$route['tt/(:any)/summary/categorie/(:num)'] =      'timetracker/summary/categorie/$1/$2';
$route['tt/(:any)/stats/categorie/(:num)'] =        'timetracker/stats/categorie/$1/$2';

$route['tt/(:any)/tags'] =                          'timetracker/tags/$1';
$route['tt/(:any)/tag/(:num)'] =                    'timetracker/tag/$1/$2';
$route['tt/(:any)/tag/(:num)/edit'] =               'timetracker/tag_edit/$1/$2';
$route['tt/(:any)/summary/tag/(:num)'] =            'timetracker/summary/tag/$1/$2';
$route['tt/(:any)/stats/tag/(:num)'] =              'timetracker/stats/tag/$1/$2';

$route['tt/(:any)/values'] =                        'timetracker/values/$1';
$route['tt/(:any)/value/(:num)'] =                  'timetracker/value/$1/$2';
$route['tt/(:any)/value/(:num)/edit'] =             'timetracker/value_edit/$1/$2';
$route['tt/(:any)/summary/value/(:num)'] =          'timetracker/summary/value/$1/$2';
$route['tt/(:any)/stats/value/(:num)'] =            'timetracker/stats/value/$1/$2';

$route['tt'] =                  'timetracker/index';
$route['tt/(:any)'] =           'timetracker/index/$1';
$route['tt/(:any)/summary'] =   'timetracker/summary/$1';
$route['tt/(:any)/stats'] =     'timetracker/stats/$1';
$route['tt/(:any)/export'] =    'timetracker/export/$1';
$route['tt/(:any)/params'] =    'timetracker/params/$1';

$route['tt/(:any)/add'] =       'timetracker/add/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
