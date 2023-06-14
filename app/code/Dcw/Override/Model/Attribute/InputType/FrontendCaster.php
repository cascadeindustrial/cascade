<?php

namespace Dcw\Override\Model\Attribute\InputType;


class FrontendCaster extends \Amasty\Orderattr\Model\Attribute\InputType\FrontendCaster
{

    protected function setOptions(&$element, $attribute, &$inputType)
    {
      $logFile='/var/log/FrontendCasterddddsdsd.log';
    $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    $logger->info("inside overriderecheck dev fileeeeeeeeeeeeee");
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
      $result = array();
      $result1 = array();
      $customer = $customerSession->getCustomer();
      $savedfedexAccountData = $customer->getFedexaccountno();
      if($savedfedexAccountData)
      {
        $savedfedexAccountNos = explode(',',$savedfedexAccountData);
        if(is_array($savedfedexAccountNos))
        {
          foreach($savedfedexAccountNos as $savedfedexAccountNo)
                     $result[$savedfedexAccountNo] = $savedfedexAccountNo;
        }
        else{
                     $result[$savedfedexAccountNo] = $savedfedexAccountNo;
        }
      }
      $savedUpsAccountData = $customer->getUpsaccountno();
      if($savedUpsAccountData)
      {
        $savedUpsAccountNos = explode(',',$savedUpsAccountData);
        if(is_array($savedUpsAccountNos))
        {
          foreach($savedUpsAccountNos as $savedUpsAccountNo)
                     $result1[$savedUpsAccountNo] = $savedUpsAccountNo;
        }
        else{
                     $result[$savedUpsAccountNo] = $savedUpsAccountNo;
        }
      }
        //printLog($attribute->getAttributeCode());
        //printLog($result);

        $allOptions = $attribute->getSource()->getAllOptions(false);

        if ($inputType->isDisplayEmptyOption()) {
            array_unshift($allOptions, ['label' => ' ', 'value' => '']);
        }
        //printLog($result);
        if($attribute->getAttributeCode()=="stored_account_values_pm")
        {
            //printLog("before");
            //printLog($allOptions);
            $allOptions = array_slice($allOptions, 0);
            //$allOptions[1] = array("value"=>"1234444123","label"=>"test123454321");
            //$allOptions[1] = array_slice($allOptions, 1);
            //$allOptions = array();
            //$allOptions[] = array("label"=>'',"value"=>'');

            //(
//     [0] => Array
//         (
//             [label] =>
//             [value] =>
//         )
//
//     [1] => Array
//         (
//             [value] =>
//             [label] => test123454321
//         )
//
// )
            $i=1;
            foreach($result as $res => $val)
            {
              $allOptions[$i] = array("value"=>$res,"label"=>$val);
              $i++;
            }
            // foreach($result1 as $res => $val)
            // {
            //   $allOptions[$i] = array("value"=>$res,"label"=>$val." -- Ups");
            //   $i++;
            // }

        }
        if($attribute->getAttributeCode()=="guest_fedexselectmethod")
        {

          $cart = $objectManager->create('Magento\Checkout\Model\Cart');

          $shippingAddress = $cart->getQuote()->getShippingAddress();
          $countryCode = $shippingAddress->getData('country_id');

          printLog("alloptions");
          printLog("guest_fedexselectmethod");
          printLog($allOptions);
          printLog("country code");
          printLog($countryCode);
        }
        if($attribute->getAttributeCode()=="stored_account_values_upspm")
        {
            //printLog("before");
            //printLog($allOptions);
            $allOptions = array_slice($allOptions, 0);
            //$allOptions[1] = array("value"=>"1234444123","label"=>"test123454321");
            //$allOptions[1] = array_slice($allOptions, 1);
            //$allOptions = array();
            //$allOptions[] = array("label"=>'',"value"=>'');

            //(
//     [0] => Array
//         (
//             [label] =>
//             [value] =>
//         )
//
//     [1] => Array
//         (
//             [value] =>
//             [label] => test123454321
//         )
//
// )
            $i=1;
            foreach($result1 as $res => $val)
            {
              $allOptions[$i] = array("value"=>$res,"label"=>$val);
              $i++;
            }

        }
        // printLog("after setOptions");
        // printLog($allOptions);
        $element['options'] = $allOptions;
    }

}
