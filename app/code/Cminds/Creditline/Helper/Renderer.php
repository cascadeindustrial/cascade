<?php


namespace Cminds\Creditline\Helper;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Url;
use Magento\Framework\DataObject;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Block\Widget;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Renderer extends Widget
{
    /**
     * @var CurrencyInterface
     */
    protected $currency;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Url
     */
    protected $backendUrl;

    public function __construct(
        CurrencyInterface $currency,
        Url $backendUrl,
        Context $context,
        $data = []
    ) {
        $this->currency = $currency;
        $this->storeManager = $context->getStoreManager();
        $this->context      = $context;
        $this->backendUrl   = $backendUrl;

        parent::__construct($context, $data);
    }

    /**
     * @param float      $value
     * @param DataObject $row
     * @param DataObject $column
     * @param bool       $isExport
     * @return string
     */
    public function amountDelta($value, $row, $column, $isExport)
    {
        if ($isExport) {
            $column->setCurrencyCode('');

            return $value;
        }
        $currency = $this->currency->getCurrency($row->getCurrencyCode());
        if ($row->getData($column->getIndex()) > 0) {
            return '<span style="color:#0a0">+' . $currency->toCurrency($value) . '</span>';
        } else {
            return '<span style="color:#f00">' . $currency->toCurrency($value) . '</span>';
        }
    }

    /**
     * @param float      $value
     * @param DataObject $row
     * @param DataObject $column
     * @param bool       $isExport
     * @return string
     */
    public function amount($value, $row, $column, $isExport)
    {
        if ($row->getData($column->getIndex()) == 0 && !$isExport) {
            return '—';
        }

        $currency = $this->currency->getCurrency($row->getCurrencyCode());

        return $currency->toCurrency($value);
    }

    /**
     * @param float      $value
     * @param DataObject $row
     * @param DataObject $column
     * @param bool       $isExport
     * @return string
     */
    public function transactionMessage($value, $row, $column, $isExport)
    {
        if ($isExport) {
            return $value;
        }

        return $row->getBackendMessage();
    }

    /**
     * @param float      $value
     * @param DataObject $row
     * @param DataObject $column
     * @return string
     */
    public function websiteCode($value, $row, $column)
    {
        return $this->storeManager->getWebsite($value)->getCode();
    }

    /**
     * @param float      $value
     * @param DataObject $row
     * @param DataObject $column
     * @param bool       $isExport
     * @return string
     */
    public function customerName($value, $row, $column, $isExport)
    {
        if ($isExport) {
            return $value;
        }

        $url = $this->backendUrl->getUrl('customer/index/edit', ['id' => $row->getCustomerId()]);

        return "<a href='$url'>$value</a>";
    }
}
