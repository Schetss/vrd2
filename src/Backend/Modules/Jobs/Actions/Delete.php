<?php

namespace Backend\Modules\Jobs\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Jobs\Engine\Model as BackendJobsModel;

/**
 * This action will delete a job.
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class Delete extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');

		if($this->id !== null && BackendJobsModel::exists($this->id))
		{
			parent::execute();
			$this->record = BackendJobsModel::get($this->id);

			// delete item
			BackendJobsModel::delete($this->id);
			BackendModel::triggerEvent($this->getModule(), 'after_delete', array('item' => $this->record));

			$this->redirect(BackendModel::createURLForAction('index') . '&report=deleted&var=' . urlencode($this->record['title']));
		}
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}
