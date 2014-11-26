<?php

namespace Backend\Modules\Immo\Actions;

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
use Backend\Modules\Immo\Engine\Model as BackendImmoModel;
 
/**
 * This is the add action, it will display a form to add an video to a immo.
 *
 * @author Tim van Wolfswinkel <tim@reclame-mediabureau.nl>
 */
class AddVideo extends BackendBaseActionAdd
{
	/**
	 * The pro record
	 *
	 * @var	array
	 */
	private $immo;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('immo_id', 'int');
        
		if($this->id !== null && BackendImmoModel::exists($this->id))
		{
			parent::execute();

			$this->getData();
			$this->loadForm();
			$this->validateForm();
			$this->parse();
			$this->display();
		}
        
		// the immo does not exist
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}

	/**
	 * Get the necessary data
	 */
	private function getData()
	{
		$this->immo = BackendImmoModel::get($this->getParameter('immo_id', 'int'));
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

		$this->tpl->assign('immo', $this->immo);
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
				$item['immo_id'] = $this->immo['id'];
				$item['title'] = $this->frm->getField('title')->getValue();
				$item['embedded_url'] = $this->frm->getField('video')->getValue();
				$item['sequence'] = BackendImmoModel::getMaximumVideosSequence($item['immo_id'])+1;

				// save the item
				$item['id'] = BackendImmoModel::saveVideo($item);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_video', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('media') . '&immo_id=' . $item['immo_id'] . '&report=added&var=' . urlencode($item['title']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}
