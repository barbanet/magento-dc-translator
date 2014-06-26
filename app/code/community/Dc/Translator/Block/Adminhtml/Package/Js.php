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

class Dc_Translator_Block_Adminhtml_Package_Js extends Mage_Adminhtml_Block_Template
{

    public function getZipUrl() {
        return $this->getUrl(
            'translator/adminhtml_ajax/download',
            array('_secure' => Mage::app()->getStore()->isCurrentlySecure())
        );
    }
    
    public function getPackageLocale() {
        return Mage::registry('package_data')->getLocale();
    }
    
    public function getUploaderUrl($file) {
        return $this->getSkinUrl('translator/' . $file);
    }
    
    public function getUploaderPost() {
        return Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('translator/adminhtml_ajax/store');
    }

}
