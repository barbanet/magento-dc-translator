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

class Dc_Translator_Model_File extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('translator/file');
    }

    /**
     * @param $data
     * @return bool
     */
    public function saveByModule($data)
    {
        $_model = Mage::getModel('translator/file')->getCollection()
                    ->addFieldToFilter('package_module', array('eq' => $data['package_module']));
        if ($_model->getSize() > 0) {
            $_package = Mage::getModel('translator/file')->load($_model->getFirstItem()->getFileId());
            $_package->setFileName($data['file_name']);
            $_package->save();
        } else {
            Mage::getModel('translator/file')->setData($data)->save();
        }
        return true;
    }
    
    public function getExistingFiles($locale)
    {
        $_files = array();
        $_list = array();
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $locale;
        if (file_exists($path)) {
            $directory = dir($path);
            while ($file = $directory->read()) {
                if (strrpos($file, '.csv')) {
                    $_list[strtolower($file)] = $file;
                }
            }
            ksort($_list, SORT_STRING);
            foreach ($_list as $_item) {
                $_files[$_item] = array('value' => $_item, 'label' => $_item);
            }
            $directory->close();
        }
        return $_files;
    }
    
    public function downloadZip($locale, array $files)
    {
        $_filename = $locale . '-package.zip';
        $_zip = Mage::helper('translator/zip');
        $_zip->init($_filename);
        $_date = new Zend_Date(Mage::app()->getLocale()->date(now()), Zend_Date::ISO_8601);
        $_data = sprintf('File downloaded from %s by %s on %s',
                            Mage::getBaseUrl(),
                            Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname(),
                            Mage::helper('core')->formatDate($_date, 'medium', true)
                        );
        $_zip->add_file('README.txt', $_data);
        $_path = Mage::getBaseDir(). DS . 'app' . DS . 'locale' . DS . $locale;
        foreach ($files as $file) {
            $_zip->add_file_from_path($file, $_path . DS . $file);
        }
        $_zip->finish();
    }
    
    private function _checkFolderExists($path)
    {
        try {
            $ioProxy = new Varien_Io_File();
            $ioProxy->setAllowCreateFolders(true);
            $ioProxy->open(array($path));
            $ioProxy->close();
            unset($ioProxy);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Used to process Uploadify request.
     */    
    public function uploadPackageFile($locale)
    {
        $_file_name = false;
        if (isset($_FILES['package_file']['name'])) {
            $path = $this->_getPackageFilesPath($locale);
            $_folder_exists = $this->_checkFolderExists($path);
            if ($_folder_exists) {
                $uploader = new Varien_File_Uploader('package_file');
                $uploader->setAllowedExtensions(array('csv'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $uploader->getUploadedFileName());
                $_file_name = $uploader->getUploadedFileName();
            } else {
                throw new Mage_Core_Exception(Mage::helper('translator')->__("Image can't be uploaded"));
            }
        }
        return $_file_name;
    }
    
    private function _getPackageFilesPath($locale)
    {
        return Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $locale . DS;
    }

}
