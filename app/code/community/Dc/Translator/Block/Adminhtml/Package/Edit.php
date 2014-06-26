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

class Dc_Translator_Block_Adminhtml_Package_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'translator';
        $this->_controller = 'adminhtml_package';
        $this->_removeButton('delete');
        $this->_removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('translator')->__('Generate Package'));
    }

    public function getHeaderText()
    {
        if (Mage::registry('package_data')->getPackageId()) {
            return Mage::helper('translator')->__('Edit Package %s', $this->getPackageName());
        } else {
            return Mage::helper('translator')->__('Create New Package');
        }
    }
    
    public function getPackageName()
    {
        $_locale = Mage::registry('package_data')->getLocale();
        if ($_locale) {
            return Mage::getModel('translator/package')->getFancyName($_locale);
        }
        return false;
    }

}
