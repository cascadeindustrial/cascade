<?php

namespace Dcw\Override\Helper;

class Customer extends \MageWorx\GeoIP\Helper\Customer
{
    public function getCustomerIp()
    {
        $logFile='/var/log/customerfile.log';
    $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    $logger->info("inside overrided file");
        if ($testIp = $this->getDebugIp()) { // for debug: paste into 'getDebugIp' country code like 'US','DE','FR','SE'
            return $testIp;
        }
         // Following code commented  and added new code to solve header default currency issue
        // if ($this->_getRequest()->getServer('HTTP_CLIENT_IP')) {
        //     $ip = $this->_getRequest()->getServer('HTTP_CLIENT_IP');
        // } elseif ($this->_getRequest()->getServer('HTTP_X_FORWARDED_FOR')) {
        //     $ip = $this->_getRequest()->getServer('HTTP_X_FORWARDED_FOR');
        // } else {
        //     $ip = $this->_getRequest()->getServer('REMOTE_ADDR');
        // }
        if ($this->_getRequest()->getServer('HTTP_X_FORWARDED_FOR')) {
            $ip = $this->_getRequest()->getServer('HTTP_X_FORWARDED_FOR');
        } elseif ($this->_getRequest()->getServer('HTTP_CLIENT_IP')) {
            $ip = $this->_getRequest()->getServer('HTTP_CLIENT_IP');
        } else {
            $ip = $this->_getRequest()->getServer('REMOTE_ADDR');
        }

        $ipArr = explode(',', $ip);
        $ip    = $ipArr[count($ipArr) - 1];
        $ip    = $ipArr[0];
        return trim($ip);
    }
}
