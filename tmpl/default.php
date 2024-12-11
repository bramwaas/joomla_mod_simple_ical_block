<?php
/**
 * @version $Id: default.php
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2022 -2024 simpleicalblock, All rights reserved.
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
 * simpleicalblock is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 0.0.0 2022-07-10 first adjustments for J4 convert parameters to array $attributes.
 * 0.0.1 2022-07-25 included display_block function from WP Plugin SimpleicalBlock
 *   replaced $instamce by $attributes, wp_kses ($text, 'post')  by strip_tags  ($text, $allowed_tags)
 *   changed wp_date in date (maybe date_default_timezone_set(<local timezone> is needed but that is already in the code if not we can remove it);
 *   replaced wp get_option('timezone_string') by Factory::getApplication()->get('offset') or (deprecated) Factory::Getconfig()->offset 
 *   replaced wp sanitize_html_class by copy in SimpleicalblockHelper
 *   removed wp esc_attr from sanitizing $e->uid
 *   removed checks isset on attributes because that is already done before.
 *   replaced date( with Date()->format where translation is necessary.
 * 2.0.1 back to static functions getData() and fetch() only instantiate object in fetch when parsing must be done (like it always was in WP)  
 * 2.1.0 add calendar class to list-group-item
 *   add htmlspecialchars() to summary, description and location when not 'allowhtml', replacing similar code from IcsParser
 * 2.1.3 use select 'layout' in stead of 'start with summary' to create more lay-out options.
 * 2.1.4 add closing HTML output after eventlist or when no events are available.    
 * 2.2.1 20240123 don't display description line when excerpt-length = 0
 * 2.3.0 Moved display_block() and $allowed_tags to SimpleicalblockHelper class to accommodate calls from REST service
 * 2.5.2 rename SimpleicalblockHelper to SimpleicalHelper 
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');

use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalHelper;

$attributes = SimpleicalHelper::render_attributes( $params->toArray());

echo '<div id="' . $attributes['anchorId']  .'" data-sib-id="' . $attributes['sibid'] . '" ' . ' class="simple_ical_block" >';
SimpleicalHelper::display_block($attributes);
echo '</div>';

