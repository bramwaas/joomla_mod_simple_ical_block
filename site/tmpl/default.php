<?php
/**
 * @version $Id: default.php
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2022 -2022 wsacarousel, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
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
 * along with WsaCarousel. If not, see <http://www.gnu.org/licenses/>.
 * 0.2.0
 * ook voor eigen javascript 3 wsacarousel
 * 1.0.6 20-2-2022 adjustments for J4
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');
//use Joomla\CMS\Factory;
//use Joomla\CMS\Language\Text;

?>

<div id="simpleicalblock<?php echo  $params->get('blockid'); ?>" class="simpleicalblock<?php echo $params->get('moduleclass_sfx') ?> "  tabindex="0">
<h3>Site simpleicalblock<?php echo  $params->get('blockid'); ?></h3>
<div>
<?php print_r($params); ?>
</div>
</div>

