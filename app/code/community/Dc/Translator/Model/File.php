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
        $model = Mage::getModel('translator/file')->getCollection()
                    ->addFieldToFilter('package_module', array('eq' => $data['package_module']));
        if ($model->getSize() > 0) {
            $package = Mage::getModel('translator/file')->load($model->getFirstItem()->getFileId());
            $package->setFileName($data['file_name']);
            $package->save();
        } else {
            Mage::getModel('translator/file')->setData($data)->save();
        }
        return true;
    }

    /**
     * Get the list of existing files inside a package.
     *
     * @param $locale
     * @return array
     */
    public function getExistingFiles($locale)
    {
        $files = array();
        $list = array();
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $locale;
        if (file_exists($path)) {
            $directory = dir($path);
            while ($file = $directory->read()) {
                if (strrpos($file, '.csv')) {
                    $list[strtolower($file)] = $file;
                }
            }
            ksort($list, SORT_STRING);
            foreach ($list as $item) {
                $files[$item] = array('value' => $item, 'label' => $item);
            }
            $directory->close();
        }
        return $files;
    }

    /**
     * Creates a zip file on the fly.
     *
     * @param $locale
     * @param array $files
     */
    public function downloadZip($locale, array $files)
    {
        $filename = $locale . '-package.zip';
        $zip = Mage::helper('translator/zip');
        $zip->init($filename);
        $date = new Zend_Date(Mage::app()->getLocale()->date(now()), Zend_Date::ISO_8601);
        $data = sprintf('File downloaded from %s by %s on %s',
                            Mage::getBaseUrl(),
                            Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname(),
                            Mage::helper('core')->formatDate($date, 'medium', true)
                        );
        $zip->add_file('README.txt', $data);
        $path = Mage::getBaseDir(). DS . 'app' . DS . 'locale' . DS . $locale;
        foreach ($files as $file) {
            $zip->add_file_from_path($file, $path . DS . $file);
        }
        $zip->finish();
    }

    /**
     * @param $path
     * @return bool
     */
    private function checkFolderExists($path)
    {
        try {
            $io_proxy = new Varien_Io_File();
            $io_proxy->setAllowCreateFolders(true);
            $io_proxy->open(array($path));
            $io_proxy->close();
            unset($io_proxy);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Used to process Uploadify request.
     *
     * @param $locale
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function uploadPackageFile($locale)
    {
        $file_name = false;
        if (isset($_FILES['package_file']['name'])) {
            $path = $this->_getPackageFilesPath($locale);
            $folder_exists = $this->checkFolderExists($path);
            if ($folder_exists) {
                $uploader = new Varien_File_Uploader('package_file');
                $uploader->setAllowedExtensions(array('csv'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $uploader->getUploadedFileName());
                $file_name = $uploader->getUploadedFileName();
            } else {
                throw new Mage_Core_Exception(Mage::helper('translator')->__("Image can't be uploaded"));
            }
        }
        return $file_name;
    }

    /**
     * Returns package path.
     *
     * @param $locale
     * @return string
     */
    private function _getPackageFilesPath($locale)
    {
        return Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $locale . DS;
    }

}
