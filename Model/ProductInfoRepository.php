<?php
/**
 * @category Fei
 * @package Fei_Test
 * @author Fei Chen chenfei@hotmail.co.uk
 */

namespace Fei\Test\Model;

use Fei\Test\Api\ProductInfoRepositoryInterface as ProductInfoRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as Configurable;
use Magento\Framework\Exception\NoSuchEntityException as NoSuchEntityException;

/**
 * Class ProductInfoRepository
 * @package Fei\Test\Model
 */
class ProductInfoRepository implements ProductInfoRepositoryInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * ProductInfoRepository constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductInfoWithChildSku($sku)
    {
        $result = [];
        try {
            $productObj = $this->productRepository->get($sku);
            $productInfo = ['name' => $productObj->getName(), 'sku' => $productObj->getSku()];
            $result[] = ['product_info' => $productInfo];
            if ($productObj->getTypeId() == Configurable::TYPE_CODE) {
                $result[] = ['product_option' => $this->getConfigurableOptions($productObj)];
            } else {
                $result[] = ['error' => 'Product with sku ' . $sku . ' does not contain any options.'];
            }

        } catch (NoSuchEntityException $e) {
            $result[] = ['error' => 'No product can be found with sku ' . $sku . ' provided.'];
        }
        return $result;
    }

    /**
     * get available product options
     * @param $product
     * @return bool|array
     */
    protected function getConfigurableOptions($product)
    {
        $optionValues = [];
        $attributes = $product->getTypeInstance()->getConfigurableOptions($product);
        if (count($attributes) > 0) {
            return false;
        }
        foreach ($attributes as $options) {
            foreach ($options as $option) {
                array_push($optionValues, ['sku' => $option['sku'],
                    'option' => $option['attribute_code'],
                    'value' => $option['option_title']]);
            }
        }
        return $optionValues;
    }
}
