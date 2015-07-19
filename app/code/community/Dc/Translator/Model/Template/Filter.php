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

class Dc_Translator_Model_Template_Filter extends Mage_Widget_Model_Template_Filter
{

    /**
     * Adds the {{translate}} directive for cms.
     *
     * @param $construction
     * @return string
     */
    public function translateDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);
        $text = $params['text'];
        return Mage::helper('translator')->__($text);
    }

}
