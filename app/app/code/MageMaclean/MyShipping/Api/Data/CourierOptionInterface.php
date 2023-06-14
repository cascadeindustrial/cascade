<?php
namespace MageMaclean\MyShipping\Api\Data;


/**
 * Interface CourierOptionInterface
 * @api
 */
interface CourierOptionInterface 
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

    /**
     * @param string|array $value
     * @return void
     */
    public function setMethods(array $value);

    /**
     * @return string|array
     */
    public function getMethods();
}
