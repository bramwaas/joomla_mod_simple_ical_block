<?php
/**
 * @package     simple_ical_block
 * @subpackage  mod_simple_ical_block  Module
 * @copyright Copyright (C) 2022 - 2025 AHC Waasdorp, All rights reserved.
 * @license     GNU General Public License version 3 or later
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer AHC Waasdorp
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;

$joomlaverge4 = (version_compare(JVERSION, '4.0', '>='));
$app = Factory::getApplication();
$document = $app->getDocument();
$params->set('sibid',  $module->id);
$params->set('clear_cache_now', FALSE); // only clear transient on save in admin.
$direction = $document->direction;

if ($joomlaverge4) {
    $wa  = $document->getWebAssetManager();
    $wr = $wa->getRegistry();
    $wr->addRegistryFile('media/mod_simple_ical_block/joomla.asset.json');
if ($params->get('add_collapse_code',0)) HTMLHelper::_('bootstrap.collapse');
switch ($params->get('title_collapse_toggle','')) {
    case 'collapse':
        $module->set('title',  '<a data-bs-toggle="collapse" href="#' .$params->get('anchorId') . '" role="button" aria-expanded="false" aria-controls="collapseMod">>' . $module->get('title','') . '</a>');        
        break;
    case 'collapse show':
        $module->set('title',  '<a data-bs-toggle="collapse" href="#' .$params->get('anchorId') . '" role="button" aria-expanded="true" aria-controls="collapseMod">>' . $module->get('title','') . '</a>');
        break;
}

require ModuleHelper::getLayoutPath('mod_simple_ical_block', $params->get('layout','default'));
}
else {
    ?>
    <div id="simpleicalblock<?php echo $params->get('sibid'); ?>" class="simpleicalblock<?php echo $params->get('moduleclass_sfx') ?> "  tabindex="0">
<h3>site simpleicalblock needs joomla v4 or v5</h3>
</div>
<?php
}