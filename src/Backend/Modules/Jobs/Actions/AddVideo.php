<?php

namespace Backend\Modules\Jobs\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Jobs\Engine\Model as BackendJobsModel;
 
/**
 * This is the add action, it will display a form to add an video to a job.
 *
 * @author Tim van Wolfswinkel <tim@reclame-mediabureau.nl>
 */
class AddVideo extends BackendBaseActionAdd
{
	/**
	 * The job record
	 *
	 * @var	array
	 */
	private $job;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('job_id', 'int');
        
		if($this->id !== null && BackendJobsModel::exists($this->id))
		{
			parent::execute();

			$this->getData();
			$this->loadForm();
			$this->validateForm();
			$this->parse();
			$this->display();
		}
        
		// the job does not exist
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}

	/**
	 * Get the necessary data
	 */
	private function getData()
	{
		$this->job = BackendJobsModel::get($this->getParameter('job_id', 'int'));
	}

	/**
	 * Load the form
	 */
	private function loadForm()
	{
		$this->frm = new BackendForm('addVideo');
		$this->frm->addText('title');
		$this->frm->addTextArea('video');
	}

	/**
	 * Parses stuff into the template
	 */
	protected function parse()
	{
		parent::parse();

		$this->tpl->assign('job', $this->job);
	}

	/**
	 * Validate the form
	 */
	private function validateForm()
	{
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('title')->isFilled(BL::err('NameIsRequired'));
			$this->frm->getField('video')->isFilled(BL::err('FieldIsRequired'));
			        
			// no errors?
			if($this->frm->isCorrect())
			{
				// build video record to insert
				$item['job_id'] = $this->job['id'];
				$item['title'] = $this->frm->getField('title')->getValue();
				$item['embedded_url'] = $this->frm->getField('video')->getValue();
				$item['sequence'] = BackendJobsModel::getMaximumVideosSequence($item['job_id'])+1;

				// save the item
				$item['id'] = BackendJobsModel::saveVideo($item);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_video', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('media') . '&job_id=' . $item['job_id'] . '&report=added&var=' . urlencode($item['title']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}