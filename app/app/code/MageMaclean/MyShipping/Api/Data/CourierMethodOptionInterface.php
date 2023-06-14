<?php
namespace MageMaclean\MyShipping\Api\Data;


/**
 * Interface CourierMethodOptionInterface
 * @api
 */
interface CourierMethodOptionInterface 
{
    /**
     * @param int $value
     * @return void
     */
    public function setValue(int $value);

    /**
     * @return int
     */
    public function getValue();

    /**
     * @param string $value
     * @return void
     */
    public function setLabel(string $value);

    /**
     * @return string
     */
    public function getLabel();
}
