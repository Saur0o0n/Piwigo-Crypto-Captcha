<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

add_event_handler('loc_end_picture', 'add_crypto');
add_event_handler('user_comment_check', 'check_crypto', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_crypto()
{
  global $template;
  
  if (!is_a_guest()) return;
  
  $template->set_prefilter('picture', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  global $conf;
  
  $search = '#<input type="hidden" name="key" value="{\$comment_add\.KEY}"([ /]*)>#';
  $replace = '<input type="hidden" name="key" value="{$comment_add.KEY}"$1>'.'
  <label>
    <img id="captcha" src="'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image">
    <a href="#" onclick="document.getElementById(\'captcha\').src = \''.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
      <img src="'.CRYPTO_PATH.'template/refresh.png"></a>
    <br>{\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate} :
    <input type="text" name="captcha_code" size="'.($conf['cryptographp']['code_length']+1).'" maxlength="'.$conf['cryptographp']['code_length'].'" />
    
  </label>';

  return preg_replace($search, $replace, $content);
}

function check_crypto($action, $comment)
{
  global $conf;
  
  include_once(CRYPTO_PATH.'securimage/securimage.php');
  $securimage = new Securimage();

  if ( $action == 'reject' or $action == $conf['cryptographp']['comments_action'] or !is_a_guest() )
  {
    return $action;
  }

  if ($securimage->check($_POST['captcha_code']) == false)
  {
    return $conf['cryptographp']['comments_action'];
  }

  return $action;
}

?>