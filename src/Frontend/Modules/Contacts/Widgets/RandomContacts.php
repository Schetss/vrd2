<?php

/**
 * Show a random contact.
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 */
class FrontendContactsWidgetRandomContact extends FrontendBaseWidget
{
	/**
	 * Execute the extra.
	 */
	public function execute()
	{
		// call parent
		parent::execute();

		// load template
		$this->loadTemplate();

		// parse
		$this->parse();
	}

	/**
	 * Parse the template.
	 */
	private function parse()
	{
		// assign the random contact
		if(Spoon::exists('usedRandomIds')) $usedRandomIds = (array) Spoon::get('usedRandomIds');
		else $usedRandomIds = array();
		$randomContact = FrontendContactsModel::getRandom($usedRandomIds);
		$this->tpl->assign('widgetContactsRandomContact', $randomContact);
		if(!empty($randomContact['id'])) $usedRandomIds[] = $randomContact['id'];
		Spoon::set('usedRandomIds', $usedRandomIds);
	}
}
