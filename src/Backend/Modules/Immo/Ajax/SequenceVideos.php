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
 * Reorder images
 *
 * @author Tim van Wolfswinkel <tim@reclame-mediabureau.nl>
 * @author Stijn Schetss <stijn@schetss.be>
 */
class SequenceVideos extends BackendBaseAJAXAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();

		//die('test');
		
		// get parameters
		$newIdSequence = trim(SpoonFilter::getPostValue('new_id_sequence', null, '', 'string'));

		// list id
		$ids = (array) explode(',', rtrim($newIdSequence, ','));

		// loop id's and set new sequence
		foreach($ids as $i => $id)
		{
			// build item
			$item['id'] = (int) $id;

			// change sequence
			$item['sequence'] = $i + 1;

			// update sequence
			BackendImmoModel::updateVideo($item);
		}

		// success output
		$this->output(self::OK, null, 'sequence updated');
	}
}
