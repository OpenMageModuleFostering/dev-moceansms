<?php
class Mocean_Moceansms_Block_Adminhtml_Moceansms extends Mage_Adminhtml_Block_Template
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_moceansms';
    $this->setFormAction(Mage::getUrl('*/*/sendsms'));
    $this->_headerText = Mage::helper('moceansms')->__('Send SMS');
  
  }

}