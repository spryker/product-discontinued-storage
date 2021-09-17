<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Synchronization;

use Spryker\Shared\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Synchronization\ProductDiscontinuedSynchronizationDataBulkPlugin} instead.
 *
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface getRepository()()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinuedStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 */
class ProductDiscontinuedSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductDiscontinuedStorageConfig::PRODUCT_DISCONTINUED_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(array $ids = [])
    {
        $productDiscontinuedStorageEntities = $this->findProductDiscontinuedStorageEntities($ids);

        return $this->getFactory()
            ->createProductDiscontinuedStorageMapper()
            ->mapProductDiscontinuedStorageEntitiesToSynchronizationDataTransfers($productDiscontinuedStorageEntities);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductDiscontinuedStorageConfig::PRODUCT_DISCONTINUED_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getProductDiscontinuedSynchronizationPoolName();
    }

    /**
     * @param array<int> $ids
     *
     * @return array<\Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage>
     */
    protected function findProductDiscontinuedStorageEntities(array $ids): array
    {
        if ($ids === []) {
            return $this->getRepository()->findAllProductDiscontinuedStorageEntities();
        }

        return $this->getRepository()->findProductDiscontinuedStorageEntitiesByIds($ids);
    }
}
