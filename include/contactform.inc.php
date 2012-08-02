<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

load_language('plugin.lang', CRYPTO_PATH);
add_event_handler('display_contactform', 'add_crypto');
add_event_handler('check_contactform_params', 'check_crypto');

function add_crypto()
{
  global $template;

  if (!is_a_guest()) return;

  $template->set_prefilter('cf_form', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  global $conf;
  
  $search = '<td class="contact-form-right"><textarea name="cf_message" id="cf_message" rows="10" cols="40">{$CF.MESSAGE}</textarea></td>';
  $replace = $search.'
      </tr>     
      <tr>
        <td class="contact-form-left" style="vertical-align:top;">
          {\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate}
          <img id="captcha" src="{$ROOT_URL}'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image">
          <a href="#" onclick="document.getElementById(\'captcha\').src = \'{$ROOT_URL}'.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
            <img src="{$ROOT_URL}'.CRYPTO_PATH.'template/refresh.png"></a>
        </td>
        <td class="contact-form-right"><input type="text" name="captcha_code" size="'.($conf['cryptographp']['code_length']+1).'" maxlength="'.$conf['cryptographp']['code_length'].'" /></td>';
  
  return str_replace($search, $replace, $content);
}

function check_crypto($infos)
{
  if (!is_a_guest()) return $infos;
  
  include_once(CRYPTO_PATH.'securimage/securimage.php');
  $securimage = new Securimage();
  
  if ($securimage->check($_POST['captcha_code']) == false)
  {
    array_push($infos['errors'], l10n('Invalid Captcha'));
  }

  return $infos;
}

?>