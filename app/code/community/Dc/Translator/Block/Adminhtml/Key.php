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

class Dc_Translator_Block_Adminhtml_Key extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_key';
        $this->_blockGroup = 'translator';
        $this->_headerText = Mage::helper('translator')->__('Package %s Keys', $this->getPackageName());
        $this->_addButtonLabel = Mage::helper('translator')->__('Add New Key');
        parent::__construct();
        $this->_updateButton('add', 'level', '10');
        $this->_updateButton('add', 'onclick', 'setLocation(\'' . $this->getUrl('*/adminhtml_key/new/', array('package_id' => $this->_getPackageId())) .'\')');
        $this->_addButton('packages', array(
            'label'     => Mage::helper('translator')->__('Back To Packages'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_package/') .'\')',
            'class'     => 'back',
        ), 0);
    }
    
    public function getPackageName()
    {
        $_locale = Mage::getModel('translator/package')->load($this->_getPackageId())->getLocale();
        if ($_locale) {
            return Mage::getModel('translator/package')->getFancyName($_locale);
        }
        return false;
    }
    
    private function _getPackageId()
    {
        return $this->getRequest()->getParam('package_id');
    }

}
