<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Block\Adminhtml\Courier\Button;

use MageMaclean\MyShipping\Ui\EntityUiConfig;
use MageMaclean\MyShipping\Ui\EntityUiManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Delete implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var EntityUiManagerInterface
     */
    private $entityUiManager;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * Delete constructor.
     * @param RequestInterface $request
     * @param EntityUiManagerInterface $entityUiManager
     * @param EntityUiConfig $uiConfig
     * @param UrlInterface $url
     */
    public function __construct(
        RequestInterface $request,
        EntityUiManagerInterface $entityUiManager,
        EntityUiConfig $uiConfig,
        UrlInterface $url
    ) {
        $this->request = $request;
        $this->entityUiManager = $entityUiManager;
        $this->uiConfig = $uiConfig;
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getEntityId()) {
            $data = [
                'label' => $this->uiConfig->getDeleteLabel(),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' .
                    $this->uiConfig->getDeleteMessage() . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return int|null
     */
    private function getEntityId(): ?int
    {
        try {
            return $this->entityUiManager->get(
                (int)$this->request->getParam($this->uiConfig->getRequestParamName(), 0)
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    private function getDeleteUrl(): string
    {
        return $this->url->getUrl(
            '*/*/delete',
            [$this->uiConfig->getRequestParamName() => $this->getEntityId()]
        );
    }
}
