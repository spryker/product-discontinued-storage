<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStoragePersistenceFactory getFactory()
 */
class ProductDiscontinuedStorageEntityManager extends AbstractEntityManager implements ProductDiscontinuedStorageEntityManagerInterface
{
    public function saveProductDiscontinuedStorageEntity(SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity): void
    {
        $productDiscontinuedStorageEntity->save();
    }

    public function deleteProductDiscontinuedStorageEntity(SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity): void
    {
        $productDiscontinuedStorageEntity->delete();
    }
}
