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

class Dc_Translator_Model_Key extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('translator/key');
    }

    /**
     * Get the list of modules per package.
     *
     * @param $package_id
     * @return array
     */
    public function getPackageModules($package_id)
    {
        //TODO: validate this method because currently the package ID is not used.
        $modules = array();
        $connection = Mage::getSingleton('core/resource')->getConnection('read');
        $table = Mage::getSingleton('core/resource')->getTableName('translator/file');
        $data = $connection->fetchAll("SELECT distinct(package_module) FROM {$table} ORDER BY package_module ASC;");
        if (is_array($data) && !empty($data)) {
            foreach ($data as $_value) {
                $modules[$_value['package_module']] = $_value['package_module'];
            }
        }
        return $modules;
    }

}
