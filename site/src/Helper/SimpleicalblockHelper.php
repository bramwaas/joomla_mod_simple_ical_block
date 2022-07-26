<?php
/**
 * @version $Id: simpleicalblock.php 
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2022 -2022 A.H.C. Waasdorp, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
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
 * 0.0.7
 * 0.2.0 slide delay added.
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
     * Delete existing transient with transientId.
     *
     * @param string  $transientId    Id for the transient
     *
     * @return void 
     */
    static function delete_transient($transientId)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
        ->delete($db->quoteName('#__simpleicalblock'))
        ->where($db->quoteName('transient_id') . " = '" . $transientId ."'");
        $db->setQuery($query);
        try
        {
           return $db->execute();
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED') . ' Delete: ' . $e->getMessage(), 'warning');
            return FALSE;
        }
    }
    /**
     * Retrieves transient data stored with transientId and still valid.
     *
     * @param string  $transientId    Id for the transient ( $transientId = 'SimpleicalBlock'  . $instance['blockid']   ;)
     *
     * @return        $data or false when transient with transientId doesn't exist or is not valid. 
     */
    static function get_transient($transientId)
    {
        if (isset($transientId) && ' ' < $transientId) {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
        ->select($db->quoteName(['a.transient_blob', 'a.transient_expires']))
        ->from($db->quoteName('#__simpleicalblock', 'a'))
        ->where($db->quoteName('a.transient_id') . " = '" . $transientId . "' and " . $db->quoteName('a.transient_expires') . ' > ' . time());
        $db->setQuery($query);
        try
        {
            $transient_blob = unserialize(base64_decode($db->loadResult()));
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED') . ' Get:', 'error');
            return false;
        }
        return $transient_blob;
        } else 
        {
            Factory::getApplication()->enqueueMessage(Text::_('MOD_SIMPLEICALBLOCK_TRANSIENTID_EMPTY'), 'warning');
            return false;
        }
    }
    /**
     * Creates or updates transient data to store (cache) data that are valid for a period of time.
     *
     * @param string  $transientId    Id for the transient
     *                $transientData  data to store serialized to store objects, base64endoced to prevent issues with backslash or other escape chars. 
     *        integer $transientTime  time in seconds that the stored data is valid. 
     *
     * @return boolean true on succes.
     */
    static function set_transient($transientId, $transientData, $transientTime)
    {
        if ((isset($transientId) && ' ' < $transientId) && isset($transientData)) {
            $transientExpiresTS = time() + ((isset($transientTime) && 0 < intval($transientTime)) ? intval($transientTime) : 0 );
            $transientDataS = base64_encode(serialize($transientData));
            $db    = Factory::getDbo();
            $query = $db->getQuery(true)
            ->select($db->quoteName(['a.id']))
            ->from($db->quoteName('#__simpleicalblock', 'a'))
            ->where($db->quoteName('a.transient_id') . " = '" . $transientId ."'");
            $db->setQuery($query);
            try
            {
                $found = FALSE;
                if (!(NULL === $db->loadResult()))  $found = TRUE;
            }
            catch (\RuntimeException $e)
            {
                Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED') . ' Set1: ' . $e->getMessage(), 'warning');
            }
            try {
                 $query->clear();
                 if ($found){
                     $query->update($db->quoteName('#__simpleicalblock', 'a'))
                     ->set([$db->quoteName('transient_blob') . " = '" . $transientDataS . "'", $db->quoteName('transient_expires') . " = " . $transientExpiresTS ])
                     ->where($db->quoteName('a.transient_id') . " = '" . $transientId ."'");
                 } else {
                     $query->insert($db->quoteName('#__simpleicalblock'), true)
                     ->columns($db->quoteName(['transient_id', 'transient_blob', 'transient_expires']))
                     ->values("'" . $transientId . "', '" . $transientDataS . "', " . $transientExpiresTS );
                 }
                 $db->setQuery($query);
                 return $db->execute();
             } catch (\RuntimeException $e) 
             {
                 Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED') . ' Set2: ' . $e->getMessage(), 'error');
                  return false;
             }
        } else
        {
            Factory::getApplication()->enqueueMessage(Text::_('MOD_SIMPLEICALBLOCK_TRANSIENTID_OR_DATA_EMPTY'), 'warning'); 
            return false;
        }
    }
    
    /**
     * Merge block attributes with defaults to be sure they exist is necesary.
     *
     * @param array $block_attributes the module params object presented as array ($params->toArray())
     * @return array attributes from parameters merged with default  attributes. 
     */
    static function render_attributes($block_attributes) {
       return  array_merge(
            array(
                'blockid' => 'AZ',
  //              'title' => Text::_('MOD_SIMPLEICALBLOCK_TITLE_DFT'),
                'calendar_id' => '',
                'event_count' => 10,
                'event_period' => 92,
                'transient_time' => 60,
                'startwsum' => false,
                'dateformat_lg' => Text::_('MOD_SIMPLEICALBLOCK_DF_DFT'),
                'dateformat_lgend' => '',
                'tag_sum' => 'a',
                'dateformat_tsum' => Text::_('MOD_SIMPLEICALBLOCK_DF_TSUM_DFT'),
                'dateformat_tsend' => '',
                'dateformat_tstart' => Text::_('MOD_SIMPLEICALBLOCK_DF_TSTART_DFT'),
                'dateformat_tend' => Text::_('MOD_SIMPLEICALBLOCK_DF_TEND_DFT'),
                'excerptlength' => '',
                'suffix_lg_class' => '',
                'suffix_lgi_class' => ' py-0',
                'suffix_lgia_class' => '',
                'allowhtml' => false,
                'clear_cache_now' => false,
                //               'align'=>'',
                'className'=>'',
                'anchorId'=> '',
            ),
            $block_attributes
            );
    }
    

}
