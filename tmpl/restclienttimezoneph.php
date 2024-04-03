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
 * 2.4.0 Created as placeholder for REST service
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalblockHelper;

$attributes = SimpleicalblockHelper::render_attributes( $params->toArray());

echo '<div id="' . $attributes['anchorId']  .'" data-sib-id="' . $attributes['sibid'] 
//. ((empty($attributes['title'])) ? '" data-sib-notitle="true' : '')
. '" data-sib-st="0-start" class="simple_ical_block" >';
//SimpleicalblockHelper::display_block($attributes);
echo '<p>' . "MOD_SIMPLEICALBLOCK_PROCESSING" . '</p>';
echo '</div>';

