<?php
/**
 * Dc_Translator
 * 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_Translator
 * @copyright  Copyright (c) 2012-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_Translator_Model_Module extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
    }

    /**
     * Updates the list of modules with translation definition.
     */
    public function refreshModules()
    {
        $modules = array_keys((array) Mage::getConfig()->getNode('modules'));
        sort($modules);
        foreach ($modules as $name) {
            $config_file = Mage::getModuleDir('etc', $name) . DS . 'config.xml';
            if (file_exists($config_file)) {
                $file = false;
                $config_xml = new Varien_Simplexml_Config($config_file);
                if ($config_xml->getNode()->frontend->translate) {
                    foreach ($config_xml->getNode()->frontend->translate->modules as $values) {
                        $frontend_name = $values->children()->getName();
                    }
                    $file = $config_xml->getNode()->frontend->translate->modules->$frontend_name->files->default;
                }
                if (!$file) {
                    if ($config_xml->getNode()->adminhtml->translate) {
                        foreach ($config_xml->getNode()->adminhtml->translate->modules as $values) {
                            $adminhtml_name = $values->children()->getName();
                        }
                        $file = $config_xml->getNode()->adminhtml->translate->modules->$adminhtml_name->files->default;
                    }
                }
                if (!$file) {
                    if ($config_xml->getNode()->install->translate) {
                        foreach ($config_xml->getNode()->install->translate->modules as $values) {
                            $install_name = $values->children()->getName();
                        }
                        $file = $config_xml->getNode()->install->translate->modules->$install_name->files->default;
                    }
                }
                if ($file) {
                    $data = array('package_module' => $name, 'file_name' => (string)$file);
                    Mage::getModel('translator/file')->saveByModule($data);
                }
                unset($config_xml);
            }
        }
    }
    
}
