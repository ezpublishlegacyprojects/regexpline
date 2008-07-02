{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )}

<div class="block">

    <div class="element">
        <label>{'Regular expression'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}:</label>
        {if $content.preset|ne('')}{$presets[$content.preset]|wash}{else}<p>{$content.regexp|wash}</p>{/if}
    </div>
    
</div>
<div class="block">

    <div class="element">
        <label>{'Help text'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}:</label>
        <p>{$content.help_text|wash|nl2br}</p>
    </div>
    
    <div class="element">
        <label>{'Object name pattern'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}:</label>
        {if $content.naming_pattern|ne('')}
            <p>{$content.naming_pattern|wash}</p>
        {else}
            <p>{'No pattern supplied. Using the complete expression.'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</p> 
        {/if}
    </div>
    
</div>

{undef $content $presets}