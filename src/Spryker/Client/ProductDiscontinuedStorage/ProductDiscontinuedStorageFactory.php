<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Service\ProductDiscontinuedStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedChecker\ProductDiscontinuedChecker;
use Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedChecker\ProductDiscontinuedCheckerInterface;
use Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedAvailabilityProductViewExpander;
use Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedAvailabilityProductViewExpanderInterface;
use Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedSuperAttributesProductViewExpander;
use Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedSuperAttributesProductViewExpanderInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReader;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;
use Spryker\Client\ProductDiscontinuedStorage\Validator\ProductDiscontinuedItemValidator;
use Spryker\Client\ProductDiscontinuedStorage\Validator\ProductDiscontinuedItemValidatorInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 */
class ProductDiscontinuedStorageFactory extends AbstractFactory
{
    public function createProductDiscontinuedStorageReader(): ProductDiscontinuedStorageReaderInterface
    {
        return new ProductDiscontinuedStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
        );
    }

    public function createDiscontinuedSuperAttributesProductViewExpander(): DiscontinuedSuperAttributesProductViewExpanderInterface
    {
        return new DiscontinuedSuperAttributesProductViewExpander(
            $this->createProductDiscontinuedStorageReader(),
            $this->getGlossaryStorageClient(),
            $this->getConfig(),
        );
    }

    public function createProductDiscontinuedChecker(): ProductDiscontinuedCheckerInterface
    {
        return new ProductDiscontinuedChecker(
            $this->createProductDiscontinuedStorageReader(),
        );
    }

    public function createDiscontinuedAvailabilityProductViewExpander(): DiscontinuedAvailabilityProductViewExpanderInterface
    {
        return new DiscontinuedAvailabilityProductViewExpander(
            $this->createProductDiscontinuedStorageReader(),
        );
    }

    public function createProductDiscontinuedItemValidator(): ProductDiscontinuedItemValidatorInterface
    {
        return new ProductDiscontinuedItemValidator(
            $this->createProductDiscontinuedStorageReader(),
            $this->getLocaleClient(),
        );
    }

    public function getGlossaryStorageClient(): ProductDiscontinuedStorageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    public function getLocaleClient(): ProductDiscontinuedStorageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::CLIENT_LOCALE);
    }

    public function getStorageClient(): ProductDiscontinuedStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::CLIENT_STORAGE);
    }

    public function getSynchronizationService(): ProductDiscontinuedStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
