<?php
/**
 * Dc_Dc
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_Dc
 * @copyright  Copyright (c) 2014 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_Dc_Block_System_Config_Form_Fieldset_Extensions	extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    protected $_fieldRenderer;
    protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $_html = $this->_getHeaderHtml($element);
        $_modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        sort($_modules);
        foreach ($_modules as $_module) {
            if (strstr($_module, 'Dc_')) {
                $_html .= $this->_getFieldHtml($element, $_module);
            }
        }
        $_html .= $this->_getFooterHtml($element);
        return $_html;
    }

    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        }
        return $this->_fieldRenderer;
    }

    protected function _getFieldHtml($fieldset, $module)
    {
        $_version = (Mage::getConfig()->getModuleConfig($module)->version);
        $_string = '<a target="_blank"><img src="' . $this->getSkinUrl('dc/dc/images/module.png') . '" title="' . $this->__('Installed') . '"/></a>';
        $_module = "$_string $module";
        if ($_version) {
            $field = $fieldset->addField($module, 'label',
                                         array(
                                              'name' => 'field_name_here',
                                              'label' => $_module,
                                              'value' => $_version,
                                         ))->setRenderer($this->_getFieldRenderer());
            return $field->toHtml();
        }
        return '';
    }

}
