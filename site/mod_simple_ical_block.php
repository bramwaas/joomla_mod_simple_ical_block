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
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

$joomlaverge4 = (version_compare(JVERSION, '4.0', '>='));
$app = Factory::getApplication();
$document = Factory::getDocument();
$params->set('blockid', $module->id);
$params->set('clear_cache_now', FALSE); // only clear transient on save in admin.
$direction = $document->direction;
$asset_dir =  "media/mod_simple_ical_block/";

if ($joomlaverge4) {
require ModuleHelper::getLayoutPath('mod_simple_ical_block', $params->get('layout','default'));
}
else {
    ?>
    <div id="simpleicalblock<?php echo $params->get('blockid'); ?>" class="simpleicalblock<?php echo $params->get('moduleclass_sfx') ?> "  tabindex="0">
<h3>site simpleicalblock needs joomla v4</h3>
</div>
<?php
}