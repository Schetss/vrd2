<?php

/**
 * Display a form to edit an existing contact.
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 * @author Matthias Mullie <matthias@mullie.eu>
 */
class BackendContactsEdit extends BackendBaseActionEdit
{
	/**
	 * Execute the action.
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the contact exist
		if($this->id !== null && BackendContactsModel::exists($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get data
			$this->getData();

			// load form
			$this->loadForm();

			// validate form
			$this->validateForm();

			// parse
			$this->parse();

			// display
			$this->display();
		}

		// no contact found
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}

	/**
	 * Get the data.
	 */
	private function getData()
	{
		$this->record = BackendContactsModel::get($this->id);
	}

	/**
	 * Load the form
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit');

		// set hidden values
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// create elements
		$this->frm->addText('name', $this->record['name'], 255, 'inputText title', 'inputTextError, title');
		$this->frm->addEditor('contact', $this->record['contact']);
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
	}

	/**
	 * Parse the form.
	 */
	protected function parse()
	{
		// call parent
		parent::parse();

		// assign fields
		$this->tpl->assign('item', $this->record);
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
				$item['id'] = $this->id;
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['contact'] = $this->frm->getField('contact')->getValue();
				$item['hidden'] = $this->frm->getField('hidden')->getValue();
				$item['user_id'] = BackendAuthentication::getUser()->getUserId();
				$item['edited_on'] = BackendModel::getUTCDate();

				// update the contact
				BackendContactsModel::update($item);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_edit', array('item' => $item));

				// everything has been saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=edited&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}
