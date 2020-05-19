<?php

namespace THP\Chat\Block;

class FacebookMessenger extends \Magento\Framework\View\Element\Template
{
    protected $_localeResolver;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Locale\ResolverInterface $localeResolver
    ) {
        $this->_localeResolver = $localeResolver;
        parent::__construct($context);
    }

   
    public function getLocate()
    {
        return $this->_localeResolver->getLocale();
    }
}