{def $content=$attribute.content
     $class_content=$attribute.class_content}
{if $class_content.display_type|eq("area")}<p>{/if}{$content|wash}{if $class_content.display_type|eq("area")}</p>{/if}
{undef $content $class_content}