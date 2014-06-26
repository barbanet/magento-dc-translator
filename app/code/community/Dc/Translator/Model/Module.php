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
 * @copyright  Copyright (c) 2014 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version    1.0.0
 */

class Dc_Translator_Model_Module extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
    }
    
    public function refreshModules()
    {
        $_modules = array_keys((array) Mage::getConfig()->getNode('modules'));
        sort($_modules);
        foreach ($_modules as $_name) {
            $_config_file = Mage::getModuleDir('etc', $_name) . DS . 'config.xml';
            if (file_exists($_config_file)) {
                $file = false;
                $_config_xml = new Varien_Simplexml_Config($_config_file);
                if ($_config_xml->getNode()->frontend->translate) {
                    foreach ($_config_xml->getNode()->frontend->translate->modules as $values) {
                        $_frontend_name = $values->children()->getName();
                    }
                    $file = $_config_xml->getNode()->frontend->translate->modules->$_frontend_name->files->default;
                }
                if (!$file) {
                    if ($_config_xml->getNode()->adminhtml->translate) {
                        foreach ($_config_xml->getNode()->adminhtml->translate->modules as $values) {
                            $_adminhtml_name = $values->children()->getName();
                        }
                        $file = $_config_xml->getNode()->adminhtml->translate->modules->$_adminhtml_name->files->default;
                    }
                }
                if (!$file) {
                    if ($_config_xml->getNode()->install->translate) {
                        foreach ($_config_xml->getNode()->install->translate->modules as $values) {
                            $_install_name = $values->children()->getName();
                        }
                        $file = $_config_xml->getNode()->install->translate->modules->$_install_name->files->default;
                    }
                }
                if ($file) {
                    $data = array('package_module' => $_name, 'file_name' => (string)$file);
                    Mage::getModel('translator/file')->saveByModule($data);
                }
                unset($_config_xml);
            }
        }
    }
    
}
