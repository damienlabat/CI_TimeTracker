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

//---

$regx=array(
    'export_format'     =>      '(json|csv|txt)',
    'username'     		=>      '([^\/]+)',
    'objfile'           =>      '(categorie|activity|record|todo|value|tag|comment)'
    );

//---
$route['tt/'.$regx['username']]                 =     'timetracker/home/$1';

$route['tt/'.$regx['username'].'/settings']     =     'timetracker/settings/$1';

//---

$route['tt/'.$regx['username'].'/activities']                           =       'timetracker/activities/$1';
$route['tt/'.$regx['username'].'/activities/summary']                   =       'timetracker_viz/summary/$1';
$route['tt/'.$regx['username'].'/activities/graph']                     =       'timetracker_viz/graph/$1';
$route['tt/'.$regx['username'].'/activities.'.$regx['export_format']]   =       'timetracker/activities_export/$1/$2';

$route['tt/'.$regx['username'].'/todolist']                             =       'timetracker/todolist/$1';
$route['tt/'.$regx['username'].'/todolist.'.$regx['export_format']]     =       'timetracker/todolist_export/$1/$2';

$route['tt/'.$regx['username'].'/values']                               =       'timetracker/values/$1';
$route['tt/'.$regx['username'].'/values/graph']                         =       'timetracker/values_graph/$1';
$route['tt/'.$regx['username'].'/values.'.$regx['export_format']]       =       'timetracker/values_export/$1/$2';

//---

$route['tt/'.$regx['username'].'/activity/new']                         =       'timetracker/generic_activity_new/$1/activity';
$route['tt/'.$regx['username'].'/todo/new']                             =       'timetracker/generic_activity_new/$1/todo';
$route['tt/'.$regx['username'].'/value/new']                            =       'timetracker/generic_activity_new/$1/value';

//---

$route['tt/'.$regx['username'].'/'.$regx['objfile'].'_(:num)']          =       'timetracker/generic_activity_show/$1/$2/$3';
$route['tt/'.$regx['username'].'/'.$regx['objfile'].'_(:num)/edit']     =       'timetracker/generic_activity_edit/$1/$2/$3';
$route['tt/'.$regx['username'].'/'.$regx['objfile'].'_(:num)/delete']   =       'timetracker/generic_activity_delete/$1/$2/$3';


//---

$route['tt/'.$regx['username'].'/record_(:num)/stop']                   =       'timetracker/stop/$1/$2';

$route['tt/'.$regx['username'].'/record_(:num)/restart']                =       'timetracker/restart/$1/$2';
$route['tt/'.$regx['username'].'/records/stopall']                      =       'timetracker/stop_all/$1/activity';

//---

$route['json/'.$regx['username'].'/histo/(:any)/(:any)/(:any)/(:any).json']                   =       'timetracker_viz/json_activities_graph/$1/$2/$3/$4/$5';

/*
TODO

/tt/{username}/(categorie|record|todo|value)/addcomment

/friends
/friends/invite
/friends/sharedrequests
/friends/sharedrequest/{id}
/friend/{username}

/messages/
/messages/send
/message/{id}
/message/{id}/delete

tt/{username}/categorie_{id}/share


*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */
