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
 * This is a widget with the job clients
 *
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class Clients extends FrontendBaseWidget
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
		$this->tpl->assign('widgetJobsClients', FrontendJobsModel::getClients());
	}
}
