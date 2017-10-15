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
		if (!parent::install() || !$this->registerHook('actionProductListOverride'))
			return false;
		return true;
	}

	public function actionProductListOverride ($params) {
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

		$id_category = Tools::getValue('id_category');
		$category = new Category($id_category);

		$params['nbProducts'] = $category->getProducts(null, null, null, $this->orderBy, $this->orderWay, true);

        $params['cat_products'] = $category->getProducts($this->context->language->id, (int)$this->p, (int)$this->n, $this->orderBy, $this->orderWay);
        $filter_by_material = Tool::getValue();
	}
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

}
