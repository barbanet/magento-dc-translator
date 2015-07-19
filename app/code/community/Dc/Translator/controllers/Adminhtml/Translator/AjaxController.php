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

class Dc_Translator_Adminhtml_AjaxController extends Mage_Adminhtml_Controller_Action
{

    public function translateAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $_search = $this->getRequest()->getParam('search');
            if ($_search) {
                $_locale_from = $this->getRequest()->getParam('from');
                $_locale_to = $this->getRequest()->getParam('to');
                $translation = Mage::helper('translator/bing_translator')->translate($_search,$_locale_from, $_locale_to);
                if ($translation) {
                    $json = array('message' => base64_encode($translation));
                    $this->getResponse()->setBody(
                            json_encode($json)
                        );
                }
            }
        }
    }
    
    public function translateCmsAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $_search = $this->getRequest()->getParam('search');
            if ($_search) {
                $_helper = Mage::helper('translator/bing_locale'); 
                $_locale_from = $_helper->getBingLocale($this->getRequest()->getParam('from'));
                $_locale_to = $_helper->getBingLocale($this->getRequest()->getParam('to'));
                $translation = Mage::helper('translator/bing_translator')->translate($_search,$_locale_from, $_locale_to);
                if ($translation) {
                    $json = array('message' => base64_encode($translation));
                    $this->getResponse()->setBody(
                            json_encode($json)
                        );
                }
            }
        }
    }
    
    public function downloadAction()
    {
        if ($this->getRequest()->getParam('locale') && $this->getRequest()->getParam('files')) {
            $data = $this->getRequest();
            $_files = explode(',', $data->getParam('files'));
            if (count($_files) > 0) {
                Mage::getModel('translator/file')->downloadZip($data->getParam('locale'), $_files);
            }
        }
    }
    
    public function storeAction()
    {
        if ($this->getRequest()->getPost()) {
            $data = $this->getRequest();
            $_token = md5('unique_salt' . $data->getParam('timestamp'));
            if ($data->getParam('token') == $_token) {
                $_package_file = Mage::getModel('translator/file')->uploadPackageFile($data->getParam('package_locale'));
                if ($_package_file) {
                    Mage::dispatchEvent('translator_package_file_upload', array(
                                                                            'locale' => $data->getParam('package_locale'),
                                                                            'package_file' => $_package_file
                                                                            ));
                }
            }
        } else {
            $this->getResponse()->setBody(Mage::helper('translator')->__('Invalid HTTP method'));
        }
    }

}
