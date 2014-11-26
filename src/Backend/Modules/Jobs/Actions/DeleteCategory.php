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
 * This action will delete a category.
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class DeleteCategory extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendJobsModel::existsCategory($this->id))
		{
			$this->record = (array) BackendJobsModel::getCategory($this->id);

			if(BackendJobsModel::deleteCategoryAllowed($this->id))
			{
				parent::execute();

				// delete item
				BackendJobsModel::deleteCategory($this->id);
				BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('item' => $this->record));

				// category was deleted, so redirect
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=deleted-category&var=' . urlencode($this->record['title']));
			}
			else $this->redirect(BackendModel::createURLForAction('categories') . '&error=delete-category-not-allowed&var=' . urlencode($this->record['title']));
		}
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}
