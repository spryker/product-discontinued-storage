<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedConditionsTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface;
use Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig;

class ProductDiscontinuedPublisher implements ProductDiscontinuedPublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface
     */
    protected $productDiscontinuedStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface
     */
    protected $productDiscontinuedStorageRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig
     */
    protected $productDiscontinuedStorageConfig;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface $productDiscontinuedStorageEntityManager
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig
     */
    public function __construct(
        ProductDiscontinuedStorageEntityManagerInterface $productDiscontinuedStorageEntityManager,
        ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository,
        ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface $productDiscontinuedFacade,
        ProductDiscontinuedStorageToLocaleFacadeInterface $localeFacade,
        ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig
    ) {
        $this->productDiscontinuedStorageEntityManager = $productDiscontinuedStorageEntityManager;
        $this->productDiscontinuedStorageRepository = $productDiscontinuedStorageRepository;
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->localeFacade = $localeFacade;
        $this->productDiscontinuedStorageConfig = $productDiscontinuedStorageConfig;
    }

    /**
     * @param array<int> $productDiscontinuedIds
     *
     * @return void
     */
    public function publish(array $productDiscontinuedIds): void
    {
        $productDiscontinuedCollectionTransfer = $this->findProductDiscontinuedCollection($productDiscontinuedIds);
        $productDiscontinuedStorageEntities = $this->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);

        $this->storeData($productDiscontinuedCollectionTransfer, $productDiscontinuedStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     * @param array<\Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage> $productDiscontinuedStorageEntities
     *
     * @return void
     */
    protected function storeData(
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer,
        array $productDiscontinuedStorageEntities
    ): void {
        $indexProductDiscontinuedStorageEntities = $this->indexProductDiscontinuedStorageEntities($productDiscontinuedStorageEntities);
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $this->storeLocalizedData(
                $productDiscontinuedTransfer,
                $indexProductDiscontinuedStorageEntities,
                $localeTransfers,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param array $indexProductDiscontinuedStorageEntities
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return void
     */
    protected function storeLocalizedData(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        array $indexProductDiscontinuedStorageEntities,
        array $localeTransfers
    ): void {
        foreach ($localeTransfers as $localeName => $localeTransfer) {
            if (isset($indexProductDiscontinuedStorageEntities[$productDiscontinuedTransfer->getIdProductDiscontinued()][$localeName])) {
                $this->storeDataSet(
                    $productDiscontinuedTransfer,
                    $localeTransfer,
                    $indexProductDiscontinuedStorageEntities[$productDiscontinuedTransfer->getIdProductDiscontinued()][$localeName],
                );

                continue;
            }

            $this->storeDataSet($productDiscontinuedTransfer, $localeTransfer, new SpyProductDiscontinuedStorage());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage $productDiscontinuedStorage
     *
     * @return void
     */
    protected function storeDataSet(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        LocaleTransfer $localeTransfer,
        SpyProductDiscontinuedStorage $productDiscontinuedStorage
    ): void {
        $productDiscontinuedStorage->setFkProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
            ->setSku($productDiscontinuedTransfer->getSku())
            ->setLocale($localeTransfer->getLocaleName())
            ->setData(
                $this->mapToProductDiscontinuedStorageTransfer($productDiscontinuedTransfer, $localeTransfer)->toArray(),
            );

        $productDiscontinuedStorage->setIsSendingToQueue($this->productDiscontinuedStorageConfig->isSendingToQueue());
        $this->productDiscontinuedStorageEntityManager->saveProductDiscontinuedStorageEntity($productDiscontinuedStorage);
    }

    /**
     * @param array<int> $productDiscontinuedIds
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    protected function findProductDiscontinuedCollection(array $productDiscontinuedIds): ProductDiscontinuedCollectionTransfer
    {
        $productDiscontinuedCriteriaTransfer = (new ProductDiscontinuedCriteriaTransfer())
            ->setProductDiscontinuedConditions(
                (new ProductDiscontinuedConditionsTransfer())->setProductDiscontinuedIds($productDiscontinuedIds),
            );

        return $this->productDiscontinuedFacade->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);
    }

    /**
     * @param array<int> $productDiscontinuedIds
     *
     * @return array<\Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage>
     */
    protected function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        return $this->productDiscontinuedStorageRepository->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);
    }

    /**
     * @param array<\Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage> $productDiscontinuedStorageEntities
     *
     * @return array
     */
    protected function indexProductDiscontinuedStorageEntities(array $productDiscontinuedStorageEntities): array
    {
        $indexProductDiscontinuedStorageEntities = [];
        foreach ($productDiscontinuedStorageEntities as $discontinuedStorageEntity) {
            $indexProductDiscontinuedStorageEntities[$discontinuedStorageEntity->getFkProductDiscontinued()][$discontinuedStorageEntity->getLocale()]
                = $discontinuedStorageEntity;
        }

        return $indexProductDiscontinuedStorageEntities;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer
     */
    protected function mapToProductDiscontinuedStorageTransfer(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        LocaleTransfer $localeTransfer
    ): ProductDiscontinuedStorageTransfer {
        return (new ProductDiscontinuedStorageTransfer())
            ->fromArray($productDiscontinuedTransfer->toArray(), true)
            ->setNote($this->getLocalizedNote($productDiscontinuedTransfer, $localeTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getLocalizedNote(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        LocaleTransfer $localeTransfer
    ): string {
        foreach ($productDiscontinuedTransfer->getProductDiscontinuedNotes() as $discontinuedNoteTransfer) {
            if ($discontinuedNoteTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                return $discontinuedNoteTransfer->getNote() ?? '';
            }
        }

        return '';
    }
}
