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
 * @version    1.0.0
 */

class Dc_Translator_Block_Adminhtml_Key_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('keyGrid');
        $this->setDefaultSort('package_key');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    private function _getPackageId()
    {
        return $this->getRequest()->getParam('package_id');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('translator/key')->getCollection()
                        ->addFieldToFilter('package_id', array('eq' => $this->_getPackageId()));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('key_id', array(
            'header'    => Mage::helper('translator')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'key_id'
        ));
        $this->addColumn('package_module', array(
            'header'    => Mage::helper('translator')->__('Module'),
            'align'     =>'left',
            'index'     => 'package_module',
            'type'      => 'options',
            'options'   => Mage::getModel('translator/key')->getPackageModules($this->_getPackageId())
        ));
        $this->addColumn('package_key', array(
            'header'    => Mage::helper('translator')->__('Key'),
            'align'     =>'left',
            'index'     => 'package_key'
        ));
        $this->addColumn('package_value', array(
            'header'    => Mage::helper('translator')->__('Value'),
            'align'     =>'left',
            'index'     => 'package_value'
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('translator')->__('Created At'),
            'align'     =>'left',
            'index'     => 'created_at',
            'type'      => 'datetime',
            'format'    => $dateFormatIso,
            'width'     => '150px' 
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('translator')->__('Last Update'),
            'align'     =>'left',
            'index'     => 'updated_at',
            'type'      => 'datetime',
            'format'    => $dateFormatIso,
            'width'     => '150px'
        ));
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('translator')->__('Action'),
                'width'     => '60px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('translator')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => false,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('key_id');
        $this->getMassactionBlock()->setFormFieldName('keys');
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('translator')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('translator')->__('Are you sure?')
        ));
        return $this;
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
