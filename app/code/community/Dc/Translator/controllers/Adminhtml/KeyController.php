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

class Dc_Translator_Adminhtml_KeyController extends Mage_Adminhtml_Controller_Action
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
    
    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('translator/key')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('key_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('system/translator');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('translator/adminhtml_key_edit'))
                ->_addLeft($this->getLayout()->createBlock('translator/adminhtml_key_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Key does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('translator/key');
            try {
                if ($this->getRequest()->getPost('mass_update') && $this->getRequest()->getPost('mass_update') == 1) {
                    Mage::helper('translator')->keyMassUpdate($data['package_id'], $model->load($this->getRequest()->getParam('id'))->getPackageKey(), $data['package_value']);
                } else {
                    if ($this->getRequest()->getParam('id') > 0) {
                        $model->setPackageValue($data['package_value'])
                            ->setUpdatedAt(now())
                            ->setId($this->getRequest()->getParam('id'));
                        $model->save();
                    } else {
                        $model->setData($data)
                            ->setUpdatedAt(now())
                            ->setCreatedAt(now())
                            ->setId($this->getRequest()->getParam('id'));
                        $model->save();
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Key was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                //TODO: Cambiar a propiedad del model.
                $this->_redirect('*/*/', array('package_id' => $data['package_id']));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('translator')->__('Unable to find Key to save'));
        $this->_redirect('*/adminhtml_package/');
    }
    
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('translator/key')->load($this->getRequest()->getParam('id'));
                if ($model) {
                    $_package_id = $model->getPackageId();
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('translator')->__('Key was successfully deleted'));
                $this->_redirect('*/*/', array('package_id' => $_package_id));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/adminhtml_package/');
    }
    
    public function massDeleteAction()
    {
        $keysIds = $this->getRequest()->getParam('keys');
        if(!is_array($keysIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $_package_id = null;
                foreach ($keysIds as $keysId) {
                    $key = Mage::getModel('translator/key')->load($keysId);
                    $_package_id = $key->getPackageId();
                    $key->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($keysIds)
                    )
                );
                $this->_redirect('*/*/index', array('package_id' => $_package_id));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/adminhtml_package/');
    }

}
