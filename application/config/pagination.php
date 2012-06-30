<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['use_page_numbers'] = TRUE;
$config['page_query_string']= TRUE;
$config['query_string_segment'] = 'page';

$config['per_page']         = 10;

$config['num_tag_open']     = '<li>';
$config['num_tag_close']    = '</li>';

$config['cur_tag_open']     = '<li class="active"><a href="#">';
$config['cur_tag_close']    = '</a></li>';

$config['first_tag_open']   = '<li>';
$config['first_tag_close']  = '</li>';
$config['first_link']       = 'First';

$config['last_tag_open']    = '<li>';
$config['last_tag_close']   = '</li>';
$config['last_link']        = 'Last';

$config['next_tag_open']    = '<li>';
$config['next_tag_close']   = '</li>';
$config['prev_tag_open']    = '<li>';
$config['prev_tag_close']   = '</li>';