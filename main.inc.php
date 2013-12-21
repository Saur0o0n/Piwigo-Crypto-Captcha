<?php
/*
Plugin Name: Crypto Captcha
Version: auto
Description: Add a captcha to register, comment, GuestBook and ContactForm pages (thanks to P@t)
Plugin URI: auto
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

define('CRYPTO_ID',       basename(dirname(__FILE__)));
define('CRYPTO_PATH' ,    PHPWG_PLUGINS_PATH . CRYPTO_ID . '/');
define('CRYPTO_ADMIN',    get_root_url() . 'admin.php?page=plugin-' . CRYPTO_ID);
define('CRYPTO_VERSION',  'auto');


add_event_handler('init', 'crypto_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'crypto_plugin_admin_menu');
}
else
{
  add_event_handler('loc_end_section_init', 'crypto_document_init', EVENT_HANDLER_PRIORITY_NEUTRAL+30);
}


// plugin init
function crypto_init()
{
  global $conf;
  
  include_once(CRYPTO_PATH . 'maintain.inc.php');
  $maintain = new CryptograPHP_maintain(CRYPTO_ID);
  $maintain->autoUpdate(CRYPTO_VERSION, 'install');
  
  load_language('plugin.lang', CRYPTO_PATH);
  $conf['cryptographp'] = unserialize($conf['cryptographp']);
}


// modules
function crypto_document_init()
{
  global $conf, $user, $page;
  
  if (!is_a_guest())
  {
    return;
  }
  
  if (script_basename() == 'register' and $conf['cryptographp']['activate_on']['register'])
  {
    $conf['cryptographp']['template'] = 'register';
    include(CRYPTO_PATH . 'include/register.inc.php');
  }
  else if (script_basename() == 'picture' and $conf['cryptographp']['activate_on']['picture'])
  {
    $conf['cryptographp']['template'] = 'comment';
    include(CRYPTO_PATH . 'include/picture.inc.php');
  }
  else if (isset($page['section']))
  {
    if (
      script_basename() == 'index' &&
      $page['section'] == 'categories' && isset($page['category']) &&
      isset($pwg_loaded_plugins['Comments_on_Albums']) &&
      $conf['cryptographp']['activate_on']['category']
      )
    {
      $conf['cryptographp']['template'] = 'comment';
      include(CRYPTO_PATH . 'include/category.inc.php');
    }
    else if ($page['section'] == 'contact' && $conf['cryptographp']['activate_on']['contactform'])
    {
      $conf['cryptographp']['template'] = 'contactform';
      include(CRYPTO_PATH . 'include/contactform.inc.php');
    }
    else if ($page['section'] == 'guestbook' && $conf['cryptographp']['activate_on']['guestbook'])
    {
      $conf['cryptographp']['template'] = 'guestbook';
      include(CRYPTO_PATH . 'include/guestbook.inc.php');
    }
  }
}


// admin
function crypto_plugin_admin_menu($menu)
{
  $menu[] = array(
    'NAME' => 'Crypto Captcha',
    'URL' => CRYPTO_ADMIN,
    );
  return $menu;
}