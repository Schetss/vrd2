<?php

namespace Frontend\Modules\Immo\Actions;

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
use Frontend\Modules\Immo\Engine\Model as FrontendImmoModel;

/**
 * This is the index-action
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@webleads.nl>
 */
class Index extends FrontendBaseBlock
{
    /**
     * @var    array
     */
    private $categories, $immo, $clients;

    /**
     * The pagination array
     * It will hold all needed parameters, some of them need initialization.
     *
     * @var    array
     */
    protected $pagination = array('limit' => 10, 'offset' => 0, 'requested_page' => 1, 'num_items' => null, 'num_pages' => null);

    /**
     * Execute the extra
     */
    public function execute()
    {
        parent::execute();

        $this->getData();
        $this->loadTemplate();
        $this->parse();
    }

    /**
     * Load the data, don't forget to validate the incoming data
     */
    private function getData()
    {
        // requested page
        $requestedPage = $this->URL->getParameter('page', 'int', 1);

        // set URL and limit
        $this->pagination['url'] = FrontendNavigation::getURLForBlock('Immo');
        $this->pagination['limit'] = FrontendModel::getModuleSetting('Immo', 'overview_num_items', 10);

        // populate count fields in pagination
        $this->pagination['num_items'] = FrontendImmoModel::getAllCount();
        $this->pagination['num_pages'] = (int) ceil($this->pagination['num_items'] / $this->pagination['limit']);

        // num pages is always equal to at least 1
        if($this->pagination['num_pages'] == 0) $this->pagination['num_pages'] = 1;

        // redirect if the request page doesn't exist
        if($requestedPage > $this->pagination['num_pages'] || $requestedPage < 1) $this->redirect(FrontendNavigation::getURL(404));

        // populate calculated fields in pagination
        $this->pagination['requested_page'] = $requestedPage;
        $this->pagination['offset'] = ($this->pagination['requested_page'] * $this->pagination['limit']) - $this->pagination['limit'];

        // get immo
        $this->immo = FrontendImmoModel::getAll($this->pagination['limit'], $this->pagination['offset']);

        // get clients
        $this->clients = FrontendImmoModel::getClients();

        // get categories
        $this->categories = FrontendImmoModel::getCategories();
    }

    /**
     * Parse the data into the template
     */
    private function parse()
    {
        $this->tpl->assign('categories', (array)$this->categories);
        $this->tpl->assign('clients', (array)$this->clients);
        $this->tpl->assign('immo', (array)$this->immo);
        $this->tpl->assign('allowMultipleCategories', FrontendModel::getModuleSetting('Immo', 'allow_multiple_categories', true));

        $this->parsePagination();
    }
}
