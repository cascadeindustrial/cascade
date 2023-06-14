<?php

namespace Dcw\Override\Block\Adminhtml\Order\Plugin;

class OrderView extends \Amasty\Orderattr\Block\Adminhtml\Order\Plugin\OrderView
{
    public function afterToHtml(
        \Magento\Sales\Block\Adminhtml\Order\View\Info $subject,
        $result
    ) {
    //     $logFile='/var/log/pluginoverride.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("in overrided file");
        $attributesBlock = $subject->getChildBlock('order_attributes');
        if ($attributesBlock) {
            $attributesBlock->setTemplate("Dcw_Override::order/view/attributes.phtml");
            $result = $result . $attributesBlock->toHtml();
        }

        return $result;
    }
}
