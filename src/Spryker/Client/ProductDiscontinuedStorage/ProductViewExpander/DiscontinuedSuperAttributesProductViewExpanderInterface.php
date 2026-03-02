<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander;

use Generated\Shared\Transfer\ProductViewTransfer;

interface DiscontinuedSuperAttributesProductViewExpanderInterface
{
    public function expandDiscontinuedProductSuperAttributes(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer;
}
