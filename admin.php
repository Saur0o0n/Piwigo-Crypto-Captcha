<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

$conf['cryptographp'] = unserialize($conf['cryptographp']);
load_language('plugin.lang', CRYPTO_PATH);

if ( isset($_POST['submit']))
{
  $conf['cryptographp'] = array(
    'comments_action' => $_POST['comments_action'],
    'theme'           => $_POST['theme'],
    'captcha_type'    => $_POST['captcha_type'],
    'case_sensitive'  => $conf['cryptographp']['case_sensitive'], //not used, problem with some fonts
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
    );
  
  conf_update_param('cryptographp', serialize($conf['cryptographp']));
  array_push($page['infos'], l10n('Information data registered in database'));
}

$presets = array(
  'bluenoise' => array('perturbation'=>0.25, 'image_bg_color'=>'ffffff', 'text_color'=>'0000ff', 'num_lines'=>2, 'line_color'=>'0000ff', 'noise_level'=>2, 'noise_color'=>'0000ff', 'ttf_file'=>'AlteHassGroteskB'),
  'gray' => array('perturbation'=>1, 'image_bg_color'=>'ffffff', 'text_color'=>'8a8a8a', 'num_lines'=>2, 'line_color'=>'8a8a8a', 'noise_level'=>0.1, 'noise_color'=>'8a8a8a', 'ttf_file'=>'TopSecret'),
  'xcolor' => array('perturbation'=>0.5, 'image_bg_color'=>'ffffff', 'text_color'=>'random', 'num_lines'=>1, 'line_color'=>'ffffff', 'noise_level'=>2, 'noise_color'=>'ffffff', 'ttf_file'=>'Dread'),
  'pencil' => array('perturbation'=>0.8, 'image_bg_color'=>'9e9e9e', 'text_color'=>'363636', 'num_lines'=>0, 'line_color'=>'ffffff', 'noise_level'=>0, 'noise_color'=>'ffffff', 'ttf_file'=>'AllStar'),
  );
  
function list_fonts($dir)
{
  $dir = rtrim($dir, '/');
  $dh = opendir($dir);
  $fonts = array();
  
  while (($file = readdir($dh)) !== false )
  {
    if ($file !== '.' && $file !== '..') $fonts[] = str_replace('.ttf', null, $file);
  }
  
  closedir($dh);
  return $fonts;
}

function presets_to_js($presets)
{
  $out = null;
  
  foreach ($presets as $name => $param)
  {
    $out.= '
function apply_'.$name.'() {
  $("input[name=perturbation]").val("'.$param['perturbation'].'");
  $("input[name=image_bg_color]").val("'.$param['image_bg_color'].'");
  $("input[name=text_color]").val("'.$param['text_color'].'");
  $("input[name=num_lines]").val("'.$param['num_lines'].'");
  $("input[name=line_color]").val("'.$param['line_color'].'");
  $("input[name=noise_level]").val("'.$param['noise_level'].'");
  $("input[name=noise_color]").val("'.$param['noise_color'].'");
  $("input[name=ttf_file]").val(["'.$param['ttf_file'].'"]);

}';
  }
   
  return $out;
}

$template->set_filename('plugin_admin_content', dirname(__FILE__).'/template/admin.tpl');

$template->assign(array(
  'crypto' => $conf['cryptographp'],
  'fonts' => list_fonts(CRYPTO_PATH.'securimage/fonts'),
  'presets' => array_keys($presets),
  'PRESETS_FUNC' => presets_to_js($presets),
  'CRYPTO_PATH' => CRYPTO_PATH,
  ));

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');

?>