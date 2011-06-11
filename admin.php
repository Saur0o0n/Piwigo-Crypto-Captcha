<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

load_language('plugin.lang', CRYPTO_PATH);
$conf['cryptographp_theme'] = explode(',', $conf['cryptographp_theme']);

if ( isset($_POST['submit']))
{
  $conf['cryptographp_theme'] = array(
    $_POST['cryptographp_theme'],
    $_POST['comments_action'],
    );
  conf_update_param('cryptographp_theme', implode(',', $conf['cryptographp_theme']));
  array_push($page['infos'], l10n('Information data registered in database'));
}

$template->set_filename('plugin_admin_content', dirname(__FILE__).'/admin.tpl');

$template->assign(array(
  'cryptographp_theme' => $conf['cryptographp_theme'][0],
  'comments_action' => $conf['cryptographp_theme'][1],
  'available_themes' => array('cryptographp', 'bluenoise', 'gray', 'pencil', 'xcolor'),
  'CRYPTO_PATH' => CRYPTO_PATH,
  )
);

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');

?>