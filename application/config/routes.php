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


$route['tt/([^\/]+)/(activity|todo|value)/(:num)/edit'] =                       'timetracker/generic_activity_edit/$1/$2/$3';
$route['tt/([^\/]+)/(activity|todo|value)/(:num)/(:num)'] =     'timetracker/generic_activity_show/$1/$2/$3/$4'; // $4=page
$route['tt/([^\/]+)/(activity|todo|value)/(:num)'] =            'timetracker/generic_activity_show/$1/$2/$3';

$route['tt/([^\/]+)/(categories|tags|valuetypes)'] =            'timetracker/$2/$1';
$route['tt/([^\/]+)/(categorie|tag|valuetype)/(:num)'] =        'timetracker/$2/$1/$3';
$route['tt/([^\/]+)/(categorie|tag|valuetype)/(:num)/(:num)'] = 'timetracker/$2/$1/$3/$4'; // $4=page
$route['tt/([^\/]+)/(categorie|tag|valuetype)/(:num)/edit'] =   'timetracker/$2_edit/$1/$3';



$route['tt/([^\/]+)/record/(:num)'] =                           'timetracker/record/$1/$2';
$route['tt/([^\/]+)/record/(:num)/edit'] =                      'timetracker/edit_record/$1/$2';
$route['tt/([^\/]+)/record/(:num)/delete'] =                    'timetracker/delete_record/$1/$2';
$route['tt/([^\/]+)/record/(:num)/restart'] =                   'timetracker/restart/$1/$2';
$route['tt/([^\/]+)/record/(:num)/stop'] =                      'timetracker/stop/$1/$2';





$route['tt/([^\/]+)/(summary|graph|export|log)'] =                              'timetracker_viz/$2/$1';
$route['tt/([^\/]+)/(summary|graph|export|log)/([^\/]+)'] =                     'timetracker_viz/$2/$1/$3';
$route['tt/([^\/]+)/(summary|graph|export|log)/([^\/]+)/([^\/]+)'] =            'timetracker_viz/$2/$1/$3/$4';    // $4=id
$route['tt/([^\/]+)/(summary|graph|export|log)/([^\/]+)/([^\/]+)/([^\/]+)'] =   'timetracker_viz/$2/$1/$3/$4/$5'; // $5=plage_date

$route['tt/([^\/]+)/graph/([^\/]+)/([^\/]+)/([^\/]+)/([^\/]+)'] =               'timetracker_viz/graph/$1/$2/$3/$4/$5';  //$5=graph_id
$route['tt/([^\/]+)/export/([^\/]+)/([^\/]+)/([^\/]+)/([^\/]+)'] =              'timetracker_viz/export/$1/$2/$3/$4/$5';  //$5=format

$route['tt/([^\/]+)/histo/([^\/]+)/([^\/]+)/([^\/]+)/(minute|hour|day|week).json'] =                   'timetracker_viz/histo_json/$1/$2/$3/$4/$5';
//ex: http://127.0.0.1/damien/CI_TimeTracker/tt/damien/histo/categorie/all/all/day.json      // user type id plagedat





$route['tt/([^\/]+)/params'] =       'timetracker/params/$1';

$route['tt/([^\/]+)'] =              'timetracker/index/$1';
$route['tt/([^\/]+)/(:num)'] =       'timetracker/index/$1/$2'; // $2=page
$route['tt'] =                       'timetracker/index';





/* End of file routes.php */
/* Location: ./application/config/routes.php */
