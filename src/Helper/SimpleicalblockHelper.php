<?php
/**
 * @version $Id: simpleicalblock.php 
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2022 -2023 A.H.C. Waasdorp, All rights reserved.
 * @license GNU General Public License version 3 or later
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer A.H.C. Waasdorp
 *
 *
 * wsacarousel is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * wsacarousel is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with simpleicalblock. If not, see <http://www.gnu.org/licenses/>.
 * 0.0.3 replaced defaults for dateformats by "", because we also use empty format to skip the field.
 *  Added space to sanitize html class because it it also used for more classes.
 * 0.0.4 removed selfmade transient functions because we now use Joomla standard cache type output to replace wp_transient. 
 * 2.1.4 add closing HTML output after eventlist or when no events are available.    
 */
namespace WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper;
// no direct access
defined('_JEXEC') or die ('Restricted access');


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Helper for mod_simpleicalblock
 *
 * @since  1.0
 */
class SimpleicalblockHelper
{
    /*
     * @var array allowed tags for summary
     */
    private static $allowed_tags_sum = ['a', 'b', 'div', 'h4', 'h5', 'h6', 'i', 'span', 'strong', 'u'] ;
    
    /**
     * copied from WP sanitize_html_class, and added space as allowed character to accomadate multiple classes in one string.
     * Strips the string down to A-Z,a-z,0-9,_,-. If this results in an empty string then it will return the alternative value supplied.
     * 
     * @param string $class
     * @param string $fallback
     * @return string sanitized class or fallback.
     */
    static function sanitize_html_class( $class, $fallback = '' ) {
        // Strip out any %-encoded octets.
        $sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class );
        
        // Limit to A-Z, ' ', a-z, 0-9, '_', '-'.
        $sanitized = preg_replace( '/[^A-Z a-z0-9_-]/', '', $sanitized );
        
        if ( '' === $sanitized && $fallback ) {
            return  $fallback;
        }
        return $sanitized;
    }
    /**
     * Merge block attributes with defaults to be sure they exist is necesary.
     *
     * @param array $block_attributes the module params object presented as array ($params->toArray())
     * @return array attributes from parameters merged with default  attributes. 
     */
    static function render_attributes($block_attributes) {
        
        $block_attributes =  array_merge(
            array(
                'blockid' => 'AZ',
                'calendar_id' => '',
                'event_count' => 10,
                'event_period' => 92,
                'transient_time' => 60,
                'sib_layout' => 3,
                'dateformat_lg' => '',
                'dateformat_lgend' => '',
                'tag_sum' => 'a',
                'dateformat_tsum' => '',
                'dateformat_tsend' => '',
                'dateformat_tstart' => '',
                'dateformat_tend' => '',
                'excerptlength' => '',
                'suffix_lg_class' => '',
                'suffix_lgi_class' => ' py-0',
                'suffix_lgia_class' => '',
                'after_events' => '',
                'no_events' => '',
                'allowhtml' => false,
                'clear_cache_now' => false,
                //               'align'=>'',
                'className'=>'',
                'anchorId'=> '',
            ),
            $block_attributes
            );
        if (!in_array($block_attributes['tag_sum'], self::$allowed_tags_sum)) $block_attributes['tag_sum'] = 'a';
        $block_attributes['suffix_lg_class'] = self::sanitize_html_class($block_attributes['suffix_lg_class']);
        $block_attributes['suffix_lgi_class'] = self::sanitize_html_class($block_attributes['suffix_lgi_class']);
        $block_attributes['suffix_lgia_class'] = self::sanitize_html_class($block_attributes['suffix_lgia_class']);
        $block_attributes['anchorId'] = self::sanitize_html_class($block_attributes['anchorId'], 'b' . $block_attributes['blockid']);
        
       
       return $block_attributes;
    }
}
