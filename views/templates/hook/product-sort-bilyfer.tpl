

<form id="productsSortForm{if isset($paginationId)}_{$paginationId}{/if}" action="{$request|escape:'html':'UTF-8'}" class="productsSortForm">

	{foreach $attribute_list as $atributo}
		
		<select id="selectProductSort{if isset($paginationId)}_{$paginationId}{/if}" class="selectProductSort form-control">
			<option value="{if $page_name != 'best-sales'}{$orderbydefault|escape:'html':'UTF-8'}:{$orderwaydefault|escape:'html':'UTF-8'}{/if}"{if !in_array($orderby, array('price', 'name', 'quantity', 'reference')) && $orderby eq $orderbydefault} selected="selected"{/if}>{l s='Sort by'}</option>
		
			<option value="reference:asc"{if $orderby eq 'reference' AND $orderway eq 'asc'} selected="selected"{/if}>{l s='Reference: Lowest first'}</option>
			<option value="reference:desc"{if $orderby eq 'reference' AND $orderway eq 'desc'} selected="selected"{/if}>{l s='Reference: Highest first'}</option>
		</select>
	
	{/foreach}

</form>

