<?php
/**
 * @version $Id: helper.php 
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2024 -2024 A.H.C. Waasdorp, All rights reserved.
 * @license GNU General Public License version 3 or later
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer A.H.C. Waasdorp
 * 2.4.0 added helper.php for mod_simple_ical_block to accomodate simple Ajax/Rest in module
 *  call : /index.php?option=com_ajax&module=simple_ical_block&method=get&format=json&sibid={sibid}&tzid_ui={tzid_ui} 
 */
//namespace WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper;
// no direct access
defined('_JEXEC') or die ('Restricted access');
use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalblockHelper; // as ModSimpleicalblockHelper;

/**
 * extension of SimpleicalblockHelper to conform to com_ajax naming conventions
 *
 * @since  2.4.0
 */
class ModSimpleicalblockHelper extends SimpleicalblockHelper
{

}