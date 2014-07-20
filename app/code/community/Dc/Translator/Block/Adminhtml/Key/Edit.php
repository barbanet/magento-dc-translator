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

class Dc_Translator_Block_Adminhtml_Key_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    protected $_button_previous;
    protected $_button_next;

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'translator';
        $this->_controller = 'adminhtml_key';
        $this->_removeButton('reset');
        $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getUrl('*/adminhtml_key/', array('package_id' => $this->_getPackageId())) .'\')');
        $this->_getPrevNext();
        $this->_getButtonPrevious();
        $this->_getButtonNext();
    }

    public function getHeaderText()
    {
        if (Mage::registry('key_data')->getKeyId()) {
            return Mage::helper('translator')->__('Edit Key for Package %s', $this->getPackageName());
        } else {
            return Mage::helper('translator')->__('Create New Key');
        }
    }
    
    public function getPackageName()
    {
        $_locale = Mage::getModel('translator/package')->load(Mage::registry('key_data')->getPackageId())->getLocale();
        if ($_locale) {
            return Mage::getModel('translator/package')->getFancyName($_locale);
        }
        return false;
    }
    
    private function _getPackageId()
    {
        if ($this->getRequest()->getParam('package_id')) {
            return $this->getRequest()->getParam('package_id');
        } else {
            return Mage::registry('key_data')->getPackageId();
        }
    }
    
    private function _getPrevNext()
    {
        if (Mage::registry('key_data')->getKeyId()) {
            $_position_current = false;
            $_collection = Mage::getModel('translator/key')->getCollection()
                            ->addFieldToFilter('package_id', array('eq' => $this->_getPackageId()));
            $_collection_array = $_collection->toArray();
            if (isset($_collection_array['items'])) {
                foreach ($_collection_array['items'] as $_array_key => $_array_value) {
                        if ($_array_value['key_id'] == Mage::registry('key_data')->getKeyId()) {
                            $_position_current = $_array_key;
                        break;
                    }
                }
                if ($_position_current) {
                    if (isset($_collection_array['items'][($_position_current-1)])) {
                        $this->_button_previous = $_collection_array['items'][($_position_current-1)]['key_id'];
                    }
                    if (isset($_collection_array['items'][($_position_current+1)])) {
                        $this->_button_next = $_collection_array['items'][($_position_current+1)]['key_id'];
                    }
                }
            }
        }
    }
    
    protected function _getButtonPrevious()
    {
        if ($this->_button_previous) {
            $this->_addButton('key_previous', array(
                'label'     => Mage::helper('translator')->__('Previous'),
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_key/edit', array('id' => $this->_button_previous)) . '\')',
                'class'     => '',
            ), 0);
        }
    }
    
    protected function _getButtonNext()
    {
        if ($this->_button_next) {
            $this->_addButton('key_next', array(
                'label'     => Mage::helper('translator')->__('Next'),
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_key/edit', array('id' => $this->_button_next)) . '\')',
                'class'     => '',
            ), 0);
        }
    }

}
