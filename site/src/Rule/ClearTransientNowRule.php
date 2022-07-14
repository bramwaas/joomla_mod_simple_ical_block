<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.dna
 *
 * @copyright Copyright (C) 2016 - 2022 Bram Waasdorp. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 * 10-9-2020
 * 14-9-2020 removed most references to TWBS 3 SASS files and the files itself.
 * 31-12-2021 restored most references to TWBS 3 SASS files and the files itself.
 * 2-1-2022 node ... containers verplaatst en daarna verwijderd omdat deze niet compatible is met BS3.
 *   most warnings of undefined variables resolved by commenting those statements.
 * 30-1-2022 //TODO namespace will work net earlier than Joomla v4.2 and maybe Administrator and Site must be distinguised.  
 */
/* regel voor validatie type compiler, bedoeld om samenstellen en compileren Less bestanden uit te voeren vlak voor
   de save  

	*/
namespace WaasdorpSoekhan\Template\Wsaonepage\Rule;

\defined('_JEXEC') or die('caught by _JEXEC');

use Joomla\CMS\Factory;   
//use Joomla\CMS\Language\Text;   // voor vertalingen???
use Joomla\CMS\Form\FormRule;
use Joomla\CMS\Form\Form;


 
class ClearTransientNowRule extends FormRule
/* voorbeeld eenvoudigste validatie dmv regexp
 uitgebreider gaat met functie test die ik hier wel ga gebruiken.
{
    protected $regex = '[0-9]';
}
*/

{ /* begin WsaFormRuleCompiler voert validatie wsa.compiler uit */

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
//$templatestyleid =  Uri::getInstance ()->getVar('id');
 $app = Factory::getApplication();
 $currentpath = realpath(__DIR__ ) ;
$templatestyleid = $input->get('id');
$home = $input->get('home');
$params = $input->get('params'); // stdobject params are properties.


if  (htmlspecialchars($value) == '1')

{ /* creeren en compileren */

$gplusProfile   = htmlspecialchars($params->gplusProfile);

try { 
    /* start try */
    /* einde les files compileren naar .css */
    /* "Compileren LESS geslaagd." "COM_TEMPLATES_COMPILE_SUCCESS" */
    $app->enqueueMessage("Compile SCSS succes..", 'message');
/* end try */
}
catch (\Exception $e)
{
 $app->enqueueMessage('Scss compile failed ' . $e->getMessage(), 'error');
 return false;
}

/* end creeren en compileren */
}


return true;
/* eind test */
}
/* eind WsaFormRuleCompiler */
}

?>