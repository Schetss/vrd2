<?php

/**
 * Installer for the contacts module
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 */
class ContactsInstaller extends ModuleInstaller
{
	/**
	 * Install the module.
	 */
	public function install()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// add 'contacts' as a module
		$this->addModule('contacts', 'The contacts module.');

		// import locale
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');

		// module rights
		$this->setModuleRights(1, 'contacts');

		// action rights
		$this->setActionRights(1, 'contacts', 'add');
		$this->setActionRights(1, 'contacts', 'delete');
		$this->setActionRights(1, 'contacts', 'edit');
		$this->setActionRights(1, 'contacts', 'index');
		$this->setActionRights(1, 'contacts', 'sequence');

		// set navigation
		$navigationModulesId = $this->setNavigation(null, 'Modules');
		$this->setNavigation($navigationModulesId, 'Contacts', 'contacts/index', array('contacts/add', 'contacts/edit'));

		// add extra's
		$this->insertExtra('contacts', 'block', 'AllContacts', 'all_contacts', null, 'N');
		$this->insertExtra('contacts', 'widget', 'RandomContact', 'random_contact', null, 'N');
	}
}
