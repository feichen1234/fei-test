<?php
/**
 * @category Fei
 * @package Fei_Test
 * @author Fei Chen chenfei@hotmail.co.uk
 */

namespace Fei\Test\Api;

/**
 * Interface for retrieve product information with sku of child product
 * @api
 */
interface ProductInfoRepositoryInterface
{
    /**
     * get product information include child sku
     * @api
     * @param string sku
     * @return mixed
     */
    public function getProductInfoWithChildSku($sku);
}