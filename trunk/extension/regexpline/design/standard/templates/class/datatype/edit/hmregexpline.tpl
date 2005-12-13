{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )}

<div class="block">
    <div class="element">
        <label>Regular expression (Perl-compatible)</label>
        <input type="text" name="ContentClass_hmregexpline_regexp_{$class_attribute.id}" value="{$content.regexp|wash}" size="100" /><br />
        <span class="small">To allow all input: /.*/</span>
    </div>
    
    {if count( $presets )|gt( 0 )}
    <div class="element">
        <label>Presets</label>
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
    <div class="element">
        <label>Help text for users</label>
        <textarea name="ContentClass_hmregexpline_helptext_{$class_attribute.id}" rows="5" cols="80">{$content.help_text|wash}</textarea>
    </div>
    
    <div class="element">
        <label>Object name pattern selection</label>
        {if $content.subpattern_count|gt(0)}
            <select name="ContentClass_hmregexpline_patternselect_{$class_attribute.id}[]" multiple="multiple">
            {section var=sub loop=$content.subpattern_count}
                <option value="{$sub}" {section show=$content.pattern_selection|contains($sub)}selected="selected"{/section}>{$sub}</option>
            {/section}
            </select>
        {else}
            <p><i>No subpatterns defined. Using the complete expression.</i></p>
        {/if}
    </div>
    
    <div class="break"></div>
</div>

{undef $content $presets}