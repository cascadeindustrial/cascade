<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\Quote\Pdf\Items;

/**
 * Quote Pdf Items renderer Abstract
 */
abstract class AbstractItems extends \Magento\Sales\Model\Order\Pdf\Items\AbstractItems
{
    use \Cart2Quote\Features\Traits\Model\Quote\Pdf\Items\AbstractItems {
        setQuote as private traitSetQuote;
        getQuote as private traitGetQuote;
        getQuoteTierItemPricesForDisplay as private traitGetQuoteTierItemPricesForDisplay;
        getTextWidth as private traitGetTextWidth;
        splitTextOnWidth as private traitSplitTextOnWidth;
        getImageType as private traitGetImageType;
        createJpegImage as private traitCreateJpegImage;
        hexToRgb as private traitHexToRgb;
    }

    /**
     * Core string
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * @var \Cart2Quote\Quotation\Model\Quote
     */
    protected $quote;

    /**
     * @var \Cart2Quote\Quotation\Helper\QuotationTaxHelper
     */
    protected $quotationTaxHelper;

    /**
     * AbstractItems constructor.
     *
     * @param \Cart2Quote\Quotation\Helper\QuotationTaxHelper $quotationTaxHelper
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Tax\Helper\Data $taxData
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Cart2Quote\Quotation\Helper\QuotationTaxHelper $quotationTaxHelper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->quotationTaxHelper = $quotationTaxHelper;
        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Set Quote model
     *
     * @param \Cart2Quote\Quotation\Model\Quote $quote
     * @return $this
     */
    public function setQuote(\Cart2Quote\Quotation\Model\Quote $quote)
    {
        return $this->traitSetQuote($quote);
    }

    /**
     * Retrieve quote object
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    public function getQuote()
    {
        return $this->traitGetQuote();
    }

    /**
     * Get the tier item prices to display
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getQuoteTierItemPricesForDisplay()
    {
        return $this->traitGetQuoteTierItemPricesForDisplay();
    }

    /**
     * Function that calculates the width of a string
     *
     * @param string $string
     * @return float|int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTextWidth($string)
    {
        return $this->traitGetTextWidth($string);
    }

    /**
     * Function that splits text on width
     *
     * @param string $string
     * @param int $width
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function splitTextOnWidth($string, $width)
    {
        return $this->traitSplitTextOnWidth($string, $width);
    }

    /**
     * Get image type
     *
     * @param string $imagePath
     * @return string filetype
     */
    protected function getImageType($imagePath)
    {
        return $this->traitGetImageType($imagePath);
    }

    /**
     * Create a Jpeg Image
     *
     * @param resource|GdImage $sourceImage
     * @param int $quality
     * @param string $alpha
     * @return String path //path to temporary Jpeg image
     */
    protected function createJpegImage($sourceImage, $quality = 70, $alpha = 'FFFFFF')
    {
        return $this->traitCreateJpegImage($sourceImage, $quality, $alpha);
    }

    /**
     * Convert a hex value to rgb
     *
     * @var string $hex ( #ffffff )
     * @return array
     */
    protected function hexToRgb($hex)
    {
        return $this->traitHexToRgb($hex);
    }
}
