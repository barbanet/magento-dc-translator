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
 * @version    1.0.1
 */

class Dc_Translator_Block_Adminhtml_Key_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareLayout()
    {
        $this->setChild('translate_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Automatic Translate'),
                    'onclick'   => 'DcTranslate.translate()',
                    'class'     => 'save'
                )
            )
        );
        return parent::_prepareLayout();
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form', array('legend' => Mage::helper('translator')->__('Key Translation')));

        $fieldset->addField('package_id', 'hidden', array(
            'name'      => 'package_id',
        ));
        if ($this->_getKeyId()) {
            $fieldset->addField('package_module', 'select', array(
                'label'     => Mage::helper('translator')->__('Module'),
                'class'     => 'required-entry',
                'required'  => true,
                'disabled'  => true,
                'name'      => 'package_module',
                'values'   => Mage::getModel('translator/key')->getPackageModules($this->_getPackageId())
            ));
        } else {
            $fieldset->addField('package_module', 'select', array(
                'label'     => Mage::helper('translator')->__('Module'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'package_module',
                'values'   => Mage::getModel('translator/key')->getPackageModules($this->_getPackageId())
            ));
        }
        if ($this->_getKeyId()) {
            $fieldset->addField('package_key', 'textarea', array(
                'label'     => Mage::helper('translator')->__('Key'),
                'class'     => 'required-entry',
                'disabled'  => true,
                'required'  => true,
                'name'      => 'package_key',
            ));
        } else {
            $fieldset->addField('package_key', 'textarea', array(
                'label'     => Mage::helper('translator')->__('Key'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'package_key',
            ));
        }
        $fieldset->addField('package_value', 'textarea', array(
            'label'     => Mage::helper('translator')->__('Value'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'package_value',
        ));
        
        if (Mage::getStoreConfig('translator/bing/enabled')) {
            $_validate_locale = Mage::helper('translator/bing_locale')->validateByPackageId($this->_getPackageId());
            if ($_validate_locale) {
                $fieldset->addField('translate_button', 'note', array(
                    'text' => $this->getChildHtml('translate_button'),
                ));
            }
        }
        
        if ($this->_getKeyId()) {
            $fieldset = $form->addFieldset('options', array('legend' => Mage::helper('translator')->__('Options')));
            $_comment = '<small>' . Mage::helper('translator')->__('Change this value if you want to translate this key with this value over all modules.')  . '</small>';
            $fieldset->addField('mass_update', 'select', array(
                'label'     => Mage::helper('translator')->__('Mass Update'),
                'class'     => 'required-entry',
                'required'  => false,
                'name'      => 'mass_update',
                'values'   => array(
                                    '0' => Mage::helper('translator')->__('No'),
                                    '1' => Mage::helper('translator')->__('Yes'),
                                    ),
                'after_element_html' => $_comment
            ));
        }
        
        if (Mage::getSingleton('adminhtml/session')->getKeyData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getKeyData());
            Mage::getSingleton('adminhtml/session')->setKeyData(null);
        } elseif (Mage::registry('key_data')) {
            $form->setValues(Mage::registry('key_data')->getData());
        }
        return parent::_prepareForm();
    }
    
    private function _getPackageId()
    {
        if ($this->getRequest()->getParam('package_id')) {
            Mage::registry('key_data')->setPackageId($this->getRequest()->getParam('package_id'));
        }
        return Mage::registry('key_data')->getPackageId();
    }
    
    private function _getKeyId()
    {
        if (Mage::registry('key_data')) {
            return Mage::registry('key_data')->getId();
        } else {
            return false;
        }
    }

}
