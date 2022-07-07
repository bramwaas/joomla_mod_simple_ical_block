<?php
/**
 * @package     simple_ical_block
 * @subpackage  mod_simple_ical_block  Module
 * @copyright Copyright (C) 2022 - 2022 AHC Waasdorp, All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer AHC Waasdorp
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

$joomlaverge4 = (version_compare(JVERSION, '4.0', '>='));
$app = Factory::getApplication();
$document = Factory::getDocument();
$mid = $module->id;
$direction = $document->direction;
$asset_dir =  "media/mod_simple_ical_block/";

require ModuleHelper::getLayoutPath('mod_simple_ical_block', $params->get('layout','default'));
