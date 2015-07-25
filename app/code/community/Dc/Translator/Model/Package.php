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

class Dc_Translator_Model_Package extends Mage_Core_Model_Abstract
{

    /**
     * @var
     */
    protected $system_locales;

    public function _construct()
    {
        parent::_construct();
        $this->_init('translator/package');
    }

    /**
     * Get the full list of locale packages.
     *
     * @return mixed
     */
    public function getLocales()
    {
        if (!$this->system_locales) {
            $locales = Mage::getSingleton('core/locale')->getOptionLocales();
            foreach ($locales as $locale) {
                $this->system_locales[$locale['value']] = $locale['label'];
            }
        }
        return $this->system_locales;
    }

    /**
     * Returns list of available locales into the module.
     *
     * @param null $empty
     * @return array
     */
    public function getAvailableLocales($empty = null)
    {
        $locales = $this->getLocales();
        $available = array();
        $packages = Mage::getModel('translator/package')->getCollection();
        if ($empty) {
            $available[] = array(
                                'value' => '',
                                'label' => Mage::helper('translator')->__('None (empty one)')
                            );
        }
        foreach ($packages as $package) {
            if (array_key_exists($package->getLocale(), $locales)) {
                $available[] = array(
                                    'value' => $package->getLocale(),
                                    'label' => $locales[$package->getLocale()]
                                );
            }
        }
        return $available;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $locales = $this->getLocales();
        $available = array();
        $packages = Mage::getModel('translator/package')->getCollection();
        foreach ($packages as $package) {
            if (array_key_exists($package->getLocale(), $locales)) {
                $available[$package->getLocale()] = $locales[$package->getLocale()];
            }
        }
        return $available;
    }

    /**
     * Returns the list of existing locales on filesystem that does not exists into the module.
     *
     * @return array
     */
    public function getPendingLocales()
    {
        $locales = $this->getLocales();
        $pending = array();
        $packages = Mage::getModel('translator/package')->getCollection();
        foreach ($packages as $package) {
            if (array_key_exists($package->getLocale(), $locales)) {
                unset($locales[$package->getLocale()]);
            }
        }
        foreach ($locales as $key => $value) {
            $pending[] = array(
                                'value' => $key,
                                'label' => $value
                            );
        }
        return $pending;
    }

    /**
     * @param $locale
     * @return bool
     */
    public function getPackageByLocale($locale)
    {
        $data = Mage::getModel('translator/package')->getCollection()
                    ->addFieldToFilter('locale', array('eq' => $locale));
        if ($data->getSize() > 0) {
            return $data->getFirstItem();
        }
        return false;
    }

    /**
     * Get the full name of the locale instead the code.
     *
     * @param $locale
     * @return bool
     */
    public function getFancyName($locale)
    {
        $locales = $this->getLocales();
        if (array_key_exists($locale, $locales)) {
            return $locales[$locale];
        }
        return false;
    }

    /**
     * Get the list of not imported locales into the module.
     *
     * @return array
     */
    public function getNotImportedPackages()
    {
        $locales = array();
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS;
        $directory = dir($path);
        while ($locale = $directory->read()) {
            if (is_dir($path . DS . $locale) && strlen($locale) == 5 && !array_key_exists($locale, $this->toOptionArray())) {
                $locales[] = array(
                                    'value' => $locale,
                                    'label' => $this->getFancyName($locale)
                                );
            }
        }
        sort($locales, SORT_STRING);
        return $locales;
    }

}
