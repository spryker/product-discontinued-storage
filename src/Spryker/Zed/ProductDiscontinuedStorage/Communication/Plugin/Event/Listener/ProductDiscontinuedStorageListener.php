<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinued\Dependency\ProductDiscontinuedEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener\ProductDiscontinuedStoragePublishListener}
 *   and {@link \Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener\ProductDiscontinuedStorageUnpublishListener} instead.
 *
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinuedStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 */
class ProductDiscontinuedStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productDiscontinuedIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventEntityTransfers);

        if (!$productDiscontinuedIds) {
            return;
        }

        if (
            $eventName === ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_DELETE
            || $eventName === ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_UNPUBLISH
        ) {
            $this->getFacade()->unpublish($productDiscontinuedIds);

            return;
        }

        $this->getFacade()->publish($productDiscontinuedIds);
    }
}
