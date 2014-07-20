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

class Dc_Translator_Block_Adminhtml_Package_Edit_Tab_Upload extends Mage_Adminhtml_Block_Widget_Form
{
    
    protected function _prepareLayout()
    {
        $this->setChild('upload_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('translator')->__('Upload files'),
                    'onclick'   => 'jQuery(\'#file_upload\').uploadify(\'upload\',\'*\')',
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
        $fieldset = $form->addFieldset('upload', array('legend' => Mage::helper('translator')->__('Upload Package files')));


        $fieldset->addField('file_upload', 'file', array(
            'label'     => Mage::helper('translator')->__('Filename'),
            'name'      => 'file_upload',
            'multiple'  => true,
        ));
        
        $fieldset->addField('upload_container', 'note', array(
            'text' => $this->getChildHtml('upload_button'),
        ));

        return parent::_prepareForm();
    }
    
    private function _getPackage() {
        return Mage::registry('package_data');
    }

}
