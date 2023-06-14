<?php

namespace Dcw\Override\Block\Adminhtml\Order\View;

class Attributes extends \Amasty\Orderattr\Block\Adminhtml\Order\View\Attributes
{
    public function getOrderAttributesData()
    {
    //     $logFile='/var/log/attributesadminfile.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("in overrided file");
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $entityResolver = $objectManager->create('Amasty\Orderattr\Model\Entity\EntityResolver');
        $metadataFormFactory = $objectManager->create('Amasty\Orderattr\Model\Value\Metadata\FormFactory');
        $orderAttributesData = [];
        $entity = $entityResolver->getEntityByOrder($this->getOrder());
        if ($entity->isObjectNew()) {
            return [];
        }
        $form = $this->createEntityForm($entity);
        $outputData = $form->outputData(\Magento\Eav\Model\AttributeDataFactory::OUTPUT_FORMAT_HTML);
        foreach ($outputData as $attributeCode => $data) {
            if (!empty($data)) {
                $orderAttributesData[] = [
                    'label' => $form->getAttribute($attributeCode)->getDefaultFrontendLabel(),
                    'value' => $data,
                    'code' => $attributeCode
                ];
            }
        }

        return $orderAttributesData;
    }

}
