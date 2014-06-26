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

class Dc_Translator_Block_Adminhtml_Package_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('packageGrid');
        $this->setDefaultSort('package_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('translator/package')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('package_id', array(
            'header'    => Mage::helper('translator')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'package_id'
        ));
        $this->addColumn('locale', array(
            'header'    => Mage::helper('translator')->__('Language'),
            'align'     =>'left',
            'index'     => 'locale',
            'type'      => 'options',
            'options'   => Mage::getModel('translator/package')->toOptionArray()
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('translator')->__('Created At'),
            'align'     =>'left',
            'index'     => 'created_at',
            'type'      => 'datetime',
            'format'    => $dateFormatIso,
            'width'     => '180px' 
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('translator')->__('Last Update'),
            'align'     =>'left',
            'index'     => 'updated_at',
            'type'      => 'datetime',
            'format'    => $dateFormatIso,
            'width'     => '180px'
        ));
        $this->addColumn('action_edit',
            array(
                'header'    =>  Mage::helper('translator')->__('Package'),
                'width'     => '60px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('translator')->__('Manage'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => false,
            )
        );
        $this->addColumn('action_manage',
            array(
                'header'    =>  Mage::helper('translator')->__('Keys'),
                'width'     => '60px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('translator')->__('View'),
                        'url'       => array('base'=> '*/adminhtml_key'),
                        'field'     => 'package_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => false,
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('package_id');
        $this->getMassactionBlock()->setFormFieldName('packages');
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
        return $this->getUrl('*/adminhtml_key', array('package_id' => $row->getId()));
    }

}
