{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )}

<div class="block">
    <div class="element">
        {if count($content.preset)|gt(0)}
            <table class="list">
            <tr>
                <th>{'Identifier'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</th>
                <th>{'Regular expression'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</th>
                <th>{'Negated'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</th>
            </tr>
                {foreach $content.preset as $preset}
                <tr>
                    <td>{$preset|wash}</td>
                    <td>{$presets[$preset]|wash}</td>
                    <td>{cond( first_set( $content.negates[$preset|wash], 0 ), 'Yes', 'No' )}</td>
                </tr>
                {/foreach}
            </table>
        {else}
            <table class="list">
            <tr>
                <th>{'Regular expression'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</th>
                <th>{'Error message'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</th>
                <th>{'Negated'|i18n( 'extension/regexpline/design/standard/class/datatype/view' )}</th>
            </tr>
                {foreach $content.regexp as $index => $regexp}
                <tr>
                    <td>{$regexp|wash}</td>
                    <td>{first_set( $content.error_messages[$index]|wash, '&nbsp;' )}</td>
                    <td>{cond( first_set( $content.negates[$index], 0 ), 'Yes', 'No' )}</td>
                </tr>
                {/foreach}
            </table>
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