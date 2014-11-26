<?php

/**
 * All model functions for the contacts module.
 *
 * @author Jan Moesen <jan.moesen@netlash.com>
 * @author Matthias Mullie <matthias@mullie.eu>
 */
class BackendContactsModel
{
	/**
	 * Overview of the items.
	 *
	 * @var	string
	 */
	const QRY_BROWSE =
		'SELECT id, name, sequence
	     FROM contacts
	     WHERE language = ?
	     ORDER BY sequence';

	/**
	 * Delete a contact.
	 *
	 * @param int $id The id of the contact to delete.
	 */
	public static function delete($id)
	{
		BackendModel::getContainer()->get('database')->delete('contacts', 'id = ?', array((int) $id));
	}

	/**
	 * Does the contact exist?
	 *
	 * @param int $id The id of the contact to check for existence.
	 * @return bool
	 */
	public static function exists($id)
	{
		return (bool) BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(id)
			 FROM contacts
			 WHERE id = ?',
			array((int) $id)
		);
	}

	/**
	 * Get all data for the contact with the given ID.
	 *
	 * @param int $id The id for the contact to get.
	 * @return array
	 */
	public static function get($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT *, UNIX_TIMESTAMP(created_on) AS created_on, UNIX_TIMESTAMP(edited_on) AS edited_on
		     FROM contacts
		     WHERE id = ?
		     LIMIT 1',
			array((int) $id)
		);
	}

	/**
	 * Get the max sequence id for a contact
	 *
	 * @return int
	 */
	public static function getMaximumSequence()
	{
		return (int) BackendModel::getContainer()->get('database')->getVar(
			'SELECT MAX(sequence)
			 FROM contacts'
		);
	}

	/**
	 * Add a new contact.
	 *
	 * @param array $item The data to insert.
	 * @return int The ID of the newly inserted contact.
	 */
	public static function insert(array $item)
	{
		return BackendModel::getContainer()->get('database')->insert('contacts', $item);
	}

	/**
	 * Update an existing contact.
	 *
	 * @param array $item The new data.
	 * @return int
	 */
	public static function update(array $item)
	{
		return BackendModel::getContainer()->get('database')->update('contacts', $item, 'id = ?', array((int) $item['id']));
	}
}
