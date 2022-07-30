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
 * 0.0.0 2022-07-10 first adjustments for J4 convert parameters to array $attributes.
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Cache\Controller\OutputController;
use Joomla\CMS\Language\Text;
use WaasdorpSoekhan\Module\Simpleicalblock\Site\Helper\SimpleicalblockHelper;
$attributes = $params->toArray();

//$cachecontroller = Factory::getContainer()->get(CacheControllerFactoryInterface::class)->createCacheController('output', []);
$options = array(
    'lifetime'     => (int) 60 * $attributes['transient_time'],
    'caching'      => 1,
);
$cachecontroller = new OutputController($options);
$cache = $cachecontroller->cache;

$data = ['Red', 'Green \of' , 'Blue'];
$transientId = 'SimpleiCalBlock' . $attributes['blockid'];
//$helper = new SimpleicalblockHelper;

if ( false === ( $data = $cachecontroller->get( $transientId, '' ) ) ) {
    // we are here, means there is no data in cache. so let's put something in cache
    
    $data = new \DateTime();;
    $transientDataS = (($transientData));
    //now cache the data $cache_data
    $cachecontroller->store($transientDataS, $transientId, '' ); //if cache stored
}

//$cache_data  is the data we get from cache
//var_dump($data);


/*
if(false === ($data = SimpleicalblockHelper::get_transient($transientId)) OR empty($data)) {
    $data = new \DateTime();
    if ($data) {
        SimpleicalblockHelper::set_transient($transientId, $data , 60 * $attributes['transient_time']);
     }
 }
 */
?>

<div id="simpleicalblock<?php echo  $attributes['blockid']; ?>" class="simpleicalblock<?php echo $params->get('moduleclass_sfx') ?> "  tabindex="0">
<!-- <?php  print_r($attributes); ?>  -->
<div><?php print_r( $data); ?></div>
<div><?php print_r( $cachecontroller); ?></div>

</div>

