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
 * @copyright  Copyright (c) 2011-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_Dc_Block_System_Config_Form_Fieldset_Extensions extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    /**
     * @var
     */
    protected $fieldRenderer;

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        sort($modules);
        foreach ($modules as $module) {
            if (strstr($module, 'Dc_')) {
                $html .= $this->_getFieldHtml($element, $module);
            }
        }
        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    /**
     * @return mixed
     */
    protected function _getFieldRenderer()
    {
        if (empty($this->fieldRenderer)) {
            $this->fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        }
        return $this->fieldRenderer;
    }

    /**
     * @param $fieldset
     * @param $module
     * @return string
     */
    protected function _getFieldHtml($fieldset, $module)
    {
        $version = (Mage::getConfig()->getModuleConfig($module)->version);
        $string = '<a target="_blank"><img src="' . $this->getSkinUrl('dc/dc/images/module.png') . '" title="' . $this->__('Installed') . '"/></a>';
        $label = "$string $module";
        if ($version) {
            $field = $fieldset->addField($module, 'label',
                                         array(
                                              'name' => 'field_name_here',
                                              'label' => $label,
                                              'value' => $version,
                                         ))->setRenderer($this->_getFieldRenderer());
            return $field->toHtml();
        }
        return '';
    }

}
