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

class Dc_Translator_Block_Adminhtml_Key_Js extends Mage_Adminhtml_Block_Template
{

    public function getAjaxUrl() {
        return $this->getUrl(
            'translator/adminhtml_ajax/translate',
            array('_secure' => Mage::app()->getStore()->isCurrentlySecure())
        );
    }
    
    public function getPackageId() {
        return Mage::registry('key_data')->getPackageId();
    }
    
    public function getLocale() {
        $_validate_locale = Mage::helper('translator/bing_locale')->validateByPackageId($this->getPackageId());
        if ($_validate_locale) {
            return $_validate_locale;
        }
        return false;
    }

}
