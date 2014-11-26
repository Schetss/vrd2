<?php

namespace Backend\Modules\Jobs\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Modules\Jobs\Engine\Model as BackendJobsModel;

/**
 * Reorder jobs
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class SequenceJobs extends BackendBaseAJAXAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
				
		$jobId = \SpoonFilter::getPostValue('jobId', null, '', 'int');
		$fromCategoryId = \SpoonFilter::getPostValue('fromCategoryId', null, '', 'int');
		$toCategoryId = \SpoonFilter::getPostValue('toCategoryId', null, '', 'int');
		$fromCategorySequence = \SpoonFilter::getPostValue('fromCategorySequence', null, '', 'string');
		$toCategorySequence = \SpoonFilter::getPostValue('toCategorySequence', null, '', 'string');

		// invalid job id
		if(!BackendJobsModel::exists($jobId)) $this->output(self::BAD_REQUEST, null, 'job does not exist');

		// list ids
		$fromCategorySequence = (array) explode(',', ltrim($fromCategorySequence, ','));
		$toCategorySequence = (array) explode(',', ltrim($toCategorySequence, ','));

		// is the job moved to a new category?
		if($fromCategoryId != $toCategoryId)
		{
			$item['id'] = $jobId;
			$item['category_id'] = $toCategoryId;

			BackendJobsModel::update($item);

			// loop id's and set new sequence
			foreach($toCategorySequence as $i => $id)
			{
				$item = array();
				$item['id'] = (int) $id;
				$item['sequence'] = $i + 1;

				// update sequence if the item exists
				if(BackendJobsModel::exists($item['id'])) BackendJobsModel::update($item);
			}
		}

		// loop id's and set new sequence
		foreach($fromCategorySequence as $i => $id)
		{
			$item['id'] = (int) $id;
			$item['sequence'] = $i + 1;

			// update sequence if the item exists
			if(BackendJobsModel::exists($item['id'])) BackendJobsModel::update($item);
		}

		// success output
		$this->output(self::OK, null, 'sequence updated');
	}
}
