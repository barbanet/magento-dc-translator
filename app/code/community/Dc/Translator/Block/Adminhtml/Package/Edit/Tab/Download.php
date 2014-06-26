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

class Dc_Translator_Block_Adminhtml_Package_Edit_Tab_Download extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareLayout()
    {
        $this->setChild('download_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('translator')->__('Download Files'),
                    'onclick'   => 'DcDownloader.download()',
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
        $fieldset = $form->addFieldset('download', array('legend' => Mage::helper('translator')->__('Download Package files')));

        $_files = Mage::getModel('translator/file')->getExistingFiles($this->_getPackage()->getLocale());
        $fieldset->addField('list_action', 'note', array(
          'name'               => 'list_action',
          'after_element_html' => '<a id="toggler_select" onclick="return DcDownloader.selectAll()" href="#">' . Mage::helper('translator')->__('Select All') . '</a><span class="separator">|</span><a id="toggler_unselect" onclick="return DcDownloader.unselectAll()" href="#">' . Mage::helper('translator')->__('Unselect All') . '</a>'
        ));
        $fieldset->addField('downloadable', 'checkboxes', array(
          'label'              => Mage::helper('translator')->__('Available files'),
          'name'               => 'downloadable',
          'values'             => $_files,
          'onclick'            => "",
          'onchange'           => "",
          'value'              => array_keys($_files),
          'disabled'           => false,
          'after_element_html' => '<small>' . Mage::helper('translator')->__('Selected files will be downloaded') . '</small>',
          'tabindex' => 1
        ));
        
        if (count($_files) > 0) {
            $fieldset->addField('download_container', 'note', array(
                'text' => $this->getChildHtml('download_button'),
            ));
        }

        return parent::_prepareForm();
    }
    
    private function _getPackage() {
        return Mage::registry('package_data');
    }

}
