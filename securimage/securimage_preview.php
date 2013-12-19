<?php
/**
 * this file take parameters from $_GET, reserved for admin usage
 */
 
define('PHPWG_ROOT_PATH','../../../');
include_once(PHPWG_ROOT_PATH.'include/common.inc.php');

is_admin() or die('Hacking attempt!');


$temp_conf = array(
  'captcha_type'    => $_GET['captcha_type'],
  'width'           => (int)$_GET['width'], 
  'height'          => (int)$_GET['height'],
  'perturbation'    => (float)$_GET['perturbation'],
  'image_bg_color'  => $_GET['image_bg_color'],
  'code_length'     => (int)$_GET['code_length'],
  'text_color'      => $_GET['text_color'],
  'num_lines'       => (float)$_GET['num_lines'],
  'line_color'      => $_GET['line_color'],
  'noise_level'     => (float)$_GET['noise_level'],
  'noise_color'     => $_GET['noise_color'],
  'ttf_file'        => $_GET['ttf_file'],
  );

  
// randomize colors
function randomColor()
{
  mt_srand((double)microtime()*1000000);
  $c = null;
  while(strlen($c)<6)
  {
      $c .= sprintf("%02X", mt_rand(0, 255));
  }
  return $c;
}

foreach (array('image_bg_color','text_color','line_color','noise_color') as $color)
{
  if ($temp_conf[$color] == 'random') $temp_conf[$color] = randomColor();
}


require_once dirname(__FILE__) . '/securimage.php';
$img = new securimage();

$img->ttf_file        = './fonts/'.$temp_conf['ttf_file'].'.ttf';
$img->captcha_type    = ($temp_conf['captcha_type'] == 'string') ? Securimage::SI_CAPTCHA_STRING : Securimage::SI_CAPTCHA_MATHEMATIC;
// $img->case_sensitive  = get_boolean($temp_conf['case_sensitive']);
$img->image_width     = $temp_conf['width']; 
$img->image_height    = $temp_conf['height'];
$img->perturbation    = $temp_conf['perturbation'];
$img->image_bg_color  = new Securimage_Color('#'.$temp_conf['image_bg_color']);
$img->text_color      = new Securimage_Color('#'.$temp_conf['text_color']); 
$img->num_lines       = $temp_conf['num_lines'];
$img->line_color      = new Securimage_Color('#'.$temp_conf['line_color']);
$img->noise_level     = $temp_conf['noise_level'];
$img->noise_color     = new Securimage_Color('#'.$temp_conf['noise_color']);
$img->code_length     = $temp_conf['code_length'];

$img->show();

?>