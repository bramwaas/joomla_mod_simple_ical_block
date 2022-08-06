<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.dna
 *
 * @copyright Copyright (C) 2022 - 2022 Bram Waasdorp. All rights reserved.
 * @license GNU General Public License version 3 or later
 * used to Clear calendar cache before save when asked for.
 * 31-7-2022 0.0.4 replace transient by cache type 'output'; split transientId in cahegroup and cacheID to distinguish the group in system clear cache
 */

namespace WaasdorpSoekhan\Module\Simpleicalblock\Site\Rule;

\defined('_JEXEC') or die('caught by _JEXEC');

use Joomla\CMS\Cache\Controller\OutputController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;   // voor vertalingen???
use Joomla\CMS\Form\FormRule;
use Joomla\CMS\Form\Form;
// use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalblockHelper;


class CleartransientnowRule extends FormRule
/* example simplest validation using regexp
 more elaborate with function test that I will use here.
 {
 protected $regex = '[0-9]';
 }
 */

{ /* begin CleartransientnowRule performs validation cleartransientnow */
    
    //public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    
    /**
     * Method to test the value.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as as an array container for the field.
     *                                       For example if the field has name="foo" and the group value is set to "bar" then the
     *                                       full field name would end up being "bar[foo]".
     * @param   mixed              $input    An optional "Registry" object with the entire data set to validate against the entire form.
     * @param   Form               $form     The form object for which the field is being tested.
     *
     * @return  boolean  True if the value is valid, false otherwise.
     *
     * @since   1.7.0
     * @throws  \UnexpectedValueException if rule is invalid.
     */
    public function test(\SimpleXMLElement $element, $value, $group = null,  $input = null, Form $form = null)
    
    {
        $app = Factory::getApplication();
        $cacheId =  $input->get('id'); // = moduleid = blockid.
        $cachegroup = 'SimpleicalBlock';
        $transientId = $cachegroup . $cacheId;
        $options = array(
            'lifetime'     => 1,
            'caching'      => true,
            'language'     => 'en-GB',
            'application'  => 'site',
        );
        $cachecontroller = new OutputController($options);
        if  (htmlspecialchars($value) == '1')
        { /* clear transient cache */
            try {
                $succes = $cachecontroller->cache->remove($cacheId, $cachegroup);
                $app->enqueueMessage(Text::sprintf('MOD_SIMPLEICALBLOCK_TRANSIENT_CLEARED', $transientId), 'message');
            }
            catch (\Exception $e)
            {
                $app->enqueueMessage(Text::sprintf('MOD_SIMPLEICALBLOCK_TRANSIENT_CLEAR_FAILED', $transientId, $e->getMessage()), 'error');
                return false;
            }
            /* end clear transient cache */
        }
        return true;
    }
}
?>