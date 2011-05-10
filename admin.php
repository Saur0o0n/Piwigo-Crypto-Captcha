<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

load_language('plugin.lang', CRYPTO_PATH);

if ( isset($_POST['submit']))
{
  $conf['cryptographp_theme'] = trim($_POST['cryptographp_theme']);

  $query = '
UPDATE '.CONFIG_TABLE.'
  SET value="'.$conf['cryptographp_theme'].'"
  WHERE param="cryptographp_theme"
  LIMIT 1';
  pwg_query($query);

  array_push($page['infos'], l10n('Information data registered in database'));
}

$template->set_filename('plugin_admin_content', dirname(__FILE__).'/admin.tpl');

$template->assign(array(
  'cryptographp_theme' => $conf['cryptographp_theme'],
  'available_themes' => array('cryptographp', 'bluenoise', 'gray', 'pencil', 'xcolor'),
  'CRYPTO_PATH' => CRYPTO_PATH,
  )
);

$template->assign_var_from_handle( 'ADMIN_CONTENT', 'plugin_admin_content');

?>