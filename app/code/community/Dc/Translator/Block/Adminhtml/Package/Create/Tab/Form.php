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

class Dc_Translator_Block_Adminhtml_Package_Create_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form', array('legend' => Mage::helper('translator')->__('New Package')));
        $fieldset->addField('base_locale', 'select', array(
            'label'     => Mage::helper('translator')->__('Based On'),
            'name'      => 'base_locale',
            'values'   => Mage::getModel('translator/package')->getAvailableLocales(true)
        ));
        $fieldset->addField('locale', 'select', array(
            'label'     => Mage::helper('translator')->__('Locale'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'locale',
            'values'   => Mage::getModel('translator/package')->getPendingLocales()
        ));
        
        if (Mage::getSingleton('adminhtml/session')->getPackageData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPackageData());
            Mage::getSingleton('adminhtml/session')->setPackageData(null);
        } elseif (Mage::registry('package_data')) {
            $form->setValues(Mage::registry('package_data')->getData());
        }
        return parent::_prepareForm();
    }

}
