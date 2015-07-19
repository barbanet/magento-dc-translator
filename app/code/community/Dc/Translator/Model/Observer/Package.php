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

class Dc_Translator_Model_Observer_Package
{

    /**
     * Creates a new package based on an existing one.
     *
     * @param $observer
     */
    public function create($observer)
    {
        $now = now();
        $locale_base = Mage::getModel('translator/package')->getPackageByLocale($observer->getBaseLocale());
        if ($locale_base) {
            $locale_id = $observer->getLocaleId();
            $connection = Mage::getSingleton('core/resource')->getConnection('read');
            $table = Mage::getSingleton('core/resource')->getTableName('translator/key');
            $connection->query("DELETE FROM {$table} WHERE package_id = {$locale_id};");
            $connection->query("INSERT INTO {$table}(package_id, package_module, package_key, package_value, created_at, updated_at)
                                SELECT {$locale_id}, package_module, package_key, package_value, '{$now}', '{$now}' FROM {$table}
                                WHERE package_id = {$locale_base->getId()};");
            
        }
    }

    /**
     * Import and existing locale from file system.
     *
     * @param $observer
     */
    public function import($observer)
    {
        $modules = array_keys((array) Mage::getConfig()->getNode('modules'));
        sort($modules);
        $now = now();
        $locale_code = $observer->getLocale();
        $locale_id = $observer->getLocaleId();
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $locale_code;
        foreach ($modules as $name) {
            $config_file = Mage::getModuleDir('etc', $name) . DS . 'config.xml';
            $config_xml = new Varien_Simplexml_Config($config_file);
            foreach ($config_xml->getNode()->frontend->translate->modules as $values) {
                $frontend_name = $values->children()->getName();
            }
            $file = $config_xml->getNode()->frontend->translate->modules->$frontend_name->files->default;
            if (!$file) {
                foreach ($config_xml->getNode()->adminhtml->translate->modules as $values) {
                    $adminhtml_name = $values->children()->getName();
                }
                $file = $config_xml->getNode()->adminhtml->translate->modules->$adminhtml_name->files->default;
            }
            if (!$file) {
                foreach ($config_xml->getNode()->install->translate->modules as $values) {
                    $install_name = $values->children()->getName();
                }
                $file = $config_xml->getNode()->install->translate->modules->$install_name->files->default;
            }
            if ($file) {
                if (file_exists($path . DS . $file)) {
                    $file_content = file($path . DS . $file);
                    foreach ($file_content as $line) {
                        $values = explode('","', $line);
                        if (count($values) == 2) {
                            $key = substr($values[0], 1, strlen($values[0]));
                            $values[1] = trim($values[1]);
                            $translation = substr($values[1], 0, (strlen($values[1]) - 1));
                            $insert = array(
                                            'package_id' => $locale_id,
                                            'package_module' => $name,
                                            'package_key' => $key,
                                            'package_value' => $translation,
                                            'created_at' => $now,
                                            'updated_at' => $now
                                        );
                            Mage::getModel('translator/key')->setData($insert)->save();
                        }
                    }
                }
            }
            unset($config_xml);
        }
    }

    /**
     * Writes to file system the new csv files.
     *
     * @param $observer
     */
    public function generate($observer)
    {
        $package = $observer->getPackage();
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $package->getLocale() . DS;
        $file = new Varien_Io_File();
        $file->setAllowCreateFolders(true);
        $file->open(array (
            'path' => $path
        ));
        $connection = Mage::getSingleton('core/resource')->getConnection('read');
        $table_key = Mage::getSingleton('core/resource')->getTableName('translator/key');
        $table_file = Mage::getSingleton('core/resource')->getTableName('translator/file');
        $data = $connection->fetchAll("SELECT k.package_module, k.package_key, k.package_value, IFNULL(f.file_name, CONCAT(k.package_module,'.csv')) as file_name FROM {$table_key} k LEFT JOIN {$table_file} f ON (k.package_module = f.package_module) WHERE k.package_id = {$package->getId()} ORDER BY package_module ASC, package_key ASC;");
        if (is_array($data) && !empty($data)) {
            $file_name = false;
            foreach ($data as $value) {
                if ($value['file_name'] != $file_name) {
                    if (!empty($file_name)) {
                        $file->streamClose();
                    }
                    $file->streamOpen($value['file_name']);
                    $file_name = $value['file_name'];
                }
                $file->streamWrite("\"" . $value['package_key'] . "\",\"" . $value['package_value'] . "\"\n");
            }
            if (!empty($file_name)) {
                $file->streamClose();
            }
        }
    }

    /**
     * @param $observer
     */
    public function upload($observer)
    {
        $_modules = array_keys((array) Mage::getConfig()->getNode('modules'));
        sort($_modules);

        $now = now();
        $locale_code = $observer->getLocale();
        $locale_id = Mage::getSingleton('translator/package')->getPackageByLocale($observer->getLocale())->getPackageId();
        $package_file = $observer->getPackageFile();
        
        $path = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . $locale_code;
        
        foreach ($_modules as $_name) {
            $_config_file = Mage::getModuleDir('etc', $_name) . DS . 'config.xml';
            $_config_xml = new Varien_Simplexml_Config($_config_file);
            foreach ($_config_xml->getNode()->frontend->translate->modules as $values) {
                $_frontend_name = $values->children()->getName();
            }
            $file = $_config_xml->getNode()->frontend->translate->modules->$_frontend_name->files->default;
            if (!$file) {
                foreach ($_config_xml->getNode()->adminhtml->translate->modules as $values) {
                    $_adminhtml_name = $values->children()->getName();
                }
                $file = $_config_xml->getNode()->adminhtml->translate->modules->$_adminhtml_name->files->default;
            }
            if (!$file) {
                foreach ($_config_xml->getNode()->install->translate->modules as $values) {
                    $_install_name = $values->children()->getName();
                }
                $file = $_config_xml->getNode()->install->translate->modules->$_install_name->files->default;
            }
            if ($file == $package_file) {
                if (file_exists($path . DS . $file)) {
                    $file_content = file($path . DS . $file);
                    foreach ($file_content as $line) {
                        $values = explode('","', $line);
                        if (count($values) == 2) {
                            $key = substr($values[0], 1, strlen($values[0]));
                            $values[1] = trim($values[1]);
                            $translation = substr($values[1], 0, (strlen($values[1]) - 1));
                            $insert = array(
                                            'package_id' => $locale_id,
                                            'package_module' => $_name,
                                            'package_key' => $key,
                                            'package_value' => $translation,
                                            'created_at' => $now,
                                            'updated_at' => $now
                                        );
                            Mage::getModel('translator/key')->setData($insert)->save();
                        }
                    }
                }
            }
            unset($_config_xml);
        }
    }

}
