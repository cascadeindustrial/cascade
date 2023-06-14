<?php

namespace Dcw\ShippingMethod\Block\Adminhtml\Order\View;

//use Magento\Backend\Block\Template\Context;
//use Magento\Sales\Model\Order;
//use Amasty\Orderattr\Model\Value\Metadata\Form;

class Attributes extends \Amasty\Orderattr\Block\Adminhtml\Order\View\Attributes
{

    /**
     * @var \Amasty\Orderattr\Model\Value\Metadata\FormFactory
     */
    private $metadataFormFactory;

    /**
     * @var \Amasty\Orderattr\Model\Entity\EntityResolver
     */
    private $entityResolver;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Orderattr\Model\Value\Metadata\FormFactory $metadataFormFactory,
        \Amasty\Orderattr\Model\Entity\EntityResolver $entityResolver,
        array $data = []
    ) {
        parent::__construct($context, $metadataFormFactory, $entityResolver, $data);
        // $this->metadataFormFactory = $metadataFormFactory;
        // $this->entityResolver = $entityResolver;
    }

    /**
     * Return array of additional account data
     * Value is option style array
     *
     * @return array
     */
    public function getOrderAttributesData()
    {
        $orderAttributesData = [];
        $entity = $this->entityResolver->getEntityByOrder($this->getOrder());
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

        /**
     * Return Checkout Form instance
     *
     * @param \Amasty\Orderattr\Model\Entity\EntityData $entity
     * @return Form
     */
    protected function createEntityForm($entity)
    {
        /** @var Form $formProcessor */
        $formProcessor = $this->metadataFormFactory->create();
        $formProcessor->setFormCode('adminhtml_order_view')
            ->setEntity($entity)
            ->setStore($this->getOrder()->getStore());

        return $formProcessor;
    }
}
