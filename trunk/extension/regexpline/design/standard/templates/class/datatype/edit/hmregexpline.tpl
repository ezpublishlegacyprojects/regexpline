{let content=$class_attribute.content}

<div class="block">
    <label>Regular expression (Perl-compatible):</label>
    <input class="box" type="text" name="ContentClass_hmregexpline_regexp_{$class_attribute.id}" value="{$content.regexp|wash}" size="60" />
    <span class="small">To allow all input: /.*/</span>
</div>

<div class="block">
    <label>Help text for users:</label>
    <textarea name="ContentClass_hmregexpline_helptext_{$class_attribute.id}" rows="5" cols="60">{$content.help_text|wash|nl2br}</textarea>
</div>

{/let}