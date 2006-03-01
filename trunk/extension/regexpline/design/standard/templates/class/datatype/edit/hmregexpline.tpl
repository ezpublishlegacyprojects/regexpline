{def $content=$class_attribute.content
     $presets=ezini( 'GeneralSettings', 'RegularExpressions', 'regexpline.ini' )
     $hasPresets=count($content.preset)|gt(0)}

<script type="text/javascript">
{literal}
function addLine{/literal}{$class_attribute.id}{literal}( button )
{
    if( document.getElementById && document.createElement )
    {
        var container = document.getElementById( 'regexp_container' );
        var par = document.createElement( 'p' );
        par.style.padding = '0.5em';
        par.style.marginBottom = '1em';
        par.style.border = '1px solid #eaeaea';
        
        var label = document.createElement( 'label' );
        label.className = 'small';
        var labelText = document.createTextNode( '{/literal}{'Expression'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}{literal}:' );
        label.appendChild( labelText );
        par.appendChild( label );
        
        label = labelText = null;
        
        var input = document.createElement( 'input' );
        input.type = 'text';
        input.name = 'ContentClass_hmregexpline_regexp_{/literal}{$class_attribute.id}{literal}[]';
        input.size = 100;    
        par.appendChild( input );
        
        input = null;
        
        par.appendChild( document.createElement( 'br' ) );
        
        label = document.createElement( 'label' );
        label.className = 'small';
        labelText = document.createTextNode( '{/literal}{'Error message'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}{literal}:' );
        label.appendChild( labelText );
        par.appendChild( label );
        
        label = labelText = null;
        
        var input = document.createElement( 'input' );
        input.type = 'text';
        input.name = 'ContentClass_hmregexpline_errmsg_{/literal}{$class_attribute.id}{literal}[]';
        input.size = 100;    
        par.appendChild( input );
        
        input = null;
        
        container.appendChild( par );
    }
}

function removeLines{/literal}{$class_attribute.id}{literal}()
{
    if( document.getElementById && document.getElementsByName )
    {
        var checkBoxes = document.getElementsByName( 'ContentClass_remove_regexpitem[]' );
        
        for( i=0; i < checkBoxes.length; i++ )
        {
            if( checkBoxes[i].checked )
            {
                element = document.getElementById( checkBoxes[i].value );
                
                if( element != null )
                {
                    element.parentNode.removeChild( element );
                }
            }
        }
    }
}
{/literal}
</script>

<div class="block">
    <div class="element">
        <div id="regexp_container">
            <label>{'Regular expression(s) (Perl-compatible)'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
            <span class="small">{'To allow all input:'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )} /.*/</span>
            {foreach $content.regexp as $index => $regexp}
            <p id="regexp{$index}" style="border: 1px solid #eaeaea; padding: 0.5em; margin-bottom: 1em;">
                {if $index|gt(0)}<input type="checkbox" name="ContentClass_remove_regexpitem[]" value="regexp{$index}" />{/if}
                <label class="small">{'Expression'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}:</label>
                <input type="text" name="ContentClass_hmregexpline_regexp_{$class_attribute.id}[]" value="{$regexp|wash}" size="100" {if $hasPresets}disabled="disabled"{/if} /><br />
                
                <label class="small">{'Error message'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}:</label>
                <input type="text" name="ContentClass_hmregexpline_errmsg_{$class_attribute.id}[]" value="{$content.error_messages[$index]|wash}" size="100" {if $hasPresets}disabled="disabled"{/if} />
            </p>
            {/foreach}
        </div>
        <input type="button" name="ContentClass_add_regexp" value="{'Add regular expression'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}" onclick="javascript:addLine{$class_attribute.id}(this);" {if $hasPresets}disabled="disabled"{/if} />
        <input type="button" name="ContentClass_remove_regexp" value="{'Remove regular expression(s)'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}" onclick="javascript:removeLines{$class_attribute.id}();" {if $hasPresets}disabled="disabled"{/if} />
    </div>
    
    {if count( $presets )|gt( 0 )}
    <div class="element">
        <label>{'Presets'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
        <select name="ContentClass_hmregexpline_preset_{$class_attribute.id}[]" multiple="multiple">
        {foreach $presets as $identifier => $regexp}
        <option value="{$identifier|wash}" {if $content.preset|contains( $identifier|wash )}selected="selected"{/if}>{$identifier|wash}</option>
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
        {/if}
    </div>
</div>

<div class="block">
    <label>{'Display as'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
    <input type="radio" name="ContentClass_hmregexpline_display_{$class_attribute.id}" value="line" {if $content.display_type|eq("line")}checked="checked"{/if} /> {'Single text line'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}
    <input type="radio" name="ContentClass_hmregexpline_display_{$class_attribute.id}" value="area" {if $content.display_type|eq("area")}checked="checked"{/if} /> {'Text area (Text block)'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}
</div>

<div class="block">
    <label>{'Help text for users'|i18n( 'extension/regexpline/design/standard/class/datatype/edit' )}</label>
    <textarea name="ContentClass_hmregexpline_helptext_{$class_attribute.id}" rows="5" cols="80">{$content.help_text|wash}</textarea>
</div>

{undef $content $presets}