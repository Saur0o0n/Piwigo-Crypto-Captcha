<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

defined('CRYPTO_ID') or define('CRYPTO_ID', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . CRYPTO_ID . '/include/install.inc.php');

function plugin_install() 
{
  crypto_install();
  define('crypto_installed', true);
}

function plugin_activate()
{
  if (!defined('crypto_installed'))
  {
    crypto_install();
  }
}

function plugin_uninstall()
{
  pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="cryptographp" LIMIT 1');
}

?>