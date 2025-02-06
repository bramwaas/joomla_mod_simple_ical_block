<?php
/**
 * @version $Id: SimpleicalHelper.php 
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2022 -2025 A.H.C. Waasdorp, All rights reserved.
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
 *
 * You should have received a copy of the GNU General Public License
 * along with simpleicalblock. If not, see <http://www.gnu.org/licenses/>.
 * 0.0.3 replaced defaults for dateformats by "", because we also use empty format to skip the field.
 *  Added space to sanitize html class because it it also used for more classes.
 * 0.0.4 removed selfmade transient functions because we now use Joomla standard cache type output to replace wp_transient. 
 * 2.1.4 add closing HTML output after eventlist or when no events are available. 
 * 2.2.1 20240123 don't display description line when excerpt-length = 0
 * 2.3.0 Moved display_block() and $allowed_tags to this class to accommodate calls from Ajax/REST service
 * 2.4.0 added getAjax function. str_replace('Etc/GMT ','Etc/GMT+' for some UTC-... timezonesettings. 
 * 2.5.0 Add filter and display support for categories. copied sanitize_html_class to ... clss for multiple classses and
 *  removed allowed space from original (to use for one class); replace strip_tags(... allowed_html) by InputFilter::clean(..., 'HTML')
 *  to add a liitle more security by also filtering attributes like wp_kses make allowed_html and allowed _attrs  more
 *  comparable with wp_kses_allowed_html  
 * 2.5.2 renamed SimpleicalblockHelper to SimpleicalHelper and moved functions common with WP to top
 * 2.6.0 improve security by following Wordpress Plugin Check recommendations. 
 * Replace echo by $secho in &$secho param a.o. in display_block, to simplify escaping output by replacing multiple echoes by one.
 * clean all echoed output to safe HTML 
 *  
 */
namespace WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper;
// no direct access
defined('_JEXEC') or die ('Restricted access');


use Joomla\CMS\Date\Date as Jdate;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Response\JsonResponse;
use Joomla\Filter\InputFilter;
use WaasdorpSoekhan\Module\Simpleicalblock\Site\IcsParser;

/**
 * Helper for mod_simpleicalblock
 *
 * @since  1.0
 */
class SimpleicalHelper
{
    const SIB_ATTR = 'simple_ical_block_attrs';
    /**
     * tags allowed for summary
     *
     * @var array
     */
    static $allowed_tags_sum = [
        'a',
        'b',
        'div',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'i',
        'span',
        'strong',
        'u'
    ];
    /*
     * @var array allowed tags for text-output
     */
    static $allowed_tags = ['a','abbr', 'acronym', 'address','area','article', 'aside','audio',
        'b','big','blockquote', 'br','button', 'caption','cite','code','col', 'del',
        'details', 'div',
        'em',
        'fieldset',
        'figcaption',
        'figure',
        'footer',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'hr',
        'i',
        'img',
        'li',
        'label',
        'legend',
        'ol',
        'p',
        'q',
        's',
        'section',
        'small',
        'span',
        'strike',
        'strong',
        'u',
        'ul'
    ];
    /*
     * @var array allowed attributes for html-text-output
     */
    static $allowed_attrs = [
        'aria-controls',
        'aria-current',
        'aria-describedby',
        'aria-details',
        'aria-expanded',
        'aria-hidden',
        'aria-label',
        'aria-labelledby',
        'aria-live',
        'class',
        'cite',
        'data-bs-animation',
        'data-bs-container',
        'data-bs-content',
        'data-bs-delay',
        'data-bs-dismiss',
        'data-bs-html',
        'data-bs-offset',
        'data-bs-offset-bottom',
        'data-bs-offset-top',
        'data-bs-placement',
        'data-bs-selector',
        'data-bs-spy',
        'data-bs-target',
        'data-bs-template',
        'data-bs-toggle',
        'data-bs-title',
        'data-bs-trigger',
        'data-bs-viewport',
        'data-toggle',
        'data-target',
        'data-sib-id',
        'data-sib-st',
        'datetime',
        'dir',
        'hidden',
        'href',
        'id',
        'lang',
        'role',
        'style',
        'title',
        'type',
        'xml:lang'
    ] ;
    /*
     * @var class InputFilter to initialize it only once.
     */
    static $input_fl = null;
    /**
     * default value for block_attributes (or instance)
     *
     * @var array
     */
    static $default_block_attributes = [
        'sibid' => '',
        'calendar_id' => '',
        'event_count' => 10,
        'event_period' => 92,
        'transient_time' => 60,
        'categories_filter_op' => '',
        'categories_filter' => '',
        'categories_display' => '',      
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
        'allowhtml' => false,
        'after_events' => '',
        'no_events' => '',
        'clear_cache_now' => false,
        'period_limits' => '1',
        'tzid_ui' => '',
        'className' => '',
        'anchorId' => '',
        'before_title'  => '<h3 class="widget-title block-title">',
        'after_title'   => '</h3>'
        
    ];
    /**
     * Front-end display of module, block or widget.
     *
     * @see
     *
     * @param array $attributes
     * @param string &$secho (reference to $secho), output to echo in calling function, to simplify escaping output by replacing multiple echoes by one 
     *            Saved attribute/option values from database.
     */
    static function display_block($attributes, &$secho)
    {
//         if (empty(self::$input_fl)) {
//             self::$input_fl = new InputFilter(self::$allowed_tags, self::$allowed_attrs, InputFilter::ONLY_ALLOW_DEFINED_TAGS, InputFilter::ONLY_ALLOW_DEFINED_ATTRIBUTES);
//         }
        $sn = 0;
        try {
            $attributes['tz_ui'] = new \DateTimeZone($attributes['tzid_ui']);
        } catch (\Exception $exc) {}
        if (empty($attributes['tz_ui']))
            try {
                $attributes['tzid_ui'] = str_replace('Etc/GMT ','Etc/GMT+',$attributes['tzid_ui']);
                $attributes['tz_ui'] = new \DateTimeZone($attributes['tzid_ui']);
        } catch (\Exception $exc) {}
        if (empty($attributes['tz_ui']))
            try {
                $attributes['tzid_ui'] = Factory::getApplication()->get('offset');
                $attributes['tz_ui'] = new \DateTimeZone($attributes['tzid_ui']);
        } catch (\Exception $exc) {}
        if (empty($attributes['tz_ui'])) {
            $attributes['tzid_ui'] = 'UTC';
            $attributes['tz_ui'] = new \DateTimeZone('UTC');
        }
        $layout = (isset($attributes['sib_layout'])) ? intval($attributes['sib_layout']) : 3;
        $dflg = (isset($attributes['dateformat_lg'])) ? $attributes['dateformat_lg'] : 'l jS \of F';
        $dflgend = (isset($attributes['dateformat_lgend'])) ? $attributes['dateformat_lgend'] : '';
        $dftsum = (isset($attributes['dateformat_tsum'])) ? $attributes['dateformat_tsum'] : 'G:i ';
        $dftsend = (isset($attributes['dateformat_tsend'])) ? $attributes['dateformat_tsend'] : '';
        $dftstart = (isset($attributes['dateformat_tstart'])) ? $attributes['dateformat_tstart'] : 'G:i';
        $dftend = (isset($attributes['dateformat_tend'])) ? $attributes['dateformat_tend'] : ' - G:i ';
        $excerptlength = (isset($attributes['excerptlength']) && ' ' < trim($attributes['excerptlength']) ) ? (int) $attributes['excerptlength'] : '' ;
        $sflgi = $attributes['suffix_lgi_class'];
        $sflgia = $attributes['suffix_lgia_class'];
        if (empty($attributes['categories_display'])) {
            $cat_disp = false;
        } else {
            $cat_disp = true;
            $cat_sep = '</small>'.$attributes['categories_display'].'<small>';
        }
        if (! in_array($attributes['tag_sum'], self::$allowed_tags_sum))
            $attributes['tag_sum'] = 'a';
            $ipd = IcsParser::getData($attributes);
            $data = $ipd['data'];
            foreach ($ipd['messages'] as $msg) {
                $secho .= '<!-- ' . $msg . ' -->';
            }
            if (!empty($data) && is_array($data)) {
                $secho .= '<ul class="list-group' . $attributes['suffix_lg_class'] . ' simple-ical-widget" > ';
                $curdate = '';
                foreach($data as $e) {
                    $idlist = explode("@", $e->uid );
                    $itemid = 'b' . $attributes['sibid'] . '_' . $idlist[0];
                    $e_dtstart = new Jdate ($e->start);
                    $e_dtstart->setTimezone($attributes['tz_ui']);
                    $e_dtend = new Jdate ($e->end);
                    $e_dtend->setTimezone($attributes['tz_ui']);
                    $e_dtend_1 = new Jdate ($e->end -1);
                    $e_dtend_1->setTimezone($attributes['tz_ui']);
                    $evdate = $e_dtstart->format($dflg, true, true);
                    $ev_class =  ((!empty($e->cal_class)) ? ' ' . self::sanitize_html_clss($e->cal_class): '');
                    $cat_list = '';
                    if (!empty($e->categories)) {
                        $ev_class = $ev_class . ' ' . implode( ' ',
                            array_map( "WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalHelper::sanitize_html_class"
                                , $e->categories ));
                        if ($cat_disp) {
                            $cat_list = '<div class="categories"><small>'
                                . implode($cat_sep,str_replace("\n", '<br>', $e->categories ))
                                . '</small></div>';
                        }
                    }
                    if ( !$attributes['allowhtml']) {
                        if (!empty($e->summary)) $e->summary = htmlspecialchars($e->summary);
                        if (!empty($e->description)) $e->description = htmlspecialchars($e->description);
                        if (!empty($e->location)) $e->location = htmlspecialchars($e->location);
                    }
                    if (date('yz', $e->start) != date('yz', $e->end)) {
                        $evdate = str_replace(array("</div><div>", "</h4><h4>", "</h5><h5>", "</h6><h6>" ), '', $evdate . $e_dtend_1->format($dflgend, true, true));
                    }
                    $evdtsum = (($e->startisdate === false) ? $e_dtstart->format($dftsum, true, true) . $e_dtend->format($dftsend, true, true) : '');
                    if ($layout < 2 && $curdate != $evdate) {
                        if  ($curdate != '') {
                            $secho .= '</ul></li>';
                        }
                        $secho .= '<li class="list-group-item' . $sflgi . $ev_class . ' head">' . '<span class="ical-date">' . ucfirst($evdate) . '</span><ul class="list-group' . $attributes['suffix_lg_class'] . '">';
                    }
                    $secho .= '<li class="list-group-item' . $sflgi . $ev_class . '">';
                    if ($layout == 3 && $curdate != $evdate) {
                        $secho .= '<span class="ical-date">' . ucfirst($evdate) . '</span>' . (('a' == $attributes['tag_sum']) ? '<br>' : '');
                    }
                    $secho .=  '<' . $attributes['tag_sum'] . ' class="ical_summary' . $sflgia . (('a' == $attributes['tag_sum']) ? '" data-toggle="collapse" data-bs-toggle="collapse" href="#' . $itemid . '" aria-expanded="false" aria-controls="' . $itemid . '">' : '">');
                    if ($layout != 2)	{
                        $secho .= $evdtsum;
                    }
                    if(!empty($e->summary)) {
                        $secho .= str_replace("\n", '<br>', $e->summary);
                    }
                    $secho .= '</' . $attributes['tag_sum'] . '>';
                    if ($layout == 2)	{
                        $secho .= '<span>'. $evdate . $evdtsum . '</span>';
                    }
                    $secho .= $cat_list . '<div class="ical_details' . $sflgia . (('a' == $attributes['tag_sum']) ? ' collapse' : '') . '" id="'. $itemid. '">';
                    if(!empty($e->description) && trim($e->description) > '' && $excerptlength !== 0) {
                        if ($excerptlength !== '' && strlen($e->description) > $excerptlength) {$e->description = substr($e->description, 0, $excerptlength + 1);
                        if (rtrim($e->description) !== $e->description) {$e->description = substr($e->description, 0, $excerptlength);}
                        else {if (strrpos($e->description, ' ', max(0,$excerptlength - 10))!== false OR strrpos($e->description, "\n", max(0,$excerptlength - 10))!== false )
                        {$e->description = substr($e->description, 0, max(strrpos($e->description, "\n", max(0,$excerptlength - 10)),strrpos($e->description, ' ', max(0,$excerptlength - 10))));
                        } else
                        {$e->description = substr($e->description, 0, $excerptlength);}
                        }
                        }
                        $e->description = str_replace("\n", '<br>', $e->description);
                        $secho .= '<span class="dsc">'. $e->description. ((strrpos($e->description, '<br>') === (strlen($e->description) - 4)) ? '' : '<br>'). '</span>';
                    }
                    if ($e->startisdate === false && date('yz', $e->start) === date('yz', $e->end))	{
                        $secho .= '<span class="time">' . ($e_dtstart->format($dftstart, true, true)).
                        '</span><span class="time">' . ($e_dtend->format($dftend, true, true)). '</span> ' ;
                    } else {
                        $secho .= '';
                    }
                    if(!empty($e->location)) {
                        $secho .= '<span class="location">'. str_replace("\n", '<br>', $e->location). '</span>';
                    }
                    $secho .= '</div></li>';
                    $curdate =  $evdate;
                }
                if ($layout < 2 ) {
                    $secho .= '</ul></li>';
            }
                $secho .= '</ul>';
                $secho .= $attributes['after_events'];
            } else {
                $secho .= $attributes['no_events'];
            }
            $secho .= '<br class="clear" />';
    }
    /**
     * copied from WP sanitize_html_class, and added space as allowed character to accomodate multiple classes in one string.
     * Strips the string down to A-Z, ,a-z,0-9,_,-. If this results in an empty string then it will return the alternative value supplied.
     *
     * @param string $class
     * @param string $fallback
     * @return string sanitized class or fallback.
     */
    static function sanitize_html_clss( $class, $fallback = '' ) {
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
            self::$default_block_attributes,
            $block_attributes
            );
        if (!in_array($block_attributes['tag_sum'], self::$allowed_tags_sum)) $block_attributes['tag_sum'] = 'a';
        $block_attributes['suffix_lg_class'] = self::sanitize_html_clss($block_attributes['suffix_lg_class']);
        $block_attributes['suffix_lgi_class'] = self::sanitize_html_clss($block_attributes['suffix_lgi_class']);
        $block_attributes['suffix_lgia_class'] = self::sanitize_html_clss($block_attributes['suffix_lgia_class']);
//        $block_attributes['anchorId'] = self::sanitize_html_clss($block_attributes['anchorId'], 'simpleicalblock' . $block_attributes['sibid']);
        
        
        return $block_attributes;
    }
    /**
     * copied from WP sanitize_html_class. (only for one class)
     * Strips the string down to A-Z,a-z,0-9,_,-. If this results in an empty string then it will return the alternative value supplied.
     *
     * @param string $class
     * @param string $fallback
     * @return string sanitized class or fallback.
     */
    static function sanitize_html_class( $class, $fallback = '' ) {
        // Strip out any %-encoded octets.
        $sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class );
        
        // Limit to A-Z, a-z, 0-9, '_', '-'.
        $sanitized = preg_replace( '/[^A-Za-z0-9_-]/', '', $sanitized );
        
        if ( '' === $sanitized && $fallback ) {
            return  $fallback;
        }
        return $sanitized;
    }
    /**
     * call Rest / Ajax component.
     * Get block content wth sibid, (active menu Itemid,) postid, and client timezone from request
     *
     * @param Input object $input $app-> 
     * 
     * @return JsonResponse object $data 
     * ["succes":   {true|false},
     *  "message":  {null|string}
     *  "messages": {null|array}
     *  "data":     ["content": {null| string: content of module},
     *               "params":  {null| array: used params ] 
     * ]
     * sinc 2.4.0
     *
     */
    public static function getAjax()
    {   
        $input = Factory::getApplication()->getInput();
        $params = $input->getArray();
        unset($params['option'],$params['module'],$params['method'],$params['view'],);
        if (empty($params['sibid'])) {
            $secho = '<p>' .  Text::_('MOD_SIMPLEICALBLOCK_EMPTYSIBID') .'</p>';
        } else {
            $mod = ModuleHelper::getModuleById($params['sibid']);
            if (empty($mod->params)) {
                $secho = '<p>' .  Text::_('MOD_SIMPLEICALBLOCK_NOPARAMS') .'</p>';
            } else {
                $secho = '';
                $attributes = self::render_attributes( array_merge( json_decode($mod->params, true), $params));
                self::display_block($attributes, $secho);
            }
        }
        $secho = self::clean_output($secho);
        $data = [
            'content' => $secho ,
            'params' => $params
        ];
        return $data;
    }
    /**
     * Clean output
     *
     * since 2.6.0
     *
     * @param string $output utput to clean
     * 
     * @return string escaped HTML output to render for the block (frontend)
     */
    static function clean_output($output)
    {
        if (empty(self::$input_fl)) {
            self::$input_fl = new InputFilter(self::$allowed_tags, self::$allowed_attrs, InputFilter::ONLY_ALLOW_DEFINED_TAGS, InputFilter::ONLY_ALLOW_DEFINED_ATTRIBUTES);
        }
        return self::$input_fl->clean($output,'HTML');
    }
    
}
