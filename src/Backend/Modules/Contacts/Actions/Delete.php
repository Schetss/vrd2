<?php

/**
 * Delete a contact.
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 */
class BackendContactsDelete extends BackendBaseActionDelete
{
	/**
	 * Execute the current action.
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendContactsModel::exists($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get the current contact
			$this->record = BackendContactsModel::get($this->id);

			// delete it
			BackendContactsModel::delete($this->id);

			// trigger event
			BackendModel::triggerEvent($this->getModule(), 'after_add_image', array('item' => $this->record));

			// redirect back to the index
			$this->redirect(BackendModel::createURLForAction('index') . '&report=deleted&var=' . urlencode($this->record['name']));
		}

		// no contact found
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}
