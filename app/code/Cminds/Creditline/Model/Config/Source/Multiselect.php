<?php
namespace Cminds\Creditline\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Customer\Model\ResourceModel\Group\Collection;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Multiselect implements ArrayInterface
{

    protected $_options;

    /**
     * @var Collection
     */
    protected $customerGroup;

    /**
     * {@inheritdoc}
     */
    public function __construct( Collection $customerGroup ) {
        $this->customerGroup = $customerGroup; 
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray() {
        if (!$this->_options) {
            $this->_options = $this->customerGroup->toOptionArray();
        }
        return $this->_options;
    }
}
