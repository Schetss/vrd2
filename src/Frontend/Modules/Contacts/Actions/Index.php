<?php

/**
 * Show all contacts.
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 */
class FrontendContactsIndex extends FrontendBaseBlock
{
	/**
	 * Execute the extra.
	 */
	public function execute()
	{
		// call the parent
		parent::execute();

		// load template
		$this->loadTemplate();

		// parse
		$this->parse();
	}

	/**
	 * Parse the data and compile the template.
	 */
	private function parse()
	{
		// assign the contacts
		$this->tpl->assign('contactsItems', FrontendContactsModel::getAll());
	}
}
