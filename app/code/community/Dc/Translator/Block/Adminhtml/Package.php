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

class Dc_Translator_Block_Adminhtml_Package extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_package';
        $this->_blockGroup = 'translator';
        $this->_headerText = Mage::helper('translator')->__('Packages');
        $this->_addButtonLabel = Mage::helper('translator')->__('Create New Package');
        parent::__construct();
        $this->_updateButton('add', 'onclick', 'setLocation(\'' . $this->getUrl('*/translator_package/create') .'\')');
        $this->_addButton('packages', array(
            'label'     => Mage::helper('translator')->__('Import package'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/translator_package/import') .'\')',
            'class'     => 'add',
        ), 10);
        $this->_addButton('modules', array(
            'label'     => Mage::helper('translator')->__('Refresh Modules List'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/translator_package/refresh') .'\')',
            'class'     => 'add',
        ), 20);
    }

}
