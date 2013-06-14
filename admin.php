<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $pwg_loaded_plugins;
$loaded = array(
  'contactform' => isset($pwg_loaded_plugins['ContactForm']),
  'category' => isset($pwg_loaded_plugins['Comments_on_Albums']),
  'guestbook' => isset($pwg_loaded_plugins['GuestBook']),
  );


load_language('plugin.lang', CRYPTO_PATH);


if ( isset($_POST['submit']))
{  
  $conf['cryptographp'] = array(
    'activate_on'     => array(
          'picture'     => in_array('picture', $_POST['activate_on']),
          'category'    => in_array('category', $_POST['activate_on']) || !$loaded['category'],
          'register'    => in_array('register', $_POST['activate_on']),
          'contactform' => in_array('contactform', $_POST['activate_on']) || !$loaded['contactform'],
          'guestbook'   => in_array('guestbook', $_POST['activate_on']) || !$loaded['guestbook'],
          ),
    'comments_action' => $_POST['comments_action'],
    'theme'           => $_POST['theme'],
    'captcha_type'    => $_POST['captcha_type'],
    'case_sensitive'  => 'false', //not used, problem with some fonts
    'width'           => (int)$_POST['width'], 
    'height'          => (int)$_POST['height'],
    'perturbation'    => (float)$_POST['perturbation'],
    'image_bg_color'  => $_POST['image_bg_color'],
    'code_length'     => (int)$_POST['code_length'],
    'text_color'      => $_POST['text_color'],
    'num_lines'       => (float)$_POST['num_lines'],
    'line_color'      => $_POST['line_color'],
    'noise_level'     => (float)$_POST['noise_level'],
    'noise_color'     => $_POST['noise_color'],
    'ttf_file'        => $_POST['ttf_file'],
    'button_color'    => $_POST['button_color'],
    );
  
  conf_update_param('cryptographp', serialize($conf['cryptographp']));
  array_push($page['infos'], l10n('Information data registered in database'));
}

$presets = array(
  'bluenoise' =>  array('perturbation'=>0.25, 'image_bg_color'=>'ffffff', 'text_color'=>'0000ff', 'num_lines'=>2, 'line_color'=>'0000ff', 'noise_level'=>2,   'noise_color'=>'0000ff', 'ttf_file'=>'AlteHassGroteskB'),
  'gray' =>       array('perturbation'=>1,    'image_bg_color'=>'ffffff', 'text_color'=>'8a8a8a', 'num_lines'=>2, 'line_color'=>'8a8a8a', 'noise_level'=>0.1, 'noise_color'=>'8a8a8a', 'ttf_file'=>'TopSecret'),
  'xcolor' =>     array('perturbation'=>0.5,  'image_bg_color'=>'ffffff', 'text_color'=>'random', 'num_lines'=>1, 'line_color'=>'ffffff', 'noise_level'=>2,   'noise_color'=>'ffffff', 'ttf_file'=>'Dread'),
  'pencil' =>     array('perturbation'=>0.8,  'image_bg_color'=>'9e9e9e', 'text_color'=>'363636', 'num_lines'=>0, 'line_color'=>'ffffff', 'noise_level'=>0,   'noise_color'=>'ffffff', 'ttf_file'=>'AllStar'),
  );
  
function list_fonts($dir)
{
  $dir = rtrim($dir, '/');
  $dh = opendir($dir);
  $fonts = array();
  
  while (($file = readdir($dh)) !== false )
  {
    if ($file !== '.' && $file !== '..' && get_extension($file)=='ttf') 
      $fonts[] = get_filename_wo_extension($file);
  }
  
  closedir($dh);
  return $fonts;
}

$template->assign(array(
  'crypto' => $conf['cryptographp'],
  'loaded' => $loaded,
  'fonts' => list_fonts(CRYPTO_PATH.'securimage/fonts'),
  'PRESETS' => $presets,
  'CRYPTO_PATH' => CRYPTO_PATH,
  ));

$template->set_filename('plugin_admin_content', dirname(__FILE__).'/template/admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');

?>