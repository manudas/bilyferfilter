{if !isset($request)}
	<!-- Sort products -->
    {if isset($smarty.get.id_category) && $smarty.get.id_category}
        {assign var='request' value=$link->getPaginationLink('category', $category, false, true)
        }	{elseif isset($smarty.get.id_manufacturer) && $smarty.get.id_manufacturer}
        {assign var='request' value=$link->getPaginationLink('manufacturer', $manufacturer, false, true)}
    {elseif isset($smarty.get.id_supplier) && $smarty.get.id_supplier}
        {assign var='request' value=$link->getPaginationLink('supplier', $supplier, false, true)}
    {else}
        {assign var='request' value=$link->getPaginationLink(false, false, false, true)}
    {/if}
{/if}

<form method="post" id="productsSortFormByAttribute{if isset($paginationId)}_{$paginationId}{/if}" action="{$request|escape:'html':'UTF-8'}" class="productsSortFormByAttribute">

	<input name='hidden_selectProductSort' id='hidden_selectProductSort' type="hidden" value="{$ordernation}" />
	<script>

        $(window).load(function(){
			var product_sort_selector = $('#selectProductSort{if isset($paginationId)}_{$paginationId}{/if}');
            product_sort_selector.on('change', function(){
				$('#hidden_selectProductSort').val($('#selectProductSort{if isset($paginationId)}_{$paginationId}{/if}').val());
			});
            $('#hidden_selectProductSort').val($('#selectProductSort{if isset($paginationId)}_{$paginationId}{/if}').val());
            // alert('n and p');


			var serialized_data = $('#productsSortFormByAttribute{if isset($paginationId)}_{$paginationId}{/if}').serialize();




            $(document).off('change', '.selectProductSort{if isset($paginationId)}_{$paginationId}{/if}').on('change', '.selectProductSort', function(e) {
                $('.selectProductSort{if isset($paginationId)}_{$paginationId}{/if}').val($(this).val());

                if($('#layered_form').length > 0)
                    reloadContent('&forceSlide&'+serialized_data);
            });

            $(document).off('change', 'select[name="n"]').on('change', 'select[name="n"]', function(e)
            {
                $('select[name=n]').val($(this).val());
                reloadContent('&forceSlide&'+serialized_data);
            });


			// $sort_form = $("productsSortForm{if isset($paginationId)}_{$paginationId}{/if}");
		});

	</script>
    {*$attribute_groups|var_dump*}
	{foreach $attribute_groups as $grupo_atributo}
        {*$grupo_atributo|var_dump*}
		<select id="filterByAttributeGroup_{$grupo_atributo.id_attribute_group}" class="selectProdu...ctSort form-cont...rol" name="filterByAttributeGroup[{$grupo_atributo['id_attribute_group']}]">
			<option value="">---</option>
			{foreach $grupo_atributo.attributes as $single_atributo}
                {*$single_atributo|var_dump*}
				<option value="{$single_atributo.id_attribute}" {if $orderby eq 'reference' AND $orderway eq 'asc'}selected="selected"{/if}>{$single_atributo.name}</option>

            {/foreach}

		</select>
	
	{/foreach}
	<button type="submit">{l s='Filter' mod='bilyferfilterbyattribute'}</button>

</form>

