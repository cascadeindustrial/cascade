<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\Component\Listing;

use MageMaclean\MyShipping\Ui\EntityUiConfig;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ActionsColumn extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * ActionsColumn constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param EntityUiConfig $uiConfig
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        EntityUiConfig $uiConfig,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->uiConfig = $uiConfig;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $param = $this->uiConfig->getRequestParamName();
        foreach ($dataSource['data']['items'] as & $item) {
            $params = [$param => $item[$param] ?? null];
            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl($this->uiConfig->getEditUrlPath(), $params),
                    'label' => __('Edit')->render()
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl($this->uiConfig->getDeleteUrlPath(), $params),
                    'label' => __('Delete')->render(),
                    'confirm' => [
                        'title' => __('Delete %1', $item[$this->uiConfig->getNameAttribute()] ?? '')->render(),
                        'message' => $this->uiConfig->getDeleteMessage()
                    ],
                    'post' => true
                ]
            ];
        }
        return $dataSource;
    }
}
