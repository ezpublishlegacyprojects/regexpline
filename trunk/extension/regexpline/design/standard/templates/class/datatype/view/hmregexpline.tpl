{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )}

<div class="block">

    <div class="element">
        <label>Regular expression:</label>
        {if $content.preset|ne('')}{$presets[$content.preset]|wash}{else}<p>{$content.regexp|wash}</p>{/if}
    </div>
    
</div>
<div class="block">

    <div class="element">
        <label>Help text:</label>
        <p>{$content.help_text|wash|nl2br}</p>
    </div>
    
    <div class="element">
        <label>Object name pattern selection:</label>
        {section show=count($content.pattern_selection)|gt(0)}
            <p>Using subpatterns: {section var=selection loop=$content.pattern_selection}{$selection}{delimiter}, {/delimiter}{/section}
        {section-else}
            <p>No subpatterns selected. Using the complete expression.</p> 
        {/section}
    </div>
    
</div>

{undef $content $presets}