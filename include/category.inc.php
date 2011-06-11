<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

$cryptinstall = CRYPTO_PATH.'cryptographp/cryptographp.fct.php';
include($cryptinstall);

add_event_handler('loc_begin_index', 'add_captcha');
add_event_handler('user_comment_check', 'check_captcha', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_captcha()
{
  global $template, $conf;
  
  if (!is_a_guest()) return;
  
  $template->set_prefilter('comments_on_albums', 'captcha_prefilter');
  $template->assign('CAPTCHA', dsp_crypt($conf['cryptographp_theme'][0].'.cfg.php',1));
}

function captcha_prefilter($content, $smarty)
{
  load_language('plugin.lang', CRYPTO_PATH);
  
  $search = '<input type="hidden" name="key" value="{$comment_add.KEY}">';
  $replace = $search.'
  <label>{$CAPTCHA}<input type="text" name="code"></label>';

  return str_replace($search, $replace, $content);
}

function check_captcha($action, $comment)
{
  global $conf, $user;
  
  $my_action = ($conf['cryptographp_theme'][1] == 'reject') ? 'reject':'moderate';

  if ($action == 'reject' OR $action == $my_action OR !is_a_guest())
  {
    return $action;
  }

  if (!chk_crypt($_POST['code']))
  {
    return $my_action;
  }
  else
  {
    return $action;
  }
}

?>