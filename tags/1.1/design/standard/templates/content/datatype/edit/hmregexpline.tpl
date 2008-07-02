{def $class_content=$attribute.class_content}

{if $class_content.help_text|ne("")}
    <fieldset class="small">
        <legend>{'Help text'|i18n( 'extension/regexpline/design/standard/content/datatype/edit' )}</legend>
        <p>{$class_content.help_text|wash|nl2br}</p>
    </fieldset>
    <br />
{/if}

<input class="box" type="text" size="70" name="ContentObjectAttribute_hmregexpline_data_text_{$attribute.id}" value="{$attribute.content|wash}" />

{undef $class_content}