{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )}

<div class="block">
    <div class="element">
        <label>{'Regular expression'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}:</label>
        {if count($content.preset)|gt(0)}
            <ul>
                {foreach $content.preset as $preset}
                    <li>{$presets[$preset]|wash}</li>
                {/foreach}
            </ul>
        {else}
            <ul>
                {foreach $content.regexp as $regexp}
                <li>{$regexp|wash}</li>
                {/foreach}
            </ul>
        {/if}
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

<div class="block">
    <label>{'Display type'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}:</label>
    <p>
    {switch match=$content.display_type}
    {case match="area"}
        {'Text area (Text block)'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}
    {/case}
    {case}
        {'Single text line'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}
    {/case}
    {/switch}
    </p> 
</div>

{undef $content $presets}