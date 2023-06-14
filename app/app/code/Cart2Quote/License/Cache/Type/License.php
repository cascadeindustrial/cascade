<?php
/**
 * Copyright (c) 2019. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\License\Cache\Type;

/**
 * Class License
 * @package Cart2Quote\License\Cache\Type
 */
class License extends \Magento\Framework\Cache\Frontend\Decorator\TagScope
{
    /**
     * Cache type code unique among all cache types
     */
    const TYPE_IDENTIFIER = 'cart2quote_license';

    /**
     * Cache tag used to distinguish the cache type from all other cache
     */
    const CACHE_TAG = 'CART2QUOTE_LICENSE';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * License constructor
     *
     * @param \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function load($identifier)
    {
        $cacheDataCacheKey = 'c2qLicens::load' . '_' . (string)$identifier;
        if ($this->coreRegistry->registry($cacheDataCacheKey)) {
            $cacheData = $this->coreRegistry->registry($cacheDataCacheKey);
        } else {
            //not available in registry, use resolver
            $cacheData = $this->_getFrontend()->load($identifier);

            $this->coreRegistry->register(
                $cacheDataCacheKey,
                $cacheData,
                true
            );
        }

        return $cacheData;
    }

    /**
     * Enforce marking with a tag
     *
     * {@inheritdoc}
     */
    public function save($data, $identifier, array $tags = [], $lifeTime = null)
    {
        $cacheDataCacheKey = 'c2qLicens::load' . '_' . (string)$identifier;
        if ($this->coreRegistry->registry($cacheDataCacheKey)) {
            $this->coreRegistry->unregister($cacheDataCacheKey);
        }

        $tags[] = $this->getTag();
        return parent::save($data, $identifier, $tags, $lifeTime);
    }
}
