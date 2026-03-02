<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Storage;

use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;

interface ProductDiscontinuedStorageReaderInterface
{
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer;
}
