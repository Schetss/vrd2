<?php

/**
 * Display a form to create a new contact.
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 * @author Matthias Mullie <matthias@mullie.eu>
 */
class BackendContactsAdd extends BackendBaseActionAdd
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse the datagrid
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Load the form
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add');

		// set hidden values
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// create elements
		$this->frm->addText('name', null, 255, 'inputText title', 'inputTextError, title');
		$this->frm->addEditor('contact');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');
	}

	/**
	 * Validate the form
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('name')->isFilled(BL::err('NameIsRequired'));
			$this->frm->getField('contact')->isFilled(BL::err('ContactIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['contact'] = $this->frm->getField('contact')->getValue();
				$item['hidden'] = $this->frm->getField('hidden')->getValue();
				$item['user_id'] = BackendAuthentication::getUser()->getUserId();
				$item['language'] = BackendLanguage::getWorkingLanguage();
				$item['sequence'] = BackendContactsModel::getMaximumSequence() + 1;
				$item['created_on'] = BackendModel::getUTCDate();
				$item['edited_on'] = BackendModel::getUTCDate();

				// insert the item
				$item['id'] = BackendContactsModel::insert($item);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=added&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}
