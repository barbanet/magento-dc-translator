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

class Dc_Translator_Block_Adminhtml_Package_Create_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('package_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('translator')->__('Package'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('translator')->__('Package'),
            'title'     => Mage::helper('translator')->__('Package'),
            'content'   => $this->getLayout()->createBlock('translator/adminhtml_package_create_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
