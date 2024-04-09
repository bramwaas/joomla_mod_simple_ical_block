<?php
/**
 * @version $Id: rest_ph.php
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2024 -2024 simpleicalblock, All rights reserved.
 * @license GNU General Public License version 3 or later
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer A.H.C. Waasdorp
 *
 *
 * simpleicalblock is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * 2.4.0 Created as placeholder for Ajax (or Rest) service
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalblockHelper;
$maid = $app->getMenu()->getActive()->id;
$maid = (empty($maid)) ? '' : $maid;
$wa  = $document->getWebAssetManager();

$wa->registerAndUseScript('simple-ical-block-view.js', 'mod_simple_ical_block/simple-ical-block-view.js', ['version'=>'2.4.0'],  ['defer' => TRUE],[]);

$wa->addInlineScript(
    '(window.simpleIcalBlock=window.simpleIcalBlock || {}).restRoot = "' . Uri::root() . 'index.php?option=com_ajax&Itemid=' . $maid . '"',
     ['position' => 'before', 'name' => 'define.restRoot'], [],[]
    );
//$wa->useScript('simple-ical-block-view.js');
//$wa->usePreset('ps.mod_simple_ical_block');

$attributes = SimpleicalblockHelper::render_attributes( $params->toArray());

echo '<div id="' . $attributes['anchorId']  .'" data-sib-id="' . $attributes['sibid']
. '" data-sib-st="0-start" class="simple_ical_block" >';
echo '<p>' . Text::_('MOD_SIMPLEICALBLOCK_PROCESSING') . '</p>';
echo '</div>';

