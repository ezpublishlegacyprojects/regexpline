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
        <label>{'Object name pattern selection'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}:</label>
        {if count($content.pattern_selection)|gt(0)}
            <p>{'Using subpatterns:'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )} {section var=selection loop=$content.pattern_selection}{$selection}{delimiter}, {/delimiter}{/section}
        {else}
            <p>{'No subpatterns selected. Using the complete expression.'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</p> 
        {/if}
    </div>
    
</div>

{undef $content $presets}