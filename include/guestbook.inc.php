<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

load_language('plugin.lang', CRYPTO_PATH);
add_event_handler('loc_begin_index', 'add_crypto');
add_event_handler('user_comment_check_guestbook', 'check_crypto', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_crypto()
{
  global $template;
  
  if (!is_a_guest()) return;
  
  $template->set_prefilter('index', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  global $conf;
  
  $search = '<p><textarea name="content" id="contentid" rows="5" cols="60">{$comment_add.CONTENT}</textarea></p>';
  $replace = $search.'
				<p><label>{\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate} :</label></p>
				<p>
				  <img id="captcha" src="'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image">
				  <a href="#" onclick="document.getElementById(\'captcha\').src = \''.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
				    <img src="'.CRYPTO_PATH.'template/refresh.png"></a>
          <input type="text" name="captcha_code" style="width:'.$conf['cryptographp']['code_length'].'em;" maxlength="'.$conf['cryptographp']['code_length'].'" />
				</p>';

  return str_replace($search, $replace, $content);
}

function check_crypto($action, $comment)
{
  global $conf, $page;
  
  include_once(CRYPTO_PATH.'securimage/securimage.php');
  $securimage = new Securimage();

  if (!is_a_guest()) return $action;

  if ($securimage->check($_POST['captcha_code']) == false)
  {
    array_push($page['errors'], l10n('Invalid Captcha'));
    return 'reject';
  }

  return $action;
}

?>