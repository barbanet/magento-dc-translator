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

class Dc_Translator_Adminhtml_Translator_PackageController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('system/translator');
        return $this;
    }   
 
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }
    
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }
 
    public function createAction()
    {
        $model = Mage::getModel('translator/package');
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        Mage::register('package_data', $model);
        $this->loadLayout();
        $this->_setActiveMenu('system/translator');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('translator/adminhtml_package_create'))
            ->_addLeft($this->getLayout()->createBlock('translator/adminhtml_package_create_tabs'));
        $this->renderLayout();
    }
    
    public function importAction()
    {
        $model = Mage::getModel('translator/package');
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        Mage::register('package_data', $model);
        $this->loadLayout();
        $this->_setActiveMenu('system/translator');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('translator/adminhtml_package_import'))
            ->_addLeft($this->getLayout()->createBlock('translator/adminhtml_package_import_tabs'));
        $this->renderLayout();
    }
    
    public function refreshAction()
    {
        $model = Mage::getModel('translator/module');
        try {
            $model->refreshModules();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Modules list successfully updated.'));
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Unable to refresh Modules'));
        $this->_redirect('*/*/');
    }
    
    public function saveImportAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('translator/package');
            try {
                $model->setLocale($data['pending_locale']);
                $model->setCreatedAt(now());
                $model->setUpdatedAt(now());
                $model->save();
                Mage::dispatchEvent('translator_package_import', array(
                                                                            'locale' => $data['pending_locale'],
                                                                            'locale_id' => $model->getId()
                                                                            ));
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Locale was successfully imported'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/import');
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Unable to find Locale to import'));
        $this->_redirect('*/*/');
    }
    
    public function saveCreateAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('translator/package');
            try {
                $model->setLocale($data['locale']);
                $model->setCreatedAt(now());
                $model->setUpdatedAt(now());
                $model->save();
                if ($data['base_locale']) {
                    Mage::dispatchEvent('translator_package_create', array(
                                                                                'base_locale' => $data['base_locale'],
                                                                                'locale' => $data['locale'],
                                                                                'locale_id' => $model->getId()
                                                                                ));
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Locale was successfully created'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/*');
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Unable to find Locale to create'));
        $this->_redirect('*/*/');
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('translator/package')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('package_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('system/translator');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('translator/adminhtml_package_edit'))
                ->_addLeft($this->getLayout()->createBlock('translator/adminhtml_package_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Package does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('translator/package')->load($this->getRequest()->getParam('id'));
            try {
                if ($model->getId()) {
                    $model->setUpdatedAt(now());
                    $model->save();
                    //TODO: Validar antes el tema de los permisos con un helper
                    Mage::dispatchEvent('translator_package_generate', array('package' => $model));
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Package was successfully generated'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $model->getId()));
                        return;
                    }
                    $this->_redirect('*/*/');
                    return;
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Unable to find Package to save'));
        $this->_redirect('*/*/');
    }
    
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('translator/package');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Package was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction()
    {
        $packageIds = $this->getRequest()->getParam('packages');
        if(!is_array($packageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($packageIds as $packageId) {
                    $package = Mage::getModel('translator/package')->load($packageId);
                    $package->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($packageIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}
