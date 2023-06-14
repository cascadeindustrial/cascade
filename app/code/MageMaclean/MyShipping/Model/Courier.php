<?php
declare(strict_types=1);

namespace MageMaclean\MyShipping\Model;

use MageMaclean\MyShipping\Api\Data\CourierInterface;
use MageMaclean\MyShipping\Model\ResourceModel\Courier as CourierResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;

use MageMaclean\MyShipping\Model\Carrier;

class Courier extends AbstractModel implements CourierInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    public const CACHE_TAG = 'magemaclean_myshipping_courier';
    /**
     * Cache tag
     *
     * @var string
     * phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore,PSR12.Classes.PropertyDeclaration.Underscore
     */
    protected $_cacheTag = self::CACHE_TAG;
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'magemaclean_myshipping_courier';
    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'courier';
    //phpcs:enable
    /**
     * @var Json
     */
    private $json;
    /**
     * Initialize resource model
     *
     * @return void
     * phpcs:disable PSR2.Methods.MethodDeclaration.Underscore,PSR12.Methods.MethodDeclaration.Underscore
     */
    protected function _construct()
    {
        $this->_init(CourierResourceModel::class);
        //phpcs:enable
    }

    /**
     * Courier constructor.
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @param Json $json
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Json $json,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->json = $json;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritdoc
     */
    public function getCarrierCode() {
        $carrierCode = Carrier::CODE;
        if($this->getCourierId()) {
            $carrierCode .= "_" . $this->getCourierId();
        }
        return $carrierCode;
    }

    /**
     * @inheritdoc
     */
    public function getCourierId()
    {
        return $this->getData(CourierInterface::COURIER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCourierId($courierId)
    {
        return $this->setData(self::COURIER_ID, $courierId);
    }

    /**
     * @inheritdoc
     */
    public function setIsEnabled($isEnabled)
    {
        return $this->setData(self::IS_ENABLED, $isEnabled);
    }

    /**
     * @inheritdoc
     */
    public function getIsEnabled()
    {
        return $this->getData(self::IS_ENABLED);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setMethods($methods)
    {
        if (is_array($methods)) {
            $methods = $this->json->serialize($methods);
        }
        return $this->setData(self::METHODS, $methods);
    }

    /**
     * @inheritdoc
     */
    public function getMethods($asArray = false)
    {
        $methods = $this->getData(self::METHODS);
        if (!$asArray) {
            return $methods;
        }
        if (is_string($methods)) {
            try {
                $methods = $this->json->unserialize($methods);
            } catch (\InvalidArgumentException $e) {
                $methods = [];
            }
        }
        return $methods;
    }

    /**
     * @param int $recordId
     * @return array
     */
    public function getMethodByRecordId($recordId)
    {
        $methods = $this->getMethods(true);
        return (isset($methods[$recordId])) ? $methods[$recordId] : array();
    }

    /**
     * @param $methodCode
     * @return array
     */
    public function getMethodByCode($methodCode)
    {
        $methods = $this->getMethods(true);
        $method = array();
        if($methods && sizeof($methods)) {
            foreach($methods as $m) {
                if($m['method_code'] == $methodCode) {
                    $method = $m;
                    break;
                }
            }
        }
        return $method;
    }

    /**
     * @inheritdoc
     */
    public function getMethodTitle($methodCode)
    {
        $method = $this->getMethodByCode($methodCode);
        if(!$method) return '';

        return $method['method_name'];
    }

    /**
     * @inheritdoc
     */
    public function setSallowspecific($sallowspecific)
    {
        return $this->setData(self::SALLOWSPECIFIC, $sallowspecific);
    }

    /**
     * @inheritdoc
     */
    public function getSallowspecific()
    {
        return $this->getData(self::SALLOWSPECIFIC);
    }

    /**
     * @inheritdoc
     */
    public function setSpecificcountry($specificcountry)
    {
        if (is_array($specificcountry)) {
            $specificcountry = implode(',', $specificcountry);
        }
        return $this->setData(self::SPECIFICCOUNTRY, $specificcountry);
    }

    /**
     * @inheritdoc
     */
    public function getSpecificcountry($asArray = false)
    {
        $specificcountry = $this->getData(self::SPECIFICCOUNTRY);
        return ($asArray)
            ? ($specificcountry ? explode(',', $specificcountry) : [])
            : $specificcountry;
    }

    /**
     * @inheritdoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
