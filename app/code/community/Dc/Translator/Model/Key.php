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

class Dc_Translator_Model_Key extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('translator/key');
    }
    
    public function getPackageModules($package_id)
    {
        $_modules = array(); 
        $_connection = Mage::getSingleton('core/resource')->getConnection('read');
        $_table = Mage::getSingleton('core/resource')->getTableName('translator/file');
        $_data = $_connection->fetchAll("SELECT distinct(package_module) FROM {$_table} ORDER BY package_module ASC;");
        if (is_array($_data) && !empty($_data)) {
            foreach ($_data as $_value) {
                $_modules[$_value['package_module']] = $_value['package_module'];
            }
        }
        return $_modules;
    }

}
