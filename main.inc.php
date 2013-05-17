<?php
/*
Plugin Name: Crypto Captcha
Version: auto
Description: Add a captcha to register, comment, GuestBook and ContactForm pages (thanks to P@t)
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=535
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

/*
Author note :
Le plugin était appellé à l'origine CryptograPHP et utilisait la librairie CryptograPHP
Puis il a été renommé Crypto Captcha pour plus de clareté
La version actuelle s'appelle toujours Crypto Captcha mais utilise la librairie Securimage
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

if (mobile_theme())
{
  return;
}

defined('CRYPTO_ID') or define('CRYPTO_ID', basename(dirname(__FILE__)));
define('CRYPTO_PATH' , PHPWG_PLUGINS_PATH . CRYPTO_ID . '/');
define('CRYPTO_ADMIN', get_root_url() . 'admin.php?page=plugin-' . CRYPTO_ID);
define('CRYPTO_VERSION', 'auto');


add_event_handler('init', 'crypto_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'crypto_plugin_admin_menu');
}
else
{
  add_event_handler('init', 'crypto_document_init');
  add_event_handler('loc_end_section_init', 'crypto_section_init', EVENT_HANDLER_PRIORITY_NEUTRAL+30);
}


// plugin init
function crypto_init()
{
  global $conf, $pwg_loaded_plugins;
  
  if (
    CRYPTO_VERSION == 'auto' or
    $pwg_loaded_plugins[CRYPTO_ID]['version'] == 'auto' or
    version_compare($pwg_loaded_plugins[CRYPTO_ID]['version'], CRYPTO_VERSION, '<')
  )
  {
    include_once(CRYPTO_PATH . 'include/install.inc.php');
    crypto_install();
    
    if ( $pwg_loaded_plugins[CRYPTO_ID]['version'] != 'auto' and CRYPTO_VERSION != 'auto' )
    {
      $query = '
UPDATE '. PLUGINS_TABLE .'
SET version = "'. CRYPTO_VERSION .'"
WHERE id = "'. CRYPTO_ID .'"';
      pwg_query($query);
      
      $pwg_loaded_plugins[CRYPTO_ID]['version'] = CRYPTO_VERSION;
      
      if (defined('IN_ADMIN'))
      {
        $_SESSION['page_infos'][] = 'Crypto Captcha updated to version '. CRYPTO_VERSION;
      }
    }
  }
  
  $conf['cryptographp'] = unserialize($conf['cryptographp']);
}


// modules : picture comment & register
function crypto_document_init()
{
  global $conf, $user;
  
  if (!is_a_guest()) return;
  
  if ( script_basename() == 'register' and $conf['cryptographp']['activate_on']['register'] )
  {
    $conf['cryptographp']['template'] = 'register';
    include(CRYPTO_PATH.'include/register.inc.php');
  }
  else if ( script_basename() == 'picture' and $conf['cryptographp']['activate_on']['picture'] )
  {
    $conf['cryptographp']['template'] = 'comment';
    include(CRYPTO_PATH.'include/picture.inc.php');
  }
  
}

// modules : album comment & contact & guestbook
function crypto_section_init()
{
  global $conf, $pwg_loaded_plugins, $page, $user;
  
  if (!is_a_guest()) return;
  
  if (
    script_basename() == 'index' and $conf['cryptographp']['activate_on']['category'] and 
    isset($pwg_loaded_plugins['Comments_on_Albums']) and isset($page['section']) and 
    $page['section'] == 'categories' and isset($page['category'])
    ) 
  {
    $conf['cryptographp']['template'] = 'comment';
    include(CRYPTO_PATH.'include/category.inc.php');
  }
  else if ( isset($page['section']) and $page['section'] == 'contact' and $conf['cryptographp']['activate_on']['contactform'] ) 
  {
    $conf['cryptographp']['template'] = 'contactform';
    include(CRYPTO_PATH.'include/contactform.inc.php');
  }
  else if ( isset($page['section']) and $page['section'] == 'guestbook' and $conf['cryptographp']['activate_on']['guestbook'] ) 
  {
    $conf['cryptographp']['template'] = 'guestbook';
    include(CRYPTO_PATH.'include/guestbook.inc.php');
  }
}


// admin
function crypto_plugin_admin_menu($menu)
{
  array_push($menu, array(
    'NAME' => 'Crypto Captcha',
    'URL' => CRYPTO_ADMIN,
    ));
  return $menu;
}

?>