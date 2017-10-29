

<form id="product...sSortForm{if isset($paginationId)}_{$paginationId}{/if}" action="{$request|escape:'html':'UTF-8'}" class="pro...ductsSortForm">
    {*$attribute_groups|var_dump*}
	{foreach $attribute_groups as $grupo_atributo}
        {*$grupo_atributo|var_dump*}
		<select id="filterByAttributeGroup_{$grupo_atributo.id_attribute_group}" class="selectProdu...ctSort form-cont...rol" name="filterByAttributeGroup_{$grupo_atributo['id_attribute_group']}">

			{foreach $grupo_atributo.attributes as $single_atributo}
                {*$single_atributo|var_dump*}
				<option value="{$single_atributo.id_attribute}" {if $orderby eq 'reference' AND $orderway eq 'asc'}selected="selected"{/if}>{$single_atributo.name}</option>

            {/foreach}

		</select>
	
	{/foreach}

</form>

