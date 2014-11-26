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

/**
 * This is a widget with the Immo categories
 *
 * @author Schets Stijn <info@schetss.be>
 */

class Categories extends FrontendBaseWidget
{
	/**
	 * Execute the extra
	 */
	public function execute()
	{
		// call parent
		parent::execute();

		$this->loadTemplate();
		$this->parse();
	}

	/**
	 * Parse
	 */
	private function parse()
	{
		$this->tpl->assign('widgetImmoCategories', FrontendImmoModel::getCategories());
	}
}
