<?php
/*
Plugin Name: Crypto Captcha
Version: auto
Description: Add a captcha to register, comment and ContactForm pages (thanks to P@t)
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=535
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

## TODO : add customization of background image

/*
Author note :
Le plugin était appellé à l'origine CryptograPHP et utilisait la librairie CryptograPHP
Puis il a été renommé Crypto Captcha pour plus de clareté
La version actuelle s'appelle toujours Crypto Captcha mais utilise la librairie Securimage
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
define('CRYPTO_PATH' , PHPWG_PLUGINS_PATH . basename(dirname(__FILE__)) . '/');

add_event_handler('init', 'crypto_init');
add_event_handler('loc_end_section_init', 'crypto_section_init', EVENT_HANDLER_PRIORITY_NEUTRAL+30);

function crypto_init()
{
  global $conf, $user;
  
  $conf['cryptographp'] = unserialize($conf['cryptographp']);
  
  if (!is_a_guest()) return;
  if ($user['theme'] == 'smartpocket') return;
  
  if (script_basename() == 'register' and $conf['cryptographp']['activate_on']['register'])
  {
    $conf['cryptographp']['template'] = 'register';
    include(CRYPTO_PATH.'include/register.inc.php');
  }
  else if (script_basename() == 'picture' and $conf['cryptographp']['activate_on']['picture'])
  {
    $conf['cryptographp']['template'] = 'comment';
    include(CRYPTO_PATH.'include/picture.inc.php');
  }
  
}

function crypto_section_init()
{
  global $conf, $pwg_loaded_plugins, $page, $user;
  
  if (!is_a_guest()) return;
  if ($user['theme'] == 'smartpocket') return;
  
  if (
    script_basename() == 'index' and $conf['cryptographp']['activate_on']['category'] and 
    isset($pwg_loaded_plugins['Comments_on_Albums']) and isset($page['section']) and 
    $page['section'] == 'categories' and isset($page['category'])
    ) 
  {
    $conf['cryptographp']['template'] = 'comment';
    include(CRYPTO_PATH.'include/category.inc.php');
  }
  else if ( preg_match('#contact/?$#', $_SERVER['REQUEST_URI']) and $conf['cryptographp']['activate_on']['contactform']) 
  {
    $conf['cryptographp']['template'] = 'contactform';
    include(CRYPTO_PATH.'include/contactform.inc.php');
  }
  else if (isset($page['section']) and $page['section'] == 'guestbook' and $conf['cryptographp']['activate_on']['guestbook']) 
  {
    $conf['cryptographp']['template'] = 'comment';
    include(CRYPTO_PATH.'include/guestbook.inc.php');
  }
}

if (script_basename() == 'admin')
{
  add_event_handler('get_admin_plugin_menu_links', 'crypto_plugin_admin_menu');

  function crypto_plugin_admin_menu($menu)
  {
    array_push($menu, array(
      'NAME' => 'Crypto Captcha',
      'URL' => get_root_url().'admin.php?page=plugin-'.basename(dirname(__FILE__))
      ));
    return $menu;
  }
}

?>