<?php

namespace Dcw\Override\Block\Order;

class Attributes extends \Amasty\Orderattr\Block\Order\Attributes
{
    public function getOrderAttributesData()
    {
    //     $logFile='/var/log/attributesfront.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("in overrided file");
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $entityResolver = $objectManager->create('Amasty\Orderattr\Model\Entity\EntityResolver');
        //$coreRegistry = $objectManager->create('Magento\Framework\Registry');
        if (!$this->getOrder()) {
            return [];
        }

        $orderAttributesData = [];
        $entity = $entityResolver->getEntityByOrder($this->getOrder());
        if ($entity->isObjectNew()) {
            return [];
        }
        $form = $this->createEntityForm($entity);
        $outputData = $form->outputData(\Magento\Eav\Model\AttributeDataFactory::OUTPUT_FORMAT_HTML);
        foreach ($outputData as $attributeCode => $data) {
            if (!empty($data)) {
                $attribute = $form->getAttribute($attributeCode);
                $orderAttributesData[] = [
                    'label' => $attribute->getDefaultFrontendLabel(),
                    'value' => ($attribute->getFrontendInput() === 'html')
                        ? $data
                        : nl2br($this->escapeHtml($data)),
                    'code' => $attributeCode
                ];
            }
        }

        return $orderAttributesData;
    }

}
