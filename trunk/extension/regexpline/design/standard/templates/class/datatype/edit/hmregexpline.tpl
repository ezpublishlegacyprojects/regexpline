{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )}

<div class="block">
    <div class="element">
        <label>{'Regular expression (Perl-compatible)'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
        <input type="text" name="ContentClass_hmregexpline_regexp_{$class_attribute.id}" value="{$content.regexp|wash}" size="100" /><br />
        <span class="small">{'To allow all input:'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )} /.*/</span>
    </div>
    
    {if count( $presets )|gt( 0 )}
    <div class="element">
        <label>{'Presets'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
        <select name="ContentClass_hmregexpline_preset_{$class_attribute.id}">
        <option value=""></option>
        {foreach $presets as $identifier => $regexp}
        <option value="{$identifier|wash}" {if $content.preset|eq($identifier|wash)}selected="selected"{/if}>{$identifier|wash}</option>
        {/foreach}
        </select>
    </div>
    {/if}
    
    <div class="break"></div>
</div>

<div class="block">
    <div class="block">
        <label>{'Object name pattern'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
        {if $content.subpattern_count|gt(0)}
            <input type="text" name="ContentClass_hmregexpline_namepattern_{$class_attribute.id}" size="100" value="{$content.naming_pattern|wash}" />
            <p>
                {'This field allows you to structure the object naming pattern for this attribute. To use a subpattern of your regular expression, place its number (visible in the list below) in a tag-like notation, e.g. &lt;1&gt; to use the first subpattern.'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}
            </p>
        {else}
            <p><i>{'No subpatterns defined. Using the complete expression.'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</i></p>
        {/if}
    </div>

    <div class="block">
        {if $content.subpattern_count|gt(0)}
        <p>{'These are the available subpatterns:'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}
            <ol>
                {foreach $content.subpatterns as $pattern}
                <li>{$pattern|wash}</li>
                {/foreach}
            </ol>
        </p>
        {else}
        <p>{'No subpatters defined.'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</p>
        {/if}
    </div>
</div>

<div class="block">
    <label>{'Help text for users'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
    <textarea name="ContentClass_hmregexpline_helptext_{$class_attribute.id}" rows="5" cols="80">{$content.help_text|wash}</textarea>
</div>

{undef $content $presets}