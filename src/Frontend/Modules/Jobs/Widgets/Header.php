<?php

namespace Frontend\Modules\Jobs\Widgets;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Jobs\Engine\Model as FrontendJobsModel;

/**
 * This is a widget to view a job specific header
 *
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class Header extends FrontendBaseWidget
{
    private $job;
  
	/**
	 * Execute the extra
	 */
	public function execute()
	{
		// call parent
		parent::execute();

		// code
        
		$this->loadTemplate();
		$this->parse();
	}
    
	/**
	 * Parse
	 */
	private function parse()
	{
		//$this->tpl->assign('widgetJobsCategory', $this->job);
	}
}
