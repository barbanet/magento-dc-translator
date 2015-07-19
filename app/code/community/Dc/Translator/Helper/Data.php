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

class Dc_Translator_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Updates the same key across all the modules.
     *
     * @param $package_id
     * @param $package_key
     * @param $package_value
     */
    public function keyMassUpdate($package_id, $package_key, $package_value) {
        $now = now();
        $connection = Mage::getSingleton('core/resource')->getConnection('read');
        $table = Mage::getSingleton('core/resource')->getTableName('translator/key');
        $connection->query("UPDATE {$table}
                            SET package_value = '{$package_value}', updated_at = '{$now}'
                            WHERE package_id = {$package_id} AND package_key = '{$package_key}';");
    }

}
