{let content=$class_attribute.content}

<div class="block">

    <div class="element">
        <label>Regular expression:</label>
        <p>{$content.regexp|wash}</p>
    </div>
    
</div>
<div class="block">

    <div class="element">
        <label>Help text:</label>
        <p>{$content.help_text|wash|nl2br}</p>
    </div>
    
</div>

{/let}