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

class Dc_Translator_Model_Inline extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('translator/inline');
    }

    /**
     * Get list of store views.
     *
     * @return array
     */
    public function getStoreOptions()
    {
        $options = array();
        $connection = Mage::getSingleton('core/resource')->getConnection('read');
        $table_store = Mage::getSingleton('core/resource')->getTableName('core/store');
        $table_group = Mage::getSingleton('core/resource')->getTableName('core/store_group');
        $table_website = Mage::getSingleton('core/resource')->getTableName('core/website');
        $stores = $connection->fetchAll("SELECT s.store_id, s.name as store_view, s.code as store_code, g.name as store_group,
                                            w.name as website FROM {$table_store} as s left join {$table_group} as g on
                                            (s.group_id = g.group_id) left join {$table_website} as w on
                                            (g.website_id = w.website_id);");
        foreach ($stores as $store) {
            if ($store['store_id'] != 0) {
                $options[$store['store_id']] = $store['website'] . ' / ' . $store['store_group'] . ' / ' . $store['store_view'];
            } else {
                $options[$store['store_id']] = 'Default';
            }

        }
        return $options;
    }

}
