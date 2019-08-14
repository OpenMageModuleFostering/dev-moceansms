<?php

class Mocean_Moceansms_Helper_Data extends Mage_Core_Helper_Data
{
	const CONFIG_PATH = 'moceansms/';

	public function isAdminEnabled()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'admin/enabled');
	}

	public function isOrdersEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/enabled');
    }

	public function isOrderHoldEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/enabled');
    }

	public function isOrderUnholdEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/enabled');
    }

	public function isOrderCanceledEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/enabled');
    }

	public function isShipmentsEnabled()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'shipments/enabled');
    }

	public function getAPI()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/api');
	}

	public function getAPIKey()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/api_key');
	}

	public function getAPISecret()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/api_secret');
	}

	public function getAdminMobile()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'admin/admin_mobile');
	}

	public function getMoceanFrom()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/mocean_from');
	}

	public function getMessageField()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/message_field');
	}

	public function getToField()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/to_field');
	}

	public function getSentMethod()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'general/urlmethod');
	}

	public function getSender()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/sender');
	}

	public function getSenderForOrderHold()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/sender');
	}

	public function getSenderForOrderUnhold()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/sender');
	}

	public function getSenderForOrderCanceled()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/sender');
	}

	public function getSenderForShipment()
	{
		return Mage::getStoreConfig(self::CONFIG_PATH.'shipments/sender');
	}

	public function getAdminMessage(Mage_Sales_Model_Order $order)
	{

		$store = Mage::app()->getStore();
		$sitename = $store->getName();
		$amount = $order->getGrandTotal() - $order->getShippingAmount();
		$billingAddress = $order->getBillingAddress();
		$whatArray = array('{{sitename}}','{{amount}}','{{order_id}}');
		$withWhatArray = array($sitename,$amount,$order->getIncrementId());

		return str_replace($whatArray,$withWhatArray,Mage::getStoreConfig(self::CONFIG_PATH.'admin/message'));
	}

	public function getMessage(Mage_Sales_Model_Order $order)
	{
		$billingAddress = $order->getBillingAddress();
		$whatArray = array('{{firstname}}','{{order_id}}');
		$withWhatArray = array($billingAddress->getFirstname(),$order->getIncrementId());

		return str_replace($whatArray,$withWhatArray,Mage::getStoreConfig(self::CONFIG_PATH.'orders/message'));
	}

	public function getMessageForOrderHold(Mage_Sales_Model_Order $order)
	{
		$billingAddress = $order->getBillingAddress();
		$whatArray = array('{{firstname}}','{{order_id}}');
		$withWhatArray = array($billingAddress->getFirstname(),$order->getIncrementId());

		return str_replace($whatArray,$withWhatArray,Mage::getStoreConfig(self::CONFIG_PATH.'order_hold/message'));
	}

	public function getMessageForOrderUnhold(Mage_Sales_Model_Order $order)
	{
		$billingAddress = $order->getBillingAddress();
		$whatArray = array('{{firstname}}','{{order_id}}');
		$withWhatArray = array($billingAddress->getFirstname(),$order->getIncrementId());

		return str_replace($whatArray,$withWhatArray,Mage::getStoreConfig(self::CONFIG_PATH.'order_unhold/message'));
	}

	public function getMessageForOrderCanceled(Mage_Sales_Model_Order $order)
	{
		$billingAddress = $order->getBillingAddress();
		$whatArray = array('{{firstname}}','{{order_id}}');
		$withWhatArray = array($billingAddress->getFirstname(),$order->getIncrementId());

		return str_replace($whatArray,$withWhatArray,Mage::getStoreConfig(self::CONFIG_PATH.'order_canceled/message'));
	}

	public function getMessageForShipment(Mage_Sales_Model_Order $order)
	{
		$billingAddress = $order->getBillingAddress();
		$whatArray = array('{{firstname}}','{{order_id}}');
		$withWhatArray = array($billingAddress->getFirstname(),$order->getIncrementId());

		return str_replace($whatArray,$withWhatArray,Mage::getStoreConfig(self::CONFIG_PATH.'shipments/message'));
	}

	public function getTelephoneFromOrder(Mage_Sales_Model_Order $order)
    {
        $billingAddress = $order->getBillingAddress();

        $number = $billingAddress->getTelephone();
        $number = preg_replace('#[^\+\d]#', '', trim($number));

        if (substr($number, 0, 1) === '+') {
            $number = substr($number, 1);
        } elseif (substr($number, 0, 2) === '00') {
            $number = substr($number, 2);
        } else {
            if (substr($number, 0, 1) === '0') {
                $number = substr($number, 1);
            }
            $expectedPrefix = Zend_Locale_Data::getContent(Mage::app()->getLocale()->getLocale(), 'phonetoterritory', $billingAddress->getCountry());
            if (!empty($expectedPrefix)) {
                $prefix = substr($number, 0, strlen($expectedPrefix));
                if ($prefix !== $expectedPrefix) {
                    $number = $expectedPrefix . $number;
                }
            }
        }
        $number = preg_replace('#[^\d]#', '', trim($number));

        return $number;
    }

	public function filterPrefixNumber($number, $order)
    {
        $billingAddress = $order->getBillingAddress();

        $number = preg_replace('#[^\+\d]#', '', trim($number));

        if (substr($number, 0, 1) === '+') {
            $number = substr($number, 1);
        } elseif (substr($number, 0, 2) === '00') {
            $number = substr($number, 2);
        } else {
            if (substr($number, 0, 1) === '0') {
                $number = substr($number, 1);
            }
            $expectedPrefix = Zend_Locale_Data::getContent(Mage::app()->getLocale()->getLocale(), 'phonetoterritory', $billingAddress->getCountry());
            if (!empty($expectedPrefix)) {
                $prefix = substr($number, 0, strlen($expectedPrefix));
                if ($prefix !== $expectedPrefix) {
                    $number = $expectedPrefix . $number;
                }
            }
        }
        $number = preg_replace('#[^\d]#', '', trim($number));

        return $number;
    }

	public function getCountry(Mage_Sales_Model_Order $order){
		$billingAddress = $order->getBillingAddress();
		return $billingAddress->getCountry();
	}

	public function isOrdersNotify()
    {
		return Mage::getStoreConfig(self::CONFIG_PATH.'orders/notify');
    }

	public function getAdminTelephone()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH.'orders/receiver');
    }

	public function sendSms($url,$data=NULL)
	{

		try
		{	if($data!='')
			{

				$sendSms = $this->file_get_contents_curl_POST($url,$data);
			}
			else
			{

				$sendSms = $this->file_get_contents_curl($url);
			}

		}
		catch(Exception $e)
		{
			$sendSms = '';
		}
		if($sendSms)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function file_get_contents_curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}

	public function file_get_contents_curl_POST($url,$data)
	{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_VERBOSE,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // required as godaddy fails
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // required as godaddy fails
			curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
	}

}
