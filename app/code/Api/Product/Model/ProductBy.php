<?php
namespace Api\Product\Model;

class ProductBy
{

 /**
  * @var \Magento\Framework\Api\SearchCriteriaBuilder
  */
 protected $searchCriteriaBuilder;

 /**
  * @var \Magento\Catalog\Api\ProductRepositoryInterface
  */
 protected $productRepository;

 /**
  * @var \Magento\Framework\Api\FilterBuilder
  */
 protected $filterBuilder;

 /**
  * @var \Magento\Framework\Api\Search\FilterGroup
  */
 protected $filterGroup;

 public function __construct(
   \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
   \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
   \Magento\Framework\Api\FilterBuilder $filterBuilder,
   \Magento\Framework\Api\Search\FilterGroup $filterGroup)
 {
   $this->productRepository = $productRepository;
   $this->searchCriteria = $searchCriteria;
   $this->filterBuilder = $filterBuilder;
   $this->filterGroup = $filterGroup;
 }

 /**
  * {@inheritdoc}
  */
 public function getProductByUrl($urlKey)
 {
   $this->filterGroup->setFilters([
   $this->filterBuilder->setField('url_key')->setConditionType('eq')
        ->setValue($urlKey)->create()]);
   $this->searchCriteria->setFilterGroups([$this->filterGroup]);
   $products = $this->productRepository->getList($this->searchCriteria);
   if (count($products) == 0) {
     return null;
   }
   $items = $products->getItems();
   foreach ($items as $item) {
     return $item;
   }
 }

 /**
  * {@inheritdoc}
  */
 public function getProductById($id)
 {
   return $this->productRepository->getById($id);
 }
}