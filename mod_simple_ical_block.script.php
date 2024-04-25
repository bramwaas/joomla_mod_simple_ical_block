<?php
/**
 * @version $Id: mod_simple_ical_block.script.php
 * @package simple_ical_bloc
 * @copyright Copyright (C) 2024 - 2024 AHC Waasdorp, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer AHC Waasdorp
 * 2024-04-25 remove unused file(s) and maps.
 */
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;

class mod_wsacarouselInstallerScript
{
    /**
     * Constructor
     *
     * @param   InstallerAdapter  $adapter  The object responsible for running this script
     */
    public function __construct(InstallerAdapter $adapter)
    {
    }
    
    /**
     * Called before any type of action
     *
     * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
     * @param   InstallerAdapter  $adapter  The object responsible for running this script
     *
     * @return  boolean  True on success
     */
    public function preflight($route, InstallerAdapter $adapter)
    {
        $first_message = true;
        $paths = ['/modules/mod_simple_ical_block/css', '/modules/mod_simple_ical_block/js'];
        foreach($paths as $path) {

            if (Folder::exists(JPATH_ROOT . $path) && Folder::delete(JPATH_ROOT . $path)) {
                if ($first_message) {
                    echo '<p>' . Text::sprintf('MOD_SIMPLE_ICAL_BLOCK_PREFLIGHT_TEXT') . '</p>';
                    $first_message = false;
                }
                echo '<p>', $path, Text::sprintf('MOD_SIMPLE_ICAL_BLOCK_REMOVED_TEXT') . '</p>';
         }  

        }
        $paths = ['/modules/mod_simple_ical_block/mod_simple_ical_block.php'];
        foreach($paths as $path) {
            if (File::exists(JPATH_ROOT . $path) && File::delete(JPATH_ROOT . $path)) {
                if ($first_message) {
                    echo '<p>' . Text::sprintf('MOD_SIMPLE_ICAL_BLOCK_PREFLIGHT_TEXT') . '</p>';
                    $first_message = false;
                }
                echo '<p>', $path, Text::sprintf('MOD_SIMPLE_ICAL_BLOCK_REMOVED_TEXT') . '</p>';
            } 
            
        }
        
        return true;
    }
    
    /**
     * Called after any type of action
     *
     * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
     * @param   InstallerAdapter  $adapter  The object responsible for running this script
     *
     * @return  boolean  True on success
     */
    public function postflight($route, $adapter)
    {
        return true;
    }
    
    /**
     * Called on installation
     *
     * @param   InstallerAdapter  $adapter  The object responsible for running this script
     *
     * @return  boolean  True on success
     */
    public function install(InstallerAdapter $adapter)
    {
        return true;
    }
    
    /**
     * Called on update
     *
     * @param   InstallerAdapter  $adapter  The object responsible for running this script
     *
     * @return  boolean  True on success
     */
    public function update(InstallerAdapter $adapter)
    {
        
        //     echo '<p>' . Text::sprintf('MOD_SIMPLE_ICAL_BLOCK_UPDATE_TEXT') . '</p>';
        
        
        
        return true;
    }
    
    /**
     * Called on uninstallation
     *
     * @param   InstallerAdapter  $adapter  The object responsible for running this script
     */
    public function uninstall(InstallerAdapter $adapter)
    {
        return true;
    }
}

?>
