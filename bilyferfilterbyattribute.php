<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class Bilyferfilterbyattribute extends Module
{

	public function __construct()
	{
		$this->name = 'bilyferfilterbyattribute';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Manu';


        $this->displayName = $this->l('Modulo para el filtrado de atributos de Bilyfer');
        $this->description = $this->l('Filtra por color, material, o demás atributos añadidos');
    
        $this->confirmUninstall = $this->l('¿Seguro que lo quiere desinstalar?');

		parent::__construct();

	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('actionProductListOverride')
				|| !$this->registerHook('aboveProductList')
                || !$this->registerHook('actionProductListModifier'))
			return false;
		return true;
	}

	public function hookAboveProductList() {

	    $current_language = $this->context->language->id;

	    $attribute_groups = AttributeGroup::getAttributesGroups($current_language);
	    foreach ($attribute_groups as &$attribute_group) {
	        $attributes = AttributeGroup::getAttributes($current_language, $attribute_group['id_attribute_group']);
            $attribute_group['attributes'] = $attributes;
	    }

	    $ordenation = Tools::getValue('hidden_selectProductSort');
	    $n = Tools::getValue('hidden_n');
	    $p = Tools::getValue('hidden_p');
		$this -> smarty -> assign(array('attribute_groups' => $attribute_groups,
                                        'ordenation' => $ordenation,
                                        'form_n' => $n,
                                        'form_p' => $p)
        );
				//return $this->display( _PS_MODULE_DIR_.'bilyferfilterbyattribute/views/templates/hook/product-sort.tpl');
// return $this->display('/var/www/html/ps16/modules/bilyferfilterbyattribute/views/templates/hook/product-sort.tpl');
		return $this->display(__FILE__, 'product-sort-bilyfer.tpl');
	}


	public function hookActionProductListModifier($params){
	    /* PRODUCTS ARE ALREADY PASSED IN PARAMS
        Hook::exec(
            'actionProductListModifier',
            array(
                'nb_products' => &$nb_products,
                'cat_products' => &$products,
            )
        );
	    */
        //hacer
        /*
        @todo
        */

        $filtered_products = $this->filterByAttribute($params['cat_products']);
        $nb_products = count($filtered_products);

        $params['cat_products'] = $filtered_products;
        $params['nb_products'] = $nb_products;
    }


    private function filterByAttribute($product_list)
    {

        $availabe_att_groups = Tools::getValue('filterByAttributeGroup');
        $result_cat_products = array();

        $attribute_list = array();
        foreach ($availabe_att_groups as $id_att_group => $attribute_value_to_filter_by) {
            if (empty($attribute_value_to_filter_by)) {
                continue;
            }
            else {
                $attribute_list[] = $attribute_value_to_filter_by;
            }
        }

        if (!empty($attribute_list)) {
            foreach ($product_list as $product) {
                $product_obj = new Product($product['id_product']);
                if ($product_obj->productAttributeExists($attribute_list)) {
                    $result_cat_products[] = $product;
                }
            }
        }

        return $result_cat_products;
    }


	public function hookActionProductListOverride ($params)
    {
        /* ESTRUCTURA DEL HOOK
        Hook::exec('actionProductListOverride', array(
            'nbProducts'   => &$this->nbProducts,
            'catProducts'  => &$this->cat_products,
            'hookExecuted' => &$hook_executed,
        ));
        */

        /* FORMA DE FUNCIONAR DEL FILTRO DE CATEGORIA

                // The hook was not executed, standard working
        if (!$hook_executed) {
            $this->context->smarty->assign('categoryNameComplement', '');
            $this->nbProducts = $this->category->getProducts(null, null, null, $this->orderBy, $this->orderWay, true);
            $this->pagination((int)$this->nbProducts); // Pagination must be call after "getProducts"
            $this->cat_products = $this->category->getProducts($this->context->language->id, (int)$this->p, (int)$this->n, $this->orderBy, $this->orderWay);
        }

        */

        $order = Tools::getValue('hidden_selectProductSort');
        $order_arr = explode(':', $order);
        $order_by = $order_arr [0];
        $order_way = $order_arr[1];

        $id_category = Tools::getValue('id_category');
        $category = new Category($id_category);

        $nbProducts = $category->getProducts(null, null, null, $order_by, $order_way, true);


        $n = Tools::getValue('n');
        $p = Tools::getValue('p');

        if ($p == false) {
            $p = 0;
        }
        if ($n == false) {
            $n = Configuration::get('PS_PRODUCTS_PER_PAGE');
        }
        $cat_products = $category->getProducts($this->context->language->id, $p, $n, $order_by, $order_way);


        $result_cat_products = $this->filterByAttribute($cat_products);


        if (!empty($result_cat_products)){
            // productAttributeExists($attributes_list
            $result_nb_cat_products = count($result_cat_products);

            $params['nbProducts'] = $result_nb_cat_products;
            $params['catProducts'] = $result_cat_products;
            $params['hookExecuted'] = true;
        }
        else {
            $params['hookExecuted'] = false;
        }
		/*
		$params['nbProducts'] = $category->getProducts(null, null, null, $order_by, $order_way, true);

        // $params['cat_products'] = $category->getProducts($this->context->language->id, (int)$this->p, (int)$this->n, $order_by, $order_way);
        // we don't have $this -> p and $this -> n so... or we include it in other hidden inputs or... we set it to null
        // due to te fact that I believe they are included in cookies, I'll set it to null in order to test
        $params['cat_products'] = $category->getProducts($this->context->language->id, null, null, $order_by, $order_way);
        $filter_by_material = Tool::getValue();
*/

	}

	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

}
