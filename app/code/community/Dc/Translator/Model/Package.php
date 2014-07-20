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
 */

class Dc_Translator_Model_Package extends Mage_Core_Model_Abstract
{
    
    protected $_system_locales;

    public function _construct()
    {
        parent::_construct();
        $this->_init('translator/package');
    }
    
    private function _getLocales()
    {
        if (!$this->_system_locales) {
            $_locales = Mage::getSingleton('core/locale')->getOptionLocales();
            foreach ($_locales as $_locale) {
                $this->_system_locales[$_locale['value']] = $_locale['label'];
            }
        }
        return $this->_system_locales;
    }
    
    public function getAvailableLocales($empty = null)
    {
        $_locales = $this->_getLocales();
        $_available = array();
        $_packages = Mage::getModel('translator/package')->getCollection();
        if ($empty) {
            $_available[] = array(
                                'value' => '',
                                'label' => Mage::helper('translator')->__('None (empty one)')
                            );
        }
        foreach ($_packages as $_package) {
            if (array_key_exists($_package->getLocale(), $_locales)) {
                $_available[] = array(
                                    'value' => $_package->getLocale(),
                                    'label' => $_locales[$_package->getLocale()]
                                );
            }
        }
        return $_available;
    }
    
    public function toOptionArray()
    {
        $_locales = $this->_getLocales();
        $_available = array();
        $_packages = Mage::getModel('translator/package')->getCollection();
        foreach ($_packages as $_package) {
            if (array_key_exists($_package->getLocale(), $_locales)) {
                $_available[$_package->getLocale()] = $_locales[$_package->getLocale()];
            }
        }
        return $_available;
    }
    
    public function getPendingLocales()
    {
        $_locales = $this->_getLocales();
        $_pending = array();
        $_packages = Mage::getModel('translator/package')->getCollection();
        foreach ($_packages as $_package) {
            if (array_key_exists($_package->getLocale(), $_locales)) {
                unset($_locales[$_package->getLocale()]);
            }
        }
        foreach ($_locales as $_key => $_value) {
            $_pending[] = array(
                                'value' => $_key,
                                'label' => $_value
                            );
        }
        return $_pending;
    }
    
    public function getPackageByLocale($locale)
    {
        $_data = Mage::getModel('translator/package')->getCollection()
                    ->addFieldToFilter('locale', array('eq' => $locale));
        if ($_data->getSize() > 0) {
            return $_data->getFirstItem();
        }
        return false;
    }
    
    public function getFancyName($locale)
    {
        $_locales = $this->_getLocales();
        if (array_key_exists($locale, $_locales)) {
            return $_locales[$locale];
        }
        return false;
    }
    
    public function getNotImportedPackages()
    {
        $_locales = array();
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS;
        $directory = dir($path);
        while ($locale = $directory->read()) {
            if (is_dir($path . DS . $locale) && strlen($locale) == 5 && !array_key_exists($locale, $this->toOptionArray())) {
                $_locales[] = array(
                                    'value' => $locale,
                                    'label' => $this->getFancyName($locale)
                                );
            }
        }
        sort($_locales, SORT_STRING);
        return $_locales;
    }

}
