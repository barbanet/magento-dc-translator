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

class Dc_Translator_Block_Adminhtml_Inline_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form', array('legend' => Mage::helper('translator')->__('Key Translation')));

        $fieldset->addField('string', 'text', array(
            'label'     => Mage::helper('translator')->__('String'),
            'disabled'  => true,
            'name'      => 'string',
        ));

        $fieldset->addField('store_id', 'select', array(
            'label'     => Mage::helper('translator')->__('Store View'),
            'disabled'  => true,
            'name'      => 'store_id',
            'values'    => Mage::getSingleton('translator/inline')->getStoreOptions()
        ));

        $fieldset->addField('locale', 'select', array(
            'label'     => Mage::helper('translator')->__('Locale'),
            'disabled'  => true,
            'name'      => 'locale',
            'values'    => Mage::getModel('translator/package')->getLocales()
        ));

        $fieldset->addField('translate', 'text', array(
            'label'     => Mage::helper('translator')->__('Translate'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'translate',
        ));
        
        if (Mage::getSingleton('adminhtml/session')->getInlineData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getInlineData());
            Mage::getSingleton('adminhtml/session')->setInlineData(null);
        } elseif (Mage::registry('inline_data')) {
            $form->setValues(Mage::registry('inline_data')->getData());
        }
        return parent::_prepareForm();
    }

}
