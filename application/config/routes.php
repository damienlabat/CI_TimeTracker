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

/*
 TODO! user params and shared categories
 */


//$1 username
$route['tt/(:any)/activities'] =                    'timetracker/activities/$1';
$route['tt/(:any)/activity'] =                      'timetracker/activity/$1';
$route['tt/(:any)/activity/(:num)'] =               'timetracker/activity/$1/$2';
$route['tt/(:any)/activity/(:num)/(:num)'] =        'timetracker/activity/$1/$2/$3'; // page
$route['tt/(:any)/activity/(:num)/edit'] =          'timetracker/activity_edit/$1/$2';
$route['tt/(:any)/summary/activity/(:num)'] =       'timetracker/summary/$1/activity/$2';
$route['tt/(:any)/stats/activity/(:num)'] =         'timetracker/stats/$1/activity/$2';

$route['tt/(:any)/thingstodo'] =                    'timetracker/thingstodo/$1';
$route['tt/(:any)/todo'] =                          'timetracker/todo/$1';
$route['tt/(:any)/todo/(:num)'] =                   'timetracker/todo/$1/$2';
$route['tt/(:any)/todo/(:num)/(:num)'] =            'timetracker/todo/$1/$2/$3'; // page
$route['tt/(:any)/todo/(:num)/edit'] =              'timetracker/todo_edit/$1/$2';
$route['tt/(:any)/summary/todo/(:num)'] =           'timetracker/summary/$1/todo/$2';
$route['tt/(:any)/stats/todo/(:num)'] =             'timetracker/stats/$1/todo/$2';

$route['tt/(:any)/values'] =                        'timetracker/values/$1';
$route['tt/(:any)/value'] =                         'timetracker/value/$1';
$route['tt/(:any)/value/(:num)'] =                  'timetracker/value/$1/$2';
$route['tt/(:any)/value/(:num)/(:num)'] =           'timetracker/value/$1/$2/$3'; // page
$route['tt/(:any)/value/(:num)/edit'] =             'timetracker/value_edit/$1/$2';
$route['tt/(:any)/summary/value/(:num)'] =          'timetracker/summary/$1/value/$2';
$route['tt/(:any)/stats/value/(:num)'] =            'timetracker/stats/$1/value/$2';

$route['tt/(:any)/categories'] =                    'timetracker/categories/$1';
$route['tt/(:any)/categorie'] =                     'timetracker/categorie/$1';
$route['tt/(:any)/categorie/(:num)'] =              'timetracker/categorie/$1/$2';
$route['tt/(:any)/categorie/(:num)/(:num)'] =       'timetracker/categorie/$1/$2/$3'; // page
$route['tt/(:any)/categorie/(:num)/edit'] =         'timetracker/categorie_edit/$1/$2';
$route['tt/(:any)/summary/categorie/(:num)'] =      'timetracker/summary/$1/categorie/$2';
$route['tt/(:any)/stats/categorie/(:num)'] =        'timetracker/stats/$1/categorie/$2';

$route['tt/(:any)/tags'] =                          'timetracker/tags/$1';
$route['tt/(:any)/tag'] =                           'timetracker/tag/$1';
$route['tt/(:any)/tag/(:num)'] =                    'timetracker/tag/$1/$2';
$route['tt/(:any)/tag/(:num)/(:num)'] =             'timetracker/tag/$1/$2/$3'; // page
$route['tt/(:any)/tag/(:num)/edit'] =               'timetracker/tag_edit/$1/$2';
$route['tt/(:any)/summary/tag/(:num)'] =            'timetracker/summary/$1/tag/$2';
$route['tt/(:any)/stats/tag/(:num)'] =              'timetracker/stats/$1/tag/$2';


$route['tt/(:any)/valuetypes'] =                     'timetracker/valuetypes/$1';
$route['tt/(:any)/valuetype'] =                      'timetracker/valuetype/$1';
$route['tt/(:any)/valuetype/(:num)'] =               'timetracker/valuetype/$1/$2';
$route['tt/(:any)/valuetype/(:num)/(:num)'] =        'timetracker/valuetype/$1/$2/$3'; // page
$route['tt/(:any)/valuetype/(:num)/edit'] =          'timetracker/valuetype_edit/$1/$2';
$route['tt/(:any)/summary/valuetype/(:num)'] =       'timetracker/summary/$1/valuetype/$2';
$route['tt/(:any)/stats/valuetype/(:num)'] =         'timetracker/stats/$1/valuetype/$2';


$route['tt/(:any)/record/(:num)'] =                   'timetracker/record/$1/$2';
$route['tt/(:any)/record/(:num)/edit'] =              'timetracker/edit_record/$1/$2';
$route['tt/(:any)/record/(:num)/delete'] =            'timetracker/delete_record/$1/$2';
$route['tt/(:any)/record/(:num)/restart'] =           'timetracker/restart/$1/$2';
$route['tt/(:any)/record/(:num)/stop'] =              'timetracker/stop/$1/$2';



$route['tt/(:any)/summary'] =        'timetracker/summary/$1';
$route['tt/(:any)/stats'] =          'timetracker/stats/$1';
$route['tt/(:any)/export'] =         'timetracker/export/$1';
$route['tt/(:any)/log'] =            'timetracker/log/$1';
$route['tt/(:any)/params'] =         'timetracker/params/$1';
$route['tt/(:any)'] =                'timetracker/index/$1';
$route['tt'] =                       'timetracker/index';



/* End of file routes.php */
/* Location: ./application/config/routes.php */
