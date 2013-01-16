<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $template;

if (isset($conf['cryptographp_button_color']))
{
  $conf['cryptographp']['button_color'] = $conf['cryptographp_button_color'];
}

load_language('plugin.lang', CRYPTO_PATH);

$template->assign(array(
  'CRYPTO' => $conf['cryptographp'],
  'CRYPTO_PATH' => get_absolute_root_url().CRYPTO_PATH,
  ));
  
$template->set_filename('cryptographp', realpath(CRYPTO_PATH.'template/'.$conf['cryptographp']['template'].'.tpl'));
$template->assign_var_from_handle('CRYPTOGRAPHP', 'cryptographp');

?>