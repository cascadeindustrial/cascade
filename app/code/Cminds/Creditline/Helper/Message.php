<?php


namespace Cminds\Creditline\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory as CMCollectionFactory;
use Magento\Backend\Model\Url;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Message extends AbstractHelper
{
    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var CollectionFactory
     */
    protected $orderCreditmemoCollectionFactory;

    /**orderCreditmemoCollectionFactory
     * @var Context
     */
    protected $context;

    /**
     * @var Url
     */
    protected $backendUrlManager;

    /**
     * @param CollectionFactory $orderCollectionFactory
     * @param CollectionFactory $orderCreditmemoCollectionFactory
     * @param Context           $context
     * @param Url               $backendUrlManager
     */
    public function __construct(
        CollectionFactory $orderCollectionFactory,
        CMCollectionFactory $orderCreditmemoCollectionFactory,
        Context $context,
        Url $backendUrlManager
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderCreditmemoCollectionFactory = $orderCreditmemoCollectionFactory;
        $this->context = $context;
        $this->backendUrlManager = $backendUrlManager;

        parent::__construct($context);
    }

    /**
     * @param array $array
     * @return string
     */
    public function createTransactionMessage($array)
    {
        $arMessage = [];

        if (isset($array['order'])) {
            $order = $array['order'];

            $arMessage[] = __('Order #%1', 'o|' . $order->getIncrementId());
        }

        if (isset($array['creditmemo'])) {
            $memo = $array['creditmemo'];

            $arMessage[] = __('Creditmemo #%1', 'm|' . $memo->getIncrementId());
        }

        return implode(', ', $arMessage);
    }

    /**
     * @param string $message
     * @return string
     */
    public function getBackendTransactionMessage($message)
    {
        return $this->getPreparedTransactionMessage($message, 'adminhtml');
    }

    /**
     * @param string $message
     * @return string
     */
    public function getFrontendTransactionMessage($message)
    {
        return $this->getPreparedTransactionMessage($message, 'frontend');
    }

    /**
     * @param string $message
     * @return string mixed
     */
    public function getEmailTransactionMessage($message)
    {
        return $this->getPreparedTransactionMessage($message, 'email');
    }

    /**
     * @param string $message
     * @param string $type
     * @return string
     */
    public function getPreparedTransactionMessage($message, $type)
    {
        $message = $this->highlightOrdersInMessage($message, $type);
        $message = $this->highlightMemosInMessage($message, $type);

        return $message;
    }

    /**
     * @param string $message
     * @param string $type
     * @return string
     */
    protected function highlightOrdersInMessage($message, $type)
    {
        $orderMatches = [];
        preg_match_all('/#o\|([0-9]*)/is', $message, $orderMatches);

        if (count($orderMatches) && isset($orderMatches[1])) {
            foreach ($orderMatches[1] as $key => $incrementId) {
                $order = $this->orderCollectionFactory->create()
                    ->addFieldToFilter('increment_id', $incrementId)
                    ->getFirstItem();

                $url = false;
                if ($type == 'adminhtml') {
                    $url = $this->backendUrlManager->getUrl(
                        'sales/order/view/',
                        ['order_id' => $order->getId()]
                    );
                } elseif ($type == 'frontend') {
                    $url = $this->context->getUrlBuilder()->getUrl('sales/order/view', ['order_id' => $order->getId()]);
                }

                if ($url) {
                    $replace = "<a href='$url' target='_blank'>#$incrementId</a>";
                } else {
                    $replace = "#$incrementId";
                }

                $message = str_replace($orderMatches[0][$key], $replace, $message);
            }
        }

        return $message;
    }

    /**
     * @param string $message
     * @param string $type
     * @return string
     */
    protected function highlightMemosInMessage($message, $type)
    {
        $memoMatches = [];
        preg_match_all('/#m\|([0-9]*)/is', $message, $memoMatches);

        if (count($memoMatches) && isset($memoMatches[1])) {
            foreach ($memoMatches[1] as $key => $incrementId) {
                $memo = $this->orderCreditmemoCollectionFactory->create()
                    ->addFieldToFilter('increment_id', $incrementId)
                    ->getFirstItem();

                $url = false;
                if ($type == 'adminhtml') {
                    $url = $this->backendUrlManager->getUrl(
                        'sales/creditmemo/view',
                        ['creditmemo_id' => $memo->getId()]
                    );
                } elseif ($type == 'frontend') {
                    $url = $this->context->getUrlBuilder()
                        ->getUrl('sales/order/creditmemo', ['order_id' => $memo->getOrderId()]);
                }

                if ($url) {
                    $replace = "<a href='$url' target='_blank'>#$incrementId</a>";
                } else {
                    $replace = "#$incrementId";
                }

                $message = str_replace($memoMatches[0][$key], $replace, $message);
            }
        }

        return $message;
    }
}
