<?php
/*
Plugin Name: Crypto Captcha
Version: auto
Description: Add a CryptograPHP captcha to register, comment and ContactForm pages (thanks to P@t)
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=535
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
define('CRYPTO_PATH' , PHPWG_PLUGINS_PATH . basename(dirname(__FILE__)) . '/');

add_event_handler('loc_end_section_init', 'crypto_init');

function crypto_init()
{
  global $conf, $pwg_loaded_plugins, $page;
  $conf['cryptographp_theme'] = explode(',', $conf['cryptographp_theme']);
  
  if (script_basename() == 'register')
  {
    include(CRYPTO_PATH.'include/register.inc.php');
  }
  else if (script_basename() == 'picture' AND $conf['cryptographp_theme'][1] != 'inactive')
  {
    include(CRYPTO_PATH.'include/picture.inc.php');
  }
  else if (
    script_basename() == 'index' AND $conf['cryptographp_theme'][1] != 'inactive' AND 
    isset($pwg_loaded_plugins['Comments_on_Albums']) AND 
    $page['section'] == 'categories' AND isset($page['category'])
  ) {
    include(CRYPTO_PATH.'include/category.inc.php');
  }
  else if (isset($_GET['/contact'])) 
  {
    include(CRYPTO_PATH.'include/contactform.inc.php');
  }
}

if (script_basename() == 'admin')
{
  add_event_handler('get_admin_plugin_menu_links', 'crypto_plugin_admin_menu');

  function crypto_plugin_admin_menu($menu)
  {
    global $page,$conf;

    array_push(
      $menu,
      array(
        'NAME' => 'CryptograPHP',
        'URL' => get_root_url().'admin.php?page=plugin-'.basename(dirname(__FILE__))
        )
      );
    return $menu;
  }
}

?>