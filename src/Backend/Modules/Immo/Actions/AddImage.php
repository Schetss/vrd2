<?php

namespace Backend\Modules\Immo\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\File\File;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Immo\Engine\Model as BackendImmoModel;
use Backend\Modules\Immo\Engine\Helper as BackendImmoHelper;
 
/**
 * This is the add action, it will display a form to add an image to a immo.
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 * @author Stijn Schetss <stijn@schetss.be>
 */
class AddImage extends BackendBaseActionAdd
{
	/**
	 * The immo record
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
		$this->frm = new BackendForm('addImage');
		$this->frm->addText('title');
		$this->frm->addImage('image');
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
			$image = $this->frm->getField('image');

			$this->frm->getField('title')->isFilled(BL::err('NameIsRequired'));
			$image->isFilled(BL::err('FieldIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// build image record to insert
				$item['immo_id'] = $this->immo['id'];
				$item['title'] = $this->frm->getField('title')->getValue();

				// set files path for this record
				$path = FRONTEND_FILES_PATH . '/' . $this->module . '/' . $item['immo_id'];

				// set formats
				$formats = array();
				$formats[] = array('size' => '64x64', 'allow_enlargement' => true, 'force_aspect_ratio' => false);
				$formats[] = array('size' => '128x128', 'allow_enlargement' => true, 'force_aspect_ratio' => false);
				$formats[] = array('size' => BackendModel::getModuleSetting($this->URL->getModule(), 'width1') . 'x' . BackendModel::getModuleSetting($this->URL->getModule(), 'height1'), 'allow_enlargement' => BackendModel::getModuleSetting($this->URL->getModule(), 'allow_enlargment1'), 'force_aspect_ratio' => BackendModel::getModuleSetting($this->URL->getModule(), 'force_aspect_ratio1'));
				$formats[] = array('size' => BackendModel::getModuleSetting($this->URL->getModule(), 'width2') . 'x' . BackendModel::getModuleSetting($this->URL->getModule(), 'height2'), 'allow_enlargement' => BackendModel::getModuleSetting($this->URL->getModule(), 'allow_enlargment2'), 'force_aspect_ratio' => BackendModel::getModuleSetting($this->URL->getModule(), 'force_aspect_ratio2'));
				$formats[] = array('size' => BackendModel::getModuleSetting($this->URL->getModule(), 'width3') . 'x' . BackendModel::getModuleSetting($this->URL->getModule(), 'height3'), 'allow_enlargement' => BackendModel::getModuleSetting($this->URL->getModule(), 'allow_enlargment3'), 'force_aspect_ratio' => BackendModel::getModuleSetting($this->URL->getModule(), 'force_aspect_ratio3'));
				//$formats[] = array('size' => BackendModel::getModuleSetting($this->URL->getModule(), 'width4') . 'x' . BackendModel::getModuleSetting($this->URL->getModule(), 'height4'), 'allow_enlargement' => BackendModel::getModuleSetting($this->URL->getModule(), 'allow_enlargment4'), 'force_aspect_ratio' => BackendModel::getModuleSetting($this->URL->getModule(), 'force_aspect_ratio4'));

				// set the filename
				$item['filename'] = time() . '.' . $image->getExtension();
				$item['sequence'] = BackendImmoModel::getMaximumImagesSequence($item['immo_id'])+1;

				// add images
				BackendImmoHelper::addImages($image, $path, $item['filename'], $formats);

				// save the item
				$item['id'] = BackendImmoModel::saveImage($item);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_image', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('media') . '&immo_id=' . $item['immo_id'] . '&report=added&var=' . urlencode($item['title']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}
