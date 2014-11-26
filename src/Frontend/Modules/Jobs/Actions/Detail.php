<?php

namespace Frontend\Modules\Jobs\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Language as FL;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Jobs\Engine\Model as FrontendJobsModel;
use Frontend\Modules\Tags\Engine\Model as FrontendTagsModel;

/**
 * This is the detail-action
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class Detail extends FrontendBaseBlock
{
    private $images;

    /**
     * The job
     *
     * @var	array
     */
    private $record;

    /**
     * The settings
     *
     * @var	array
     */
    private $settings;

    /**
     * The videos
     *
     * @var	array
     */
    private $videos;

    /**
     * The related jobs
     *
     * @var	array
     */
    private $relatedJobs;

    /**
     * Execute the extra
     */
    public function execute()
    {
	parent::execute();

	// hide contentTitle, in the template the title is wrapped with an inverse-option
	$this->tpl->assign('hideContentTitle', true);

	$this->loadTemplate();
	$this->getData();
	$this->parse();
    }

    /**
     * Load the data, don't forget to validate the incoming data
     */
    private function getData()
    {
	// validate incoming parameters
	if($this->URL->getParameter(1) === null) $this->redirect(FrontendNavigation::getURL(404));

	// get by URL
	$this->record = FrontendJobsModel::get($this->URL->getParameter(1));

	// get settings
	$this->settings = FrontendModel::getModuleSettings('Jobs');

	// anything found?
	if(empty($this->record)) $this->redirect(FrontendNavigation::getURL(404));

	// images
	$this->images = FrontendJobsModel::getImages($this->record['id']);

	// videos
	$this->videos = FrontendJobsModel::getVideos($this->record['id']);

	// overwrite URLs
	$this->record['category_full_url'] = FrontendNavigation::getURLForBlock('Jobs', 'Category') . '/' . $this->record['category_url'];
	$this->record['client_full_url'] = FrontendNavigation::getURLForBlock('Jobs', 'Client') . '/' . $this->record['client_url'];
	$this->record['full_url'] = FrontendNavigation::getURLForBlock('Jobs', 'Detail') . '/' . $this->record['url'];

	// get tags
	$this->record['tags'] = FrontendTagsModel::getForItem('Jobs', $this->record['id']);

	// get related jobs
	$this->relatedJobs = FrontendJobsModel::getRelatedJobs($this->record['id'], $this->URL->getParameter(1));
    }

    /**
     * Parse the data into the template
     */
    private function parse()
    {
	// add to breadcrumb
	if($this->settings['allow_multiple_categories']) $this->breadcrumb->addElement($this->record['category_title'], $this->record['category_full_url']);
	$this->breadcrumb->addElement($this->record['title']);

	// set meta
	if($this->settings['allow_multiple_categories']) $this->header->setPageTitle($this->record['category_title']);
	$this->header->setPageTitle($this->record['title']);

	$this->addJS('jquery.colorbox-min.js');
	$this->addJS('detail.js');

	$this->addJSData('next', FL::getLabel('Next'));
	$this->addJSData('previous', FL::getLabel('Previous'));
	$this->addJSData('current', FL::getLabel('CurrentImage'));
	$this->addJSData('close', FL::getLabel('Close'));
	$this->addJSData('xhrError', FL::getLabel('AjaxError'));
	$this->addJSData('imgError', FL::getLabel('ImageError'));

	$this->addCSS('colorbox.css');

	// assign job
	$this->tpl->assign('item', $this->record);
	$this->tpl->assign('images', $this->images);
	$this->tpl->assign('related', $this->relatedJobs);
	$this->tpl->assign('videos', $this->videos);

	// assign settings
	$this->tpl->assign('settings', $this->settings);
    }
}
