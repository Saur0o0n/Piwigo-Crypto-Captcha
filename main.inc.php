<?php
/*
Plugin Name: CryptograPHP
Version: auto
Description: Add a CryptograPHP captcha to register and ContactForm pages (thanks to P@t)
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
define('CRYPTO_PATH' , PHPWG_PLUGINS_PATH . basename(dirname(__FILE__)) . '/');

global $conf;

add_event_handler('init', 'crypto_init');

function crypto_init()
{
  if (script_basename() == 'register') 
    include('register.inc.php');
  elseif (isset($_GET['/contact'])) 
    include('contactform.inc.php');
  else if (script_basename() == 'admin')
    add_event_handler('get_admin_plugin_menu_links', 'crypto_plugin_admin_menu' );
}

function crypto_plugin_admin_menu($menu)
{
	global $page,$conf;

	array_push($menu,
			array(
				'NAME' => 'CryptograPHP',
				'URL' => get_root_url().'admin.php?page=plugin-'.basename(dirname(__FILE__))
			)
		);
	return $menu;
}

?>