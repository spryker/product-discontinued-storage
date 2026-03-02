<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Validator;

use Generated\Shared\Transfer\ItemValidationTransfer;

interface ProductDiscontinuedItemValidatorInterface
{
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
