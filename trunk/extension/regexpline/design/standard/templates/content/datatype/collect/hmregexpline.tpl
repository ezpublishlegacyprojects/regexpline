{def $data_text=cond( is_set( $#collection_attributes[$attribute.id] ),
                      $#collection_attributes[$attribute.id].data_text,
                      $attribute.content )
     $class_content=$attribute.class_content}

{switch match=$class_content.display_type}
{case match="area"}
<textarea cols="80" rows="10" name="ContentObjectAttribute_hmregexpline_data_text_{$attribute.id}">
{$data_text|wash}
</textarea>
{/case}

{case}
<input type="text" name="ContentObjectAttribute_hmregexpline_data_text_{$attribute.id}" value="{$data_text|wash}" />
{/case}
{/switch}
{undef $data_text}