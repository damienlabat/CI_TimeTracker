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
$route['tt/([^\/]+)/activities'] =                    'timetracker/activities/$1';
$route['tt/([^\/]+)/activity'] =                      'timetracker/activity/$1';
$route['tt/([^\/]+)/activity/(:num)'] =               'timetracker/activity/$1/$2';
$route['tt/([^\/]+)/activity/(:num)/(:num)'] =        'timetracker/activity/$1/$2/$3'; // page
$route['tt/([^\/]+)/activity/(:num)/edit'] =          'timetracker/activity_edit/$1/$2';

$route['tt/([^\/]+)/thingstodo'] =                    'timetracker/thingstodo/$1';
$route['tt/([^\/]+)/todo'] =                          'timetracker/todo/$1';
$route['tt/([^\/]+)/todo/(:num)'] =                   'timetracker/todo/$1/$2';
$route['tt/([^\/]+)/todo/(:num)/(:num)'] =            'timetracker/todo/$1/$2/$3'; // page
$route['tt/([^\/]+)/todo/(:num)/edit'] =              'timetracker/todo_edit/$1/$2';

$route['tt/([^\/]+)/values'] =                        'timetracker/values/$1';
$route['tt/([^\/]+)/value'] =                         'timetracker/value/$1';
$route['tt/([^\/]+)/value/(:num)'] =                  'timetracker/value/$1/$2';
$route['tt/([^\/]+)/value/(:num)/(:num)'] =           'timetracker/value/$1/$2/$3'; // page
$route['tt/([^\/]+)/value/(:num)/edit'] =             'timetracker/value_edit/$1/$2';

$route['tt/([^\/]+)/categories'] =                    'timetracker/categories/$1';
$route['tt/([^\/]+)/categorie'] =                     'timetracker/categorie/$1';
$route['tt/([^\/]+)/categorie/(:num)'] =              'timetracker/categorie/$1/$2';
$route['tt/([^\/]+)/categorie/(:num)/(:num)'] =       'timetracker/categorie/$1/$2/$3'; // page
$route['tt/([^\/]+)/categorie/(:num)/edit'] =         'timetracker/categorie_edit/$1/$2';

$route['tt/([^\/]+)/tags'] =                          'timetracker/tags/$1';
$route['tt/([^\/]+)/tag'] =                           'timetracker/tag/$1';
$route['tt/([^\/]+)/tag/(:num)'] =                    'timetracker/tag/$1/$2';
$route['tt/([^\/]+)/tag/(:num)/(:num)'] =             'timetracker/tag/$1/$2/$3'; // page
$route['tt/([^\/]+)/tag/(:num)/edit'] =               'timetracker/tag_edit/$1/$2';


$route['tt/([^\/]+)/valuetypes'] =                     'timetracker/valuetypes/$1';
$route['tt/([^\/]+)/valuetype'] =                      'timetracker/valuetype/$1';
$route['tt/([^\/]+)/valuetype/(:num)'] =               'timetracker/valuetype/$1/$2';
$route['tt/([^\/]+)/valuetype/(:num)/(:num)'] =        'timetracker/valuetype/$1/$2/$3'; // page
$route['tt/([^\/]+)/valuetype/(:num)/edit'] =          'timetracker/valuetype_edit/$1/$2';


$route['tt/([^\/]+)/record/(:num)'] =                   'timetracker/record/$1/$2';
$route['tt/([^\/]+)/record/(:num)/edit'] =              'timetracker/edit_record/$1/$2';
$route['tt/([^\/]+)/record/(:num)/delete'] =            'timetracker/delete_record/$1/$2';
$route['tt/([^\/]+)/record/(:num)/restart'] =           'timetracker/restart/$1/$2';
$route['tt/([^\/]+)/record/(:num)/stop'] =              'timetracker/stop/$1/$2';


$route['tt/([^\/]+)/summary'] =                             'timetracker_viz/summary/$1';
$route['tt/([^\/]+)/summary/([^\/]+)'] =                    'timetracker_viz/summary/$1/$2';
$route['tt/([^\/]+)/summary/([^\/]+)/([^\/]+)'] =           'timetracker_viz/summary/$1/$2/$3';
$route['tt/([^\/]+)/summary/([^\/]+)/([^\/]+)/([^\/]+)'] =  'timetracker_viz/summary/$1/$2/$3/$4';

$route['tt/([^\/]+)/graph'] =                                   'timetracker_viz/graph/$1';
$route['tt/([^\/]+)/graph/([^\/]+)'] =                          'timetracker_viz/graph/$1/$2';
$route['tt/([^\/]+)/graph/([^\/]+)/([^\/]+)'] =                 'timetracker_viz/graph/$1/$2/$3';
$route['tt/([^\/]+)/graph/([^\/]+)/([^\/]+)/([^\/]+)'] =        'timetracker_viz/graph/$1/$2/$3/$4';
$route['tt/([^\/]+)/graph/([^\/]+)/([^\/]+)/([^\/]+)/([^\/]+)'] = 'timetracker_viz/graph/$1/$2/$3/$4/$5';

$route['tt/([^\/]+)/export'] =                                      'timetracker_viz/export/$1';
$route['tt/([^\/]+)/export/([^\/]+)'] =                             'timetracker_viz/export/$1/$2';
$route['tt/([^\/]+)/export/([^\/]+)/([^\/]+)'] =                    'timetracker_viz/export/$1/$2/$3';
$route['tt/([^\/]+)/export/([^\/]+)/([^\/]+)/([^\/]+)'] =           'timetracker_viz/export/$1/$2/$3/$4';
$route['tt/([^\/]+)/export/([^\/]+)/([^\/]+)/([^\/]+)/([^\/]+)'] =  'timetracker_viz/export/$1/$2/$3/$4/$5';

$route['tt/([^\/]+)/log'] =                             'timetracker_viz/log/$1';
$route['tt/([^\/]+)/log/([^\/]+)'] =                    'timetracker_viz/log/$1/$2';
$route['tt/([^\/]+)/log/([^\/]+)/([^\/]+)'] =           'timetracker_viz/log/$1/$2/$3';
$route['tt/([^\/]+)/log/([^\/]+)/([^\/]+)/([^\/]+)'] =  'timetracker_viz/log/$1/$2/$3/$4';

$route['tt/([^\/]+)/params'] =       'timetracker/params/$1';

$route['tt/([^\/]+)'] =              'timetracker/index/$1';
$route['tt/([^\/]+)/(:num)'] =       'timetracker/index/$1/$2'; // page
$route['tt'] =                       'timetracker/index';



/* End of file routes.php */
/* Location: ./application/config/routes.php */
