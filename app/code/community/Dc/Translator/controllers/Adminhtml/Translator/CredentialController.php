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

class Dc_Translator_Adminhtml_Translator_CredentialController extends Mage_Adminhtml_Controller_action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/translator');
    }

    public function testAction()
    {
        try {
            Mage::helper('translator/bing_translator')->translate(base64_encode('Hello world.'));
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__("Your API credentials are OK"));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('translator')->__("Your API credentials are wrong. The automatic translation won't work."));
        }
        $this->_redirect('adminhtml/system_config/edit',array('section' => 'translator'));
        return;
    }

}
