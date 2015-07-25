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

class Dc_Translator_Adminhtml_Translator_AjaxController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/translator/packages');
    }

    /**
     * Translate a given string using Bing's API.
     */
    public function translateAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $search = $this->getRequest()->getParam('search');
            if ($search) {
                $locale_from = $this->getRequest()->getParam('from');
                $locale_to = $this->getRequest()->getParam('to');
                $translation = Mage::helper('translator/bing_translator')->translate($search,$locale_from, $locale_to);
                if ($translation) {
                    $json = array('message' => base64_encode($translation));
                    $this->getResponse()->setBody(
                            json_encode($json)
                        );
                }
            }
        }
    }

    /**
     * @deprecated
     */
    public function translateCmsAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $search = $this->getRequest()->getParam('search');
            if ($search) {
                $helper = Mage::helper('translator/bing_locale');
                $locale_from = $helper->getBingLocale($this->getRequest()->getParam('from'));
                $locale_to = $helper->getBingLocale($this->getRequest()->getParam('to'));
                $translation = Mage::helper('translator/bing_translator')->translate($search,$locale_from, $locale_to);
                if ($translation) {
                    $json = array('message' => base64_encode($translation));
                    $this->getResponse()->setBody(
                            json_encode($json)
                        );
                }
            }
        }
    }

    /**
     * Download selected package files into a single zip file.
     */
    public function downloadAction()
    {
        if ($this->getRequest()->getParam('locale') && $this->getRequest()->getParam('files')) {
            $data = $this->getRequest();
            $files = explode(',', $data->getParam('files'));
            if (count($files) > 0) {
                Mage::getModel('translator/file')->downloadZip($data->getParam('locale'), $files);
            }
        }
    }

    /**
     * Adds a new file to the file system and also stores the values into the module.
     */
    public function storeAction()
    {
        if ($this->getRequest()->getPost()) {
            $data = $this->getRequest();
            $token = md5('unique_salt' . $data->getParam('timestamp'));
            if ($data->getParam('token') == $token) {
                $package_file = Mage::getModel('translator/file')->uploadPackageFile($data->getParam('package_locale'));
                if ($package_file) {
                    Mage::dispatchEvent('translator_package_file_upload', array(
                                                                            'locale' => $data->getParam('package_locale'),
                                                                            'package_file' => $package_file
                                                                            ));
                }
            }
        } else {
            $this->getResponse()->setBody(Mage::helper('translator')->__('Invalid HTTP method'));
        }
    }

}
