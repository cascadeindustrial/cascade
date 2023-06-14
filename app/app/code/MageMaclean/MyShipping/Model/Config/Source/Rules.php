<?php
namespace MageMaclean\MyShipping\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Rules implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $options;


    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $rules = [
                'validate-alpha',
                'validate-alphanum',
                'validate-alphanum-with-spaces',
                'validate-not-negative-number',
                'validate-zero-or-greater',
                'validate-greater-than-zero',
                'validate-number',
                'validate-digits',
                'validate-code'
            ];
            $options = [];
            foreach($rules as $rule) {
                $options[] = array('label' => __($rule), 'value' => $rule);
            }
            $this->options = $options;
        }
        return $this->options;
    }
}
