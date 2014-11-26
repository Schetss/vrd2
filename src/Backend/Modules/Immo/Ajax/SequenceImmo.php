<?php

namespace Backend\Modules\Immo\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Modules\Immo\Engine\Model as BackendImmoModel;

/**
 * Reorder immo
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class SequenceImmo extends BackendBaseAJAXAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
				
		$immoId = \SpoonFilter::getPostValue('immoId', null, '', 'int');
		$fromCategoryId = \SpoonFilter::getPostValue('fromCategoryId', null, '', 'int');
		$toCategoryId = \SpoonFilter::getPostValue('toCategoryId', null, '', 'int');
		$fromCategorySequence = \SpoonFilter::getPostValue('fromCategorySequence', null, '', 'string');
		$toCategorySequence = \SpoonFilter::getPostValue('toCategorySequence', null, '', 'string');

		// invalid immo id
		if(!BackendImmoModel::exists($immoId)) $this->output(self::BAD_REQUEST, null, 'immo does not exist');

		// list ids
		$fromCategorySequence = (array) explode(',', ltrim($fromCategorySequence, ','));
		$toCategorySequence = (array) explode(',', ltrim($toCategorySequence, ','));

		// is the immo moved to a new category?
		if($fromCategoryId != $toCategoryId)
		{
			$item['id'] = $immoId;
			$item['category_id'] = $toCategoryId;

			BackendImmoModel::update($item);

			// loop id's and set new sequence
			foreach($toCategorySequence as $i => $id)
			{
				$item = array();
				$item['id'] = (int) $id;
				$item['sequence'] = $i + 1;

				// update sequence if the item exists
				if(BackendImmoModel::exists($item['id'])) BackendImmoModel::update($item);
			}
		}

		// loop id's and set new sequence
		foreach($fromCategorySequence as $i => $id)
		{
			$item['id'] = (int) $id;
			$item['sequence'] = $i + 1;

			// update sequence if the item exists
			if(BackendImmoModel::exists($item['id'])) BackendImmoModel::update($item);
		}

		// success output
		$this->output(self::OK, null, 'sequence updated');
	}
}
