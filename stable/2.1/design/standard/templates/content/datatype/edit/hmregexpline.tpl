{def $class_content=$attribute.class_content}

{if $class_content.help_text|ne("")}
    <fieldset class="small">
        <legend>{'Help text'|i18n( 'extension/regexpline/design/standard/content/datatype/edit' )}</legend>
        <p>{$class_content.help_text|wash|nl2br}</p>
    </fieldset>
    <br />
{/if}

{switch match=$class_content.display_type}
{case match="area"}
<textarea cols="80" rows="10" name="ContentObjectAttribute_hmregexpline_data_text_{$attribute.id}">{$attribute.content|wash}</textarea>
{/case}

{case}
<input class="box" type="text" size="70" name="ContentObjectAttribute_hmregexpline_data_text_{$attribute.id}" value="{$attribute.content|wash}" />
{/case}
{/switch}

{undef $class_content}