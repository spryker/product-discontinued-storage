<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Validator;

use Generated\Shared\Transfer\ItemValidationTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class ProductDiscontinuedItemValidator implements ProductDiscontinuedItemValidatorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DISCONTINUED_PRODUCT = 'product_discontinued.message.product_discontinued';

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    protected $productDiscontinuedStorageReader;

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface
     */
    protected $localeClient;

    public function __construct(
        ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader,
        ProductDiscontinuedStorageToLocaleClientInterface $localeClient
    ) {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
        $this->localeClient = $localeClient;
    }

    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        $itemValidationTransfer->requireItem();

        if (!$itemValidationTransfer->getItem()->getId()) {
            return $itemValidationTransfer;
        }

        $itemValidationTransfer->getItem()->requireSku();
        $productDiscontinuedTransfer = $this->productDiscontinuedStorageReader
            ->findProductDiscontinuedStorage($itemValidationTransfer->getItem()->getSku(), $this->localeClient->getCurrentLocale());

        if ($productDiscontinuedTransfer) {
            $itemValidationTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_ERROR)
                ->setValue(static::ERROR_MESSAGE_DISCONTINUED_PRODUCT));
        }

        return $itemValidationTransfer;
    }
}
