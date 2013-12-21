<?php
defined('CRYPTO_ID') or die('Hacking attempt!');

include(CRYPTO_PATH.'include/common.inc.php');
add_event_handler('loc_begin_index', 'add_crypto');
add_event_handler('user_comment_check', 'check_crypto', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_crypto()
{
  global $template;
  $template->set_prefilter('guestbook', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  $search = '{$comment_add.CONTENT}</textarea>';
  return str_replace($search, $search."\n{\$CRYPTOGRAPHP}", $content);
}

function check_crypto($action, $comment)
{
  global $conf, $page;
  
  include_once(CRYPTO_PATH.'securimage/securimage.php');
  $securimage = new Securimage();

  if (!is_a_guest()) return $action;

  if ($securimage->check($_POST['captcha_code']) == false)
  {
    $page['errors'][] = l10n('Invalid Captcha');
    return 'reject';
  }

  return $action;
}