<?php

namespace Backend\Modules\Contact\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Contact\Engine\Model as BackendContactModel;

/**
 * This is the delete-action, it deletes an item
 *
 * @author Jesse Dobbelaere <jesse@dobbelaere-ae.be>
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
        if($this->id !== null && BackendContactModel::existsCategory($this->id)) {
            // get data
            $this->record = (array) BackendContactModel::getCategory($this->id);

            // allowed to delete the category?
            if(BackendContactModel::deleteCategoryAllowed($this->id)) {
                // call parent, this will probably add some general CSS/JS or other required files
                parent::execute();

                // delete item
                BackendContactModel::deleteCategory($this->id);

                // trigger event
                BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('id' => $this->id));

                // category was deleted, so redirect
                $this->redirect(BackendModel::createURLForAction('Categories') . '&report=deleted-category&var=' . urlencode($this->record['title']));
            }


            // delete category not allowed
            else $this->redirect(BackendModel::createURLForAction('Categories') . '&error=delete-category-not-allowed&var=' . urlencode($this->record['title']));
        }

        // something went wrong
        else $this->redirect(BackendModel::createURLForAction('Categories') . '&error=non-existing');
    }
}
