<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedUnpublisher;

use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface;
use Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig;

class ProductDiscontinuedUnpublisher implements ProductDiscontinuedUnpublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface
     */
    protected $discontinuedStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface
     */
    protected $productDiscontinuedStorageRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig
     */
    protected $productDiscontinuedStorageConfig;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface $discontinuedStorageEntityManager
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository
     * @param \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig
     */
    public function __construct(
        ProductDiscontinuedStorageEntityManagerInterface $discontinuedStorageEntityManager,
        ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository,
        ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig
    ) {
        $this->discontinuedStorageEntityManager = $discontinuedStorageEntityManager;
        $this->productDiscontinuedStorageRepository = $productDiscontinuedStorageRepository;
        $this->productDiscontinuedStorageConfig = $productDiscontinuedStorageConfig;
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function unpublish(array $productDiscontinuedIds): void
    {
        $productDiscontinuedStorageEntities = $this->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);

        foreach ($productDiscontinuedStorageEntities as $productDiscontinuedStorageEntity) {
            $productDiscontinuedStorageEntity->setIsSendingToQueue(
                $this->productDiscontinuedStorageConfig->isSendingToQueue()
            );
            $this->discontinuedStorageEntityManager->deleteProductDiscontinuedStorageEntity(
                $productDiscontinuedStorageEntity
            );
        }
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]
     */
    protected function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        return $this->productDiscontinuedStorageRepository->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);
    }
}
