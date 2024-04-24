<?php

/**
 * @package     simpleicalblock
 * @subpackage  mod_simple_ical_block
 *
 * @copyright   Copyright (C) 2022 -2024 A.H.C. Waasdorp, All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WaasdorpSoekhan\Module\Simpleicalblock\Site\Dispatcher;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher class for mod_simple_ical_block
 *
 * @since  2.4.1
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     * elements of $data will be extracted to variables (with key as name) before layout template is included. 
     * So these variables are available for the layout templates
     * (from parent 'module', app', 'input', 'params', 'template')
     *
     * @return  array
     *
     * @since   2.4.1
     */
    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        $data['params']->set('sibid', $data['module']->id);
        $data['params']->set('clear_cache_now', FALSE); // only clear transient on save in admin.
        $data['direction'] = $data['app']->getDocument()->direction;
        $data['wa'] = $data['app']->getDocument()->getWebAssetManager();
        $data['wr'] = $data['wa']->getRegistry();
        $data['wr']->addRegistryFile('media/mod_simple_ical_block/joomla.asset.json');
 
        return $data;
    }
}
