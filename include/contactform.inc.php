<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

$cryptinstall = CRYPTO_PATH.'cryptographp/cryptographp.fct.php';
include($cryptinstall);

add_event_handler('display_contactform', 'add_crypto');
add_event_handler('check_contactform_params', 'check_crypto');

function add_crypto()
{
  global $template, $conf;

  if (!is_a_guest()) return;

  $template->set_prefilter('cf_form', 'prefilter_crypto');
  $template->assign('CAPTCHA', dsp_crypt($conf['cryptographp_theme'][0].'.cfg.php',1));
}

function prefilter_crypto($content, $smarty)
{
  $search = '
      <tr>
        <td class="contact-form-left">&nbsp;</td>
        <td class="contact-form-right"><input class="submit" type="submit" value="{\'cf_submit\'|@translate}"></td>
      </tr>';
  $replace = '
    <tr>
      <td class="contact-form-left" style="vertical-align:top;">{\'Antibot test\'|@translate}</td>
      <td class="contact-form-right"><input type="text" name="code"> <span style="vertical-align:top;">{$CAPTCHA}</span></td>
    </tr>'
  ."\n".$search;
  
  return str_replace($search, $replace, $content);
}

function check_crypto($infos)
{
  if (!is_a_guest()) return $infos;

  if (!chk_crypt($_POST['code']))
  {
    load_language('plugin.lang', CRYPTO_PATH);
    array_push($infos['errors'], l10n('Invalid Captcha'));
  }

  return $infos;
}

?>