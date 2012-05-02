<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('css_url'))
{
	function css_url($nom)
	{
		return base_url() . 'assets/css/' . $nom;
	}
}

if ( ! function_exists('less_url'))
{
	function less_url($nom)
	{
		return base_url() . 'assets/less/' . $nom ;
	}
}

if ( ! function_exists('js_url'))
{
	function js_url($nom)
	{
		return base_url() . 'assets/js/' . $nom ;
	}
}

if ( ! function_exists('libs_url'))
{
	function libs_url($nom)
	{
		return base_url() . 'assets/js/libs/' . $nom ;
	}
}

if ( ! function_exists('img_url'))
{
	function img_url($nom)
	{
		return base_url() . 'assets/img/' . $nom;
	}
}

