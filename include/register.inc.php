<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

$cryptinstall = CRYPTO_PATH.'cryptographp/cryptographp.fct.php';
include($cryptinstall);

add_event_handler('loc_end_page_header', 'add_captcha');
add_event_handler('register_user_check', 'check_captcha');

function add_captcha()
{
  global $template, $conf;

  $template->set_prefilter('register', 'captcha_prefilter');
  $template->assign('CAPTCHA', dsp_crypt($conf['cryptographp_theme'][0].'.cfg.php',1));
}

function captcha_prefilter($content, $smarty)
{
  load_language('plugin.lang', CRYPTO_PATH);
  
  $search = '<p class="bottomButtons">';
  $replace = '
  <fieldset>
    <legend>{\'Antibot test\'|@translate}</legend>
    <ul>
      <li>
        <span class="property">
          <label>{$CAPTCHA}</label>
        </span>
        <input type="text" name="code">
      </li>
    </ul>
  </fieldset>'
."\n".$search;

  return str_replace($search, $replace, $content);
}

function check_captcha($errors)
{
  if (!chk_crypt($_POST['code']))
  {
    load_language('plugin.lang', CRYPTO_PATH);
    array_push($errors, l10n('Invalid Captcha'));
  }

  return $errors;
}

?>