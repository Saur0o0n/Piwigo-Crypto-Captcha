<div class="titrePage">
  <h2>CryptograPHP</h2>
</div>

<form method="post" class="properties">
<fieldset>
  <legend>{'Configuration'|@translate}</legend>
  
  <ul>			
    <li style="text-align:center;">
      <b>{'Captcha theme'|@translate}</b><br/>
      {foreach from=$available_themes item=theme}
      <div style="display:inline-block;margin-top:5px;">
       <label for="{$theme}-example"><img src="{$CRYPTO_PATH}cryptographp/images/{$theme}.png" alt="{$theme}"/></label> <br/>
        <input type="radio" name="cryptographp_theme" id="{$theme}-example" value="{$theme}" {if $theme == $cryptographp_theme}checked="checked"{/if}/>
      </div>
      {/foreach}
    </li>

    <li>
      <span class="property">{'Comments action'|@translate}</span>
      <label><input type="radio" name="comments_action" value="inactive" {if $comments_action == 'inactive'}checked="checked"{/if}/> {'No captcha'|@translate}</label>
      <label><input type="radio" name="comments_action" value="reject" {if $comments_action == 'reject'}checked="checked"{/if}/> {'Reject'|@translate}</label>
      <label><input type="radio" name="comments_action" value="moderate" {if $comments_action == 'moderate'}checked="checked"{/if}/> {'Moderate'|@translate}</label>
    </li>
  </ul>
  
  <p class="bottomButtons">
    <input class="submit" type="submit" value="{'Submit'|@translate}" name="submit"/>
  </p>
</fieldset>
</form>