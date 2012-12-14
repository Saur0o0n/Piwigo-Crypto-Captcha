{combine_css path=$CRYPTO_PATH|@cat:"template/colorpicker/colorpicker.css"}
{combine_script id="jquery.colorpicker" require="jquery" path=$CRYPTO_PATH|@cat:"template/colorpicker/colorpicker.js"}

{footer_script require='jquery.colorpicker'}{literal}
jQuery(document).ready(function() {
  // colorpicker
  $('.colorpicker-input')
    .ColorPicker({
      onSubmit: function(hsb, hex, rgb, el) { 
        $(el).val(hex); 
        $(el).ColorPickerHide(); 
      },
      onChange: function(hsb, hex, rgb, el) { 
        $(el).val(hex); 
        changeColor(el, hex);
        changePreview();
        setThemeCutom();
      },
      onBeforeShow: function () { 
        $(this).ColorPickerSetColor(this.value); 
      }
    })
    .bind('keyup', function(){ 
      $(this).ColorPickerSetColor(this.value);
      changeColor(this, $(this).val());
    })
    .each(function() {
      changeColor(this, $(this).val());
    });
  
  // change button
  $('.button').click(function() {
    $('.button').removeClass('selected');
    $(this).addClass('selected');
    $('input[name=button_color]').val($(this).attr('title'));
    $('#reload').attr('src', '{/literal}{$CRYPTO_PATH}{literal}template/refresh_'+ $(this).attr('title') +'.png');
  });
  
  // apply a preset
  $('.preset').click(function() {
    $('.preset').removeClass('selected');
    $(this).addClass('selected');
    eval('apply_'+ $(this).attr('title') +'();');
    $('.colorpicker-input').each(function() { changeColor(this, $(this).val()); });
    $('input[name=theme]').val($(this).attr('title'));
    changePreview();
  });
  
  // display customization panel
  $('.customize').click(function() {
    $('#theming').toggle();
  });
  
  // change theme to 'custom' if a parameter is changed
  $('input.istheme').change(function() {
    setThemeCutom();
  });
  
  // update the preview
  $('input.istheme, input.preview').change(function() {
    changePreview();
  });
  $('#reload').click(function() {
    changePreview();
  });
  
  // links for random color
  $('a.random').click(function() {
    $(this).prev('label').children('input').val('random');
    changeColor($(this).prev('label').children('input'), 'random');
    changePreview();
    setThemeCutom();
  });
});

function setThemeCutom() {
  $('.preset').removeClass('selected');
  $('input[name=theme]').val('custom');
}

function changePreview()
{
  options = new Array();
  str = '';
  
  $('input[type="text"], input[type="radio"]:checked').each(function() {
    options[$(this).attr('name')] = $(this).val();
  });
  
  for (x in options) {
    str+= '&' + x + '=' + options[x];
  }
  $('#captcha').attr('src', '{/literal}{$CRYPTO_PATH}{literal}securimage/securimage_preview.php?' + new Date().getTime() + str);
}

function changeColor(target, color) {
  if (color == 'random') color = '808080';
  if (parseInt(color, 16) > 16777215/2) {
    $(target).css('color', '#222');
  } else {
    $(target).css('color', '#ddd');
  }
  $(target).css('background', '#'+color)
}
{/literal}

{$PRESETS_FUNC}
{/footer_script}

{html_head}
<style type="text/css">
{foreach from=$fonts item=font}
@font-face {ldelim}  
  font-family: {$font} ;  
  src: url({$CRYPTO_PATH}securimage/fonts/{$font}.ttf) format("truetype");  
}
{/foreach}

.preset img, .button img {ldelim}
  margin:1px;
  padding:3px;
  border:1px solid #999;
}
.preset.selected img, .button.selected img {ldelim}
  border-color:#f70;
}
</style>
{/html_head}

<div class="titrePage">
  <h2>Crypto Captcha</h2>
</div>

<form method="post" class="properties">
<fieldset>
  <legend>{'Configuration'|@translate}</legend>
  
  <ul>
    <li>
      <span class="property">{'Activate on'|@translate}</span>
      <label><input type="checkbox" name="activate_on[picture]" value="1" {if $crypto.activate_on.picture}checked="checked"{/if}> {'Picture comments'|@translate}</label>
      {if $loaded.category}<label><input type="checkbox" name="activate_on[category]" value="1" {if $crypto.activate_on.category}checked="checked"{/if}> {'Album comments'|@translate}</label>{/if}
      <label><input type="checkbox" name="activate_on[register]" value="1" {if $crypto.activate_on.register}checked="checked"{/if}> {'Register form'|@translate}</label>
      {if $loaded.contactform}<label><input type="checkbox" name="activate_on[contactform]" value="1" {if $crypto.activate_on.contactform}checked="checked"{/if}> {'Contact form'|@translate}</label>{/if}
      {if $loaded.guestbook}<label><input type="checkbox" name="activate_on[guestbook]" value="1" {if $crypto.activate_on.guestbook}checked="checked"{/if}> {'Guestbook'|@translate}</label>{/if}
    </li>
    <li>
      <span class="property">{'Comments action'|@translate}</span>
      <label><input type="radio" name="comments_action" value="reject" {if $crypto.comments_action == 'reject'}checked="checked"{/if}> {'Reject'|@translate}</label>
      <label><input type="radio" name="comments_action" value="moderate" {if $crypto.comments_action == 'moderate'}checked="checked"{/if}> {'Moderate'|@translate}</label>
    </li>
    <li>
      <span class="property">{'Captcha type'|@translate}</span>
      <label><input type="radio" name="captcha_type" class="preview" value="string" {if $crypto.captcha_type == 'string'}checked="checked"{/if}> {'Random string'|@translate}</label>
      <label><input type="radio" name="captcha_type" class="preview" value="math" {if $crypto.captcha_type == 'math'}checked="checked"{/if}> {'Simple equation'|@translate}</label>
    </li>
    <!--<li>
      <span class="property">{'Case sensitive'|@translate}</span>
      <label><input type="radio" name="case_sensitive" value="false" {if $crypto.case_sensitive == 'false'}checked="checked"{/if}> {'No'|@translate}</label>
      <label><input type="radio" name="case_sensitive" value="true" {if $crypto.case_sensitive == 'true'}checked="checked"{/if}> {'Yes'|@translate}</label>
    </li>-->
    <li>
      <span class="property">{'Code lenght'|@translate}</span>
      <label><input type="text" name="code_length" class="preview" value="{$crypto.code_length}" size="6" maxlength="2"></label>
    </li>
    <li>
      <span class="property">{'Width'|@translate}</span>
      <label><input type="text" name="width" class="preview" value="{$crypto.width}" size="6" maxlength="3"> {'good value:'|@translate} lenght&times;30</label>
    </li>
    <li>
      <span class="property">{'Height'|@translate}</span>
      <label><input type="text" name="height" class="preview" value="{$crypto.height}" size="6" maxlength="3"> {'good value:'|@translate} lenght&times;12</label>
    </li>
    <li>
      <span class="property">{'Button color'|@translate}</span>
      <div style="display:relative;margin-left:51%;">
        <a class="button {if $crypto.button_color == 'dark'}selected{/if}" title="dark"><img src="{$CRYPTO_PATH}template/refresh_dark.png" alt="dark"></a>
        <a class="button {if $crypto.button_color == 'light'}selected{/if}" title="light"><img src="{$CRYPTO_PATH}template/refresh_light.png" alt="light"></a>
        <input type="hidden" name="button_color" value="{$crypto.button_color}">
      </div>
    </li>
    <li>
      <span class="property">{'Captcha theme'|@translate}</span>
      <div style="display:relative;margin-left:51%;">
        {foreach from=$presets item=preset}
        <a class="preset {if $crypto.theme == $preset}selected{/if}" title="{$preset}"><img src="{$CRYPTO_PATH}template/presets/{$preset}.png" alt="{$preset}"></a>
        {/foreach}
        <br><a class="customize">{'Customize'|@translate}</a><input type="hidden" name="theme" value="{$crypto.theme}">
      </div>
    </li>
  </ul>
  
  <fieldset {if $crypto.theme != 'custom'}style="display:none;"{/if} id="theming">
    <legend>{'Customize'|@translate}</legend>
    
    <ul>
      <li>
        <span class="property">{'Perturbation'|@translate}</span>
        <label><input type="text" name="perturbation" value="{$crypto.perturbation}" class="istheme" size="6" maxlength="4"> {'range:'|@translate} 0 - 1</label>
      </li>
      <li>
        <span class="property">{'Background color'|@translate}</span>
        <label><input type="text" name="image_bg_color" value="{$crypto.image_bg_color}" class="colorpicker-input istheme" size="6" maxlength="6"></label> 
        <a class="random" title="{'random'|@translate}"><img src="{$CRYPTO_PATH}/template/arrow_switch.png"></a>
      </li>
      <li>
        <span class="property">{'Text color'|@translate}</span>
        <label><input type="text" name="text_color" value="{$crypto.text_color}" class="colorpicker-input istheme" size="6" maxlength="6"></label> 
        <a class="random" title="{'random'|@translate}"><img src="{$CRYPTO_PATH}/template/arrow_switch.png"></a>
      </li>
      <li>
        <span class="property">{'Lines density'|@translate}</span>
        <label><input type="text" name="num_lines" value="{$crypto.num_lines}" class="istheme" size="6" maxlength="4"> {'range:'|@translate} 0 - 10</label>
      </li>
      <li>
        <span class="property">{'Lines color'|@translate}</span>
        <label><input type="text" name="line_color" value="{$crypto.line_color}" class="colorpicker-input istheme" size="6" maxlength="6"></label> 
        <a class="random" title="{'random'|@translate}"><img src="{$CRYPTO_PATH}/template/arrow_switch.png"></a>
      </li>
      <li>
        <span class="property">{'Noise level'|@translate}</span>
        <label><input type="text" name="noise_level" value="{$crypto.noise_level}" class="istheme" size="6" maxlength="4"> {'range:'|@translate} 0 - 10</label>
      </li>
      <li>
        <span class="property">{'Noise color'|@translate}</span>
        <label><input type="text" name="noise_color" value="{$crypto.noise_color}" class="colorpicker-input istheme" size="6" maxlength="6"></label> 
        <a class="random" title="{'random'|@translate}"><img src="{$CRYPTO_PATH}/template/arrow_switch.png"></a>
      </li>
      <li>
        <span class="property">{'Font'|@translate}</span>
        <div style="display:relative;margin-left:51%;">
          {foreach from=$fonts item=font}
          <label style="font-family:{$font};" title="{$font}"><input type="radio" name="ttf_file" value="{$font}" {if $crypto.ttf_file == $font}checked="checked"{/if} class="istheme"> {$font}</label>
          {/foreach}
        </div>
      </li>
    </ul>
    
    {'Tip: type "random" on a color field to have a random color'|@translate}
  </fieldset>
  
  <ul style="margin-top:30px;">
    <li>
      <span class="property">{'Preview'|@translate}</span>
      <img id="captcha" src="{$CRYPTO_PATH}securimage/securimage_show.php" alt="CAPTCHA Image">
      <a href="#" onClick="return false;"><img id="reload" src="{$CRYPTO_PATH}template/refresh_{$crypto.button_color}.png"></a>
    </li>
  </ul>
  
</fieldset>
<p><input class="submit" type="submit" value="{'Submit'|@translate}" name="submit"></p>
</form>

<div style="text-align:right;">
  All free fonts from <a href="http://www.dafont.com" target="_blank">dafont.com</a> | 
  Powered by : <a href="http://www.phpcaptcha.org/" target="_blank"><img src="{$CRYPTO_PATH}template/logo.png" alt="Secureimage"></a>
</div>