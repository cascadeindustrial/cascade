<?php
namespace Dcw\CustomPricing\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;


class ViewAction extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
    // $logFile='/var/log/viewactions.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("datasource");
    // $logger->info($dataSource);
         if (isset($dataSource['data']['items'])) {
             $storeId = $this->context->getFilterParam('store_id');
             foreach ($dataSource['data']['items'] as &$item) {
                 $item[$this->getData('name')]['edit'] = [
                     'href' => $this->urlBuilder->getUrl(
                         'dcw_custompricing/custompricing/edit',
                         ['id' => $item['id'], 'store' => $storeId]
                     ),
                     'label' => __('Edit'),
                     'hidden' => false,
                 ];
                 $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl('dcw_custompricing/custompricing/delete', ['id' => $item['id']]),
                    'label' => __('Delete'),
                    'hidden' => false
                ];
             }
         }
       return $dataSource;
     }
}
