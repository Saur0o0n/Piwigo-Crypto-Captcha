<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

load_language('plugin.lang', CRYPTO_PATH);
add_event_handler('loc_begin_index', 'add_crypto');
add_event_handler('contact_form_check', 'check_crypto', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_crypto()
{
  global $template;

  if (!is_a_guest()) return;

  $template->set_prefilter('index', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  global $conf;
  
  $search = '{$contact.content}</textarea></td>';
  $replace = $search.'
      </tr>     
      <tr>
        <td class="title">
          {\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate}
        </td>
        <td>
          <input type="text" name="captcha_code" id="captcha_code" size="'.($conf['cryptographp']['code_length']+1).'" maxlength="'.$conf['cryptographp']['code_length'].'" />
          <img id="captcha" src="{$ROOT_URL}'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image" style="vertical-align:top;">
          <a href="#" id="captcha_refresh" onclick="document.getElementById(\'captcha\').src = \'{$ROOT_URL}'.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
            <img src="{$ROOT_URL}'.CRYPTO_PATH.'template/refresh.png" style="vertical-align:bottom;"></a>
        </td>
        
{footer_script}
var captcha_code = new LiveValidation("captcha_code", {ldelim} onlyOnSubmit: true, insertAfterWhatNode: "captcha_refresh" });
captcha_code.add(Validate.Presence, {ldelim} failureMessage: "{\'Invalid Captcha\'|@translate}" });
{/footer_script}';
  
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