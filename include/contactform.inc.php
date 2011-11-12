<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

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
  
  $search = '
      <tr>
        <td class="contact-form-left">&nbsp;</td>
        <td class="contact-form-right"><input class="submit" type="submit" value="{\'cf_submit\'|@translate}"></td>
      </tr>';
  $replace = '     
    <tr>
      <td class="contact-form-left" style="vertical-align:top;">
        {\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate}
        <img id="captcha" src="'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image">
      </td>
      <td class="contact-form-right"><input type="text" name="captcha_code" size="'.($conf['cryptographp']['code_length']+1).'" maxlength="'.$conf['cryptographp']['code_length'].'" />
        <a href="#" onclick="document.getElementById(\'captcha\').src = \''.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
          <img src="'.CRYPTO_PATH.'template/refresh.png"></a>
      </td>
    </tr>'
  ."\n".$search;
  
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