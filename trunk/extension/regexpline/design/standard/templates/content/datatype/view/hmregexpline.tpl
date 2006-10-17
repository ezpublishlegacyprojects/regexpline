{def $content=$attribute.content|wash
     $class_content=$attribute.class_content}

{if $class_content.display_type|eq("area")}
    <p>{$content|nl2br}</p>
{else}
    {$content}
{/if}

{undef $content $class_content}