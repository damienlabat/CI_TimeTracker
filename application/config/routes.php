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
$route['tt/'.$regx['username'].'/activities/summary']                   =       'timetracker/activities_settings/$1';
$route['tt/'.$regx['username'].'/activities/graph']                     =       'timetracker/activities_graph/$1';
$route['tt/'.$regx['username'].'/activities.'.$regx['export_format']]   =       'timetracker/activities_export/$1/$2';

$route['tt/'.$regx['username'].'/todolist']                             =       'timetracker/todolist/$1';
$route['tt/'.$regx['username'].'/todolist.'.$regx['export_format']]     =       'timetracker/todolist_export/$1/$2';

$route['tt/'.$regx['username'].'/values']                               =       'timetracker/values/$1';
$route['tt/'.$regx['username'].'/values.'.$regx['export_format']]       =       'timetracker/values_export/$1/$2';

//---

$route['tt/'.$regx['username'].'/record/new']                           =       'timetracker/record_new/$1';
$route['tt/'.$regx['username'].'/todo/new']                             =       'timetracker/todo_new/$1';
$route['tt/'.$regx['username'].'/value/new']                            =       'timetracker/value_new/$1';

//---

$route['tt/'.$regx['username'].'/'.$regx['objfile'].'_(:num)']          =       'timetracker/$2/$1/$3';
$route['tt/'.$regx['username'].'/'.$regx['objfile'].'_(:num)/edit']     =       'timetracker/$2_edit/$1/$3';
$route['tt/'.$regx['username'].'/'.$regx['objfile'].'_(:num)/delete']   =       'timetracker/$2_delete/$1/$3';

//---

$route['tt/'.$regx['username'].'/record_(:num)/stop']                   =       'timetracker/record_stop/$1/$2';
$route['tt/'.$regx['username'].'/todo_(:num)/done']                     =       'timetracker/todo_done/$1/$2';

$route['tt/'.$regx['username'].'/record_(:num)/restart']                =       'timetracker/record_restart/$1/$2';
$route['tt/'.$regx['username'].'/records/stopall']                      =       'timetracker/records_stopall/$1';

//---

$route['json/'.$regx['username'].'/activities/graph']                   =       'timetracker/json_activities_graph/$1';
$route['json/'.$regx['username'].'/activities/summary']                 =       'timetracker/json_activities_summary/$1';

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
