<?php

namespace Frontend\Modules\Contact\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Modules\Contact\Engine\Model as FrontendContactModel;

/**
 * This is the Index-action, it will display the overview of contact posts
 *
 * @author Jesse Dobbelaere <jesse@dobbelaere-ae.be>
 */
class Index extends FrontendBaseBlock
{
    /**
     * The record data
     *
     * @var array
     */
    private $record;

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->loadData();
        $this->parse();
    }

    /**
     * Load the data
     */
    protected function loadData()
    {
        $this->record = FrontendContactModel::getAll();
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        $this->tpl->assign('items', $this->record);
    }
}
