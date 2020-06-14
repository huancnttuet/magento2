<?php
namespace Api\Product\Api;

interface ProductByInterface
{
 /**
  * GET product identified by its URL key
  *
  * @api
  * @param string $urlKey
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
 public function getProductByUrl($urlKey);

 /**
  * GET product identified by its id
  *
  * @api
  * @param string $id
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
 public function getProductById($id);
 
/**
  * GET product identified by its sku
  *
  * @api
  * @param string $sku
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function getProductBySku($sku);

}
