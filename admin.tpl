<div class="titrePage">
  <h2>CryptograPHP</h2>
</div>

<form method="post" class="properties">
<fieldset>
  <legend>{'Captcha theme'|@translate}</legend>
  
  {foreach from=$available_themes item=theme}
  <div style="display:inline-block;margin-top:5px;">
   <label for="{$theme}-example"><img src="{$CRYPTO_PATH}cryptographp/{$theme}.png" alt="{$theme}"/></label> <br/>
    <input type="radio" name="cryptographp_theme" id="{$theme}-example" value="{$theme}" {if $theme == $cryptographp_theme}checked="checked"{/if}/>
  </div>
  {/foreach}
  
  <p class="bottomButtons">
    <input class="submit" type="submit" value="{'Submit'|@translate}" name="submit"/>
  </p>
</fieldset>
</form>