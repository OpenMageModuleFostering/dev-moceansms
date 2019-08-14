<?php

class Mocean_Moceansms_Adminhtml_MoceansmsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('customer/customer')
			->_addBreadcrumb(Mage::helper('moceansms')->__('Items Manager'), Mage::helper('moceansms')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
	
		$this->_initAction();
		$this->loadLayout()
			->_setActiveMenu('customer/customer')
			->renderLayout();
	}

	public function sendsmsAction()
	{
		extract($_POST);
		if(isset($_POST)){
			$phone_array = explode(',',$phone_numbers);
			//Gateway credentials  
					
			//$api_url = Mage::helper('moceansms')->getAPI();
			$api_url = "https://rest-api.moceansms.com/rest/1/sms";
			$key = Mage::helper('moceansms')->getAPIKey();
			$secret = Mage::helper('moceansms')->getAPISecret();
			$from = 'donald';

			/*handling error*/
			$flag = 1;
			$error_message = "Error: Send SMS - Not able to send SMS. Make sure that your provided correct Mocean Key and Secret.";
			if($key=='' || $secret=='')
			{
				$flag = 0;
			}

			/*check balance*/
			$data = '';
			$data .= 'mocean-api-key=' . $key;
			$data .= '&mocean-api-secret=' . $secret;
		
			$method = 'GET';
			$url = 'https://rest-api.moceansms.com/rest/1/account/balance?'.$data;
			$checkBal = Mage::helper('moceansms')->sendSms($url);			
			Mage::log('bal='.$checkBal,null,'moceansms.log');

				
			if($flag==1)
			{			
				for($i=0;$i<count($phone_array);$i++)
				{
					if($phone_array[$i]!='')
					{

						$post_data = '';
						$post_data .= 'mocean-api-key=' . $key;
						$post_data .= '&mocean-api-secret=' . $secret;
						$post_data .= '&mocean-from=' . $from;
						$post_data .= '&mocean-text=' . $message;
						$post_data .= '&mocean-to=' . $phone_array[$i];
					
						$url = $api_url;
						$sendSms = Mage::helper('moceansms')->sendSms($url,$post_data);				
						
					}
				}
				Mage::getSingleton('core/session')->setSmsstatus('success');
				Mage::getSingleton('core/session')->setSmsmsg('Message sent successfully.');
				$this->_redirect('*/*/');
			}
			else
			{
				Mage::log($error_message,null,'moceansms.log');
			}
		}
		else
		{
			Mage::getSingleton('core/session')->setSmsstatus('error');
			Mage::getSingleton('core/session')->setSmsmsg('Unable to send SMS.');
			$this->_redirect('*/*/');
		}
		
		$this->loadLayout();
		$this->renderLayout();
	}	
}