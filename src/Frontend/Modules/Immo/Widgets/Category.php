<?php

namespace Frontend\Modules\Immo\Widgets;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Immo\Engine\Model as FrontendImmoModel;
use Frontend\Core\Engine\Theme as FrontendTheme;

/**
 * This is a widget to view immo within a category
 *
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 * @author Jacco de Heus <jacco@webleads.nl>
 * @author Stijn Schets <stijn@schetss.be>
 */
class Category extends FrontendBaseWidget
{
	/**
	 * The item.
	 *
	 * @var	array
	 */
	private $item;
  
	/**
	 * Execute the extra
	 */
	public function assignTemplate()
	{
		$template = FrontendTheme::getPath(FRONTEND_MODULES_PATH.'/Immo/Layout/Widgets/Category.tpl');

		return $template;
	}
    
	/**
	 * Execute the extra
	 */
	public function execute()
	{
		parent::execute();
		
		$this->loadData();
		
		$template = $this->assignTemplate();
		$this->loadTemplate($template);
		
		$this->parse();
	}
	
	/**
	 * Load the data
	 */
	private function loadData()
	{
		$this->item = FrontendImmoModel::getAllForCategory((int) $this->data['id']);
	}
	
	/**
	 * Parse
	 */
	private function parse()
	{
		if($this->item){
			$this->tpl->assign('widgetImmoInCategory', $this->item);
		}
	}
}
