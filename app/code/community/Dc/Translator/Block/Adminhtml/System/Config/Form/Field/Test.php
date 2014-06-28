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

class Dc_Translator_Block_Adminhtml_System_Config_Form_Field_Test extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $buttonBlock = $this->getLayout()->createBlock('adminhtml/widget_button');
        $data = array(
            'label'     => Mage::helper('translator')->__('Run Test'),
            'onclick'   => 'setLocation(\''. $this->getUrl('translator/adminhtml_credential/test') . '\' );',
            'class'     => '',
        );
        $html = $buttonBlock->setData($data)->toHtml();
        return $html;
    }

}
