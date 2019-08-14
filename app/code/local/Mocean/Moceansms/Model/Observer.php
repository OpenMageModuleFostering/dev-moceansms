<?php
require_once dirname(dirname(__FILE__)). '/vendor/autoload.php';
class Mocean_Moceansms_Model_Observer
{
	public function sendSmsOnOrderCreated(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrdersEnabled())
		{
			$orders = $observer->getEvent()->getOrderIds();

			if(!empty($orders))
			{
			$order = Mage::getModel('sales/order')->load($orders['0']);
			$order_inc_id = $order->getIncrementId();
			if ($order instanceof Mage_Sales_Model_Order)
			{
				$api_url = "https://rest-api.moceansms.com/rest/1/sms";
				$key = $this->getHelper()->getAPIKey();
				$secret = $this->getHelper()->getAPISecret();
				$from = $this->getHelper()->getMoceanFrom();
				if(is_numeric($from)){
					if(strlen($from) > 20){
						$from = "SMS";
					}
				}
				else{
					if(strlen($from) > 11){
						$from = "SMS";
					}
				}
				$adminNo = urlencode($this->getHelper()->filterPrefixNumber($this->getHelper()->getAdminMobile(), $order));

				$sent_method = $this->getHelper()->getSentMethod();
				$smsto = urlencode($this->getHelper()->getTelephoneFromOrder($order));
				$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		        $phoneNumber = $phoneNumberUtil->parse($smsto, $this->getHelper()->getCountry($order));
		        if($phoneNumberUtil->isValidNumber($phoneNumber) && $phoneNumberUtil->getNumberType($phoneNumber) == 1) {
		            $smsto = $phoneNumberUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
					$smsto = str_replace("+","",$smsto);
				}
				else{
					$error_message = "Error: Invalid Number! (".$smsto.")";
					$smsto = '';
				}

				$smsmsg = urlencode($this->getHelper()->getMessage($order));
				Mage::log("Order placed!",null,'moceansms.log');
				/*handling error*/
				$flag = 1;

				if($key=='' || $secret=='')
				{
					$flag = 0;
					$error_message = "Error: Send SMS - Not able to send SMS. Make sure that your provided correct Mocean Key and Secret.";
				}

				if($flag==1 && $smsto!='')
				{

					$method = 'POST';
					$post_data = '';
					$post_data .= 'mocean-api-key=' . $key;
					$post_data .= '&mocean-api-secret=' . $secret;
					$post_data .= '&mocean-from=' . $from;
					$post_data .= '&mocean-text=' . $smsmsg;
					$post_data .= '&mocean-to=' . $smsto;
					$url = $api_url;
					$sendSms = $this->getHelper()->sendSms($url,$post_data);
					Mage::log("response: ".$sendSms,null,'moceansms.log');
					if($this->getHelper()->isAdminEnabled() && $adminNo != ''){

						$smsmsg = urlencode($this->getHelper()->getAdminMessage($order));

						$post_data = '';
						$post_data .= 'mocean-api-key=' . $key;
						$post_data .= '&mocean-api-secret=' . $secret;
						$post_data .= '&mocean-from=' . $from;
						$post_data .= '&mocean-text=' . $smsmsg;
						$post_data .= '&mocean-to=' . $adminNo;
						$url = $api_url;
						$sendSms = $this->getHelper()->sendSms($url,$post_data);
						Mage::log("response: ".$sendSms,null,'moceansms.log');
					}

					Mage::log($sendSms,null,'moceansms.log');
					Mage::log($post_data,null,'moceansms.log');

				}
				else
				{
					Mage::log($error_message,null,'moceansms.log');
				}
			}
			} // end empty
			else
			{
				Mage::log("Error: order create - $order_inc_id - Not able to call observer. ",null,'moceansms.log');
			}
		}
	}

	public function sendSmsOnOrderHold(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrderHoldEnabled())
		{
			$order = $observer->getOrder();
				if ($order instanceof Mage_Sales_Model_Order)
				{

					if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_HOLDED)
					{
						$order_inc_id = $order->getIncrementId();
						$api_url = "https://rest-api.moceansms.com/rest/1/sms";
						$key = $this->getHelper()->getAPIKey();
						$secret = $this->getHelper()->getAPISecret();
						$from = $this->getHelper()->getMoceanFrom();
						if(is_numeric($from)){
							if(strlen($from) > 20){
								$from = "SMS";
							}
						}
						else{
							if(strlen($from) > 11){
								$from = "SMS";
							}
						}
						$sent_method = $this->getHelper()->getSentMethod();
						$smsto = urlencode($this->getHelper()->getTelephoneFromOrder($order));
						$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
				        $phoneNumber = $phoneNumberUtil->parse($smsto, $this->getHelper()->getCountry($order));
				        if($phoneNumberUtil->isValidNumber($phoneNumber) && $phoneNumberUtil->getNumberType($phoneNumber) == 1) {
				            $smsto = $phoneNumberUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
							$smsto = str_replace("+","",$smsto);
						}
						else{
							$error_message = "Error: Invalid Number! (".$smsto.")";
							$smsto = '';
						}
						$smsmsg = urlencode($this->getHelper()->getMessageForOrderHold($order));
						Mage::log("Order onhold!",null,'moceansms.log');
						/*handling error*/
						$flag = 1;

						if($key=='' || $secret=='')
						{
							$flag = 0;
							$error_message = "Error: Send SMS - Not able to send SMS. Make sure that your provided correct Mocean Key and Secret.";
						}

						if($flag==1 && $smsto!='')
						{

							$method = 'POST';
							$post_data = '';
							$post_data .= 'mocean-api-key=' . $key;
							$post_data .= '&mocean-api-secret=' . $secret;
							$post_data .= '&mocean-from=' . $from;
							$post_data .= '&mocean-text=' . $smsmsg;
							$post_data .= '&mocean-to=' . $smsto;
							$url = $api_url;
							$sendSms = $this->getHelper()->sendSms($url,$post_data);

							Mage::log($sendSms,null,'moceansms.log');
							Mage::log($post_data,null,'moceansms.log');

						}
						else
						{
							Mage::log($error_message,null,'moceansms.log');
						}
						Mage::log("fin",null,'moceansms.log');
					}
				} // end state
				else
				{
					Mage::log("Error: order hold - $order_inc_id - Not able to call observer.",null,'moceansms.log');
				}

		}
	}

	public function sendSmsOnOrderUnhold(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrderUnholdEnabled())
		{
			$order = $observer->getOrder();
				if ($order instanceof Mage_Sales_Model_Order)
				{

					if ($order->getState() !== $order->getOrigData('state') && $order->getOrigData('state') === Mage_Sales_Model_Order::STATE_HOLDED)
					{
						$order_inc_id = $order->getIncrementId();
						$api_url = "https://rest-api.moceansms.com/rest/1/sms";
						$key = $this->getHelper()->getAPIKey();
						$secret = $this->getHelper()->getAPISecret();
						$from = $this->getHelper()->getMoceanFrom();
						if(is_numeric($from)){
							if(strlen($from) > 20){
								$from = "SMS";
							}
						}
						else{
							if(strlen($from) > 11){
								$from = "SMS";
							}
						}
						$sent_method = $this->getHelper()->getSentMethod();
						$smsto = urlencode($this->getHelper()->getTelephoneFromOrder($order));
						$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
				        $phoneNumber = $phoneNumberUtil->parse($smsto, $this->getHelper()->getCountry($order));
				        if($phoneNumberUtil->isValidNumber($phoneNumber) && $phoneNumberUtil->getNumberType($phoneNumber) == 1) {
				            $smsto = $phoneNumberUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
							$smsto = str_replace("+","",$smsto);
						}
						else{
							$error_message = "Error: Invalid Number! (".$smsto.")";
							$smsto = '';
						}
						$smsmsg = urlencode($this->getHelper()->getMessageForOrderUnhold($order));
						Mage::log("Order unhold!",null,'moceansms.log');
						/*handling error*/
						$flag = 1;

						if($key=='' || $secret=='')
						{
							$flag = 0;
							$error_message = "Error: Send SMS - Not able to send SMS. Make sure that your provided correct Mocean Key and Secret.";
						}

						if($flag==1 && $smsto!='')
						{

							$method = 'POST';
							$post_data = '';
							$post_data .= 'mocean-api-key=' . $key;
							$post_data .= '&mocean-api-secret=' . $secret;
							$post_data .= '&mocean-from=' . $from;
							$post_data .= '&mocean-text=' . $smsmsg;
							$post_data .= '&mocean-to=' . $smsto;
							$url = $api_url;
							$sendSms = $this->getHelper()->sendSms($url,$post_data);

							Mage::log($sendSms,null,'moceansms.log');
							Mage::log($post_data,null,'moceansms.log');

						}
						else
						{
							Mage::log($error_message,null,'moceansms.log');
						}
						Mage::log("fin",null,'moceansms.log');
					}
				} // end state
				else
				{
					Mage::log("Error: order unhold - $order_inc_id - Not able to call observer.",null,'moceansms.log');
				}

		}
	}

	public function sendSmsOnOrderCanceled(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isOrderCanceledEnabled())
		{
				$order = $observer->getOrder();
				if ($order instanceof Mage_Sales_Model_Order)
				{
					if ($order->getState() !== $order->getOrigData('state') && $order->getState() === Mage_Sales_Model_Order::STATE_CANCELED)
					{
							$order_inc_id = $order->getIncrementId();
							$api_url = "https://rest-api.moceansms.com/rest/1/sms";
							$key = $this->getHelper()->getAPIKey();
							$secret = $this->getHelper()->getAPISecret();
							$from = $this->getHelper()->getMoceanFrom();
							if(is_numeric($from)){
								if(strlen($from) > 20){
									$from = "SMS";
								}
							}
							else{
								if(strlen($from) > 11){
									$from = "SMS";
								}
							}
							$sent_method = $this->getHelper()->getSentMethod();
							$smsto = urlencode($this->getHelper()->getTelephoneFromOrder($order));
							$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
					        $phoneNumber = $phoneNumberUtil->parse($smsto, $this->getHelper()->getCountry($order));
					        if($phoneNumberUtil->isValidNumber($phoneNumber) && $phoneNumberUtil->getNumberType($phoneNumber) == 1) {
					            $smsto = $phoneNumberUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
								$smsto = str_replace("+","",$smsto);
							}
							else{
								$error_message = "Error: Invalid Number! (".$smsto.")";
								$smsto = '';
							}
							$smsmsg = urlencode($this->getHelper()->getMessageForOrderCanceled($order));
							Mage::log("Order cancelled!",null,'moceansms.log');
							/*handling error*/
							$flag = 1;

							if($key=='' || $secret=='')
							{
								$flag = 0;
								$error_message = "Error: Send SMS - Not able to send SMS. Make sure that your provided correct Mocean Key and Secret.";
							}

							if($flag==1 && $smsto!='')
							{

								$method = 'POST';
								$post_data = '';
								$post_data .= 'mocean-api-key=' . $key;
								$post_data .= '&mocean-api-secret=' . $secret;
								$post_data .= '&mocean-from=' . $from;
								$post_data .= '&mocean-text=' . $smsmsg;
								$post_data .= '&mocean-to=' . $smsto;
								$url = $api_url;
								$sendSms = $this->getHelper()->sendSms($url,$post_data);

								Mage::log($sendSms,null,'moceansms.log');
								Mage::log($post_data,null,'moceansms.log');

							}
							else
							{
								Mage::log($error_message,null,'moceansms.log');
							}
							Mage::log("fin",null,'moceansms.log');
					}
				} // end state
				else
				{
					Mage::log("Error: order cancel - $order_inc_id - Not able to call observer.",null,'moceansms.log');
				}

		}
	}

	public function sendSmsOnShipmentCreated(Varien_Event_Observer $observer)
	{
		if($this->getHelper()->isShipmentsEnabled())
		{
			Mage::log("ITEM SIHIPPED.",null,'moceansms.log');
				$shipment = $observer->getEvent()->getShipment();
				$order = $shipment->getOrder();

				if ($order instanceof Mage_Sales_Model_Order)
				{
					$order_inc_id = $order->getIncrementId();
					$api_url = "https://rest-api.moceansms.com/rest/1/sms";
					$key = $this->getHelper()->getAPIKey();
					$secret = $this->getHelper()->getAPISecret();
					$from = $this->getHelper()->getMoceanFrom();
					if(is_numeric($from)){
						if(strlen($from) > 20){
							$from = "SMS";
						}
					}
					else{
						if(strlen($from) > 11){
							$from = "SMS";
						}
					}
					$sent_method = $this->getHelper()->getSentMethod();
					$smsto = urlencode($this->getHelper()->getTelephoneFromOrder($order));
					$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			        $phoneNumber = $phoneNumberUtil->parse($smsto, $this->getHelper()->getCountry($order));
			        if($phoneNumberUtil->isValidNumber($phoneNumber) && $phoneNumberUtil->getNumberType($phoneNumber) == 1) {
			            $smsto = $phoneNumberUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
						$smsto = str_replace("+","",$smsto);
					}
					else{
						$error_message = "Error: Invalid Number! (".$smsto.")";
						$smsto = '';
					}
					$smsmsg = urlencode($this->getHelper()->getMessageForShipment($order));
					Mage::log("Shipment msg sent!",null,'moceansms.log');
					/*handling error*/
					$flag = 1;

					if($key=='' || $secret=='')
					{
						$flag = 0;
						$error_message = "Error: Send SMS - Not able to send SMS. Make sure that your provided correct Mocean Key and Secret.";
					}

					if($flag==1 && $smsto!='')
					{

						$method = 'POST';
						$post_data = '';
						$post_data .= 'mocean-api-key=' . $key;
						$post_data .= '&mocean-api-secret=' . $secret;
						$post_data .= '&mocean-from=' . $from;
						$post_data .= '&mocean-text=' . $smsmsg;
						$post_data .= '&mocean-to=' . $smsto;
						$url = $api_url;
						$sendSms = $this->getHelper()->sendSms($url,$post_data);

						Mage::log($sendSms,null,'moceansms.log');
						Mage::log($post_data,null,'moceansms.log');

					}
					else
					{
						Mage::log($error_message,null,'moceansms.log');
					}
					Mage::log("fin",null,'moceansms.log');

				} // end state
				else
				{
					Mage::log("Error: order shipment - $order_inc_id - Not able to call observer.",null,'moceansms.log');
				}

		}
	}

	public function getHelper()
    {
        return Mage::helper('moceansms/Data');
    }
}
