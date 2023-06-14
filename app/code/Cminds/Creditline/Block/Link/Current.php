<?php
namespace Cminds\Creditline\Block\Link;

use Magento\Framework\View\Element\Html\Link\Current as LinkCurrent;
use Cminds\Creditline\Model\Config;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\DefaultPathInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Current extends LinkCurrent
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param Calculation            $calculation
     * @param CreditHelper           $creditHelper
     * @param Context                $context
     * @param array                  $data
     */
    public function __construct(
        Context $context,
        Config $config,
        DefaultPathInterface $defaultPath,
        array $data = []
    ) {
        $this->config        = $config;
        $this->_defaultPath = $defaultPath;
        parent::__construct($context,$defaultPath,$data);
    }
    
    public function toHtml(){
        
        if(!$this->config->isExistGroup()){
            return '';
        }

        return parent::toHtml();
    }
}