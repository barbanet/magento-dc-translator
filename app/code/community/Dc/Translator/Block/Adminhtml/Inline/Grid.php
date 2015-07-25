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

class Dc_Translator_Block_Adminhtml_Inline_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('inlineGrid');
        $this->setDefaultSort('string');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('translator/inline')->getCollection();
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
        $this->addColumn('string', array(
            'header'    => Mage::helper('translator')->__('String'),
            'align'     =>'left',
            'index'     => 'string'
        ));
        $this->addColumn('store_id', array(
            'header'    => Mage::helper('translator')->__('Store ID'),
            'index'     => 'store_id',
            'type'      => 'options',
            'options'   => Mage::getSingleton('translator/inline')->getStoreOptions()
        ));
        $this->addColumn('translate', array(
            'header'    => Mage::helper('translator')->__('Translate'),
            'align'     =>'left',
            'index'     => 'translate'
        ));
        $this->addColumn('locale', array(
            'header'    => Mage::helper('translator')->__('Locale'),
            'align'     =>'left',
            'index'     => 'locale',
            'type'      => 'options',
            'options'   => Mage::getModel('translator/package')->getLocales()
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
        $this->getMassactionBlock()->setFormFieldName('inline_keys');
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
