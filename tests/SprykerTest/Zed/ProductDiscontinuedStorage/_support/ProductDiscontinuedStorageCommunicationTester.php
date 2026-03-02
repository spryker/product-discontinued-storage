<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageBusinessFactory;
use Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacade;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductDiscontinuedStorageCommunicationTester extends Actor
{
    use _generated\ProductDiscontinuedStorageCommunicationTesterActions;

    public function getProductDiscontinuedFacade(): ProductDiscontinuedFacadeInterface
    {
        return $this->getLocator()->productDiscontinued()->facade();
    }

    public function getMockedFacade(): ProductDiscontinuedStorageFacade
    {
        $factory = new ProductDiscontinuedStorageBusinessFactory();
        $factory->setConfig(new ProductDiscontinuedStorageConfigMock());

        $facade = $this->getFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    public function createProductDiscontinued(): ?ProductDiscontinuedTransfer
    {
        $productConcrete = $this->haveProduct();
        $productDiscontinuedRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productConcrete->getIdProductConcrete());
        $productDiscontinuedResponseTransfer = $this->getProductDiscontinuedFacade()
            ->markProductAsDiscontinued($productDiscontinuedRequestTransfer);

        return $productDiscontinuedResponseTransfer->getProductDiscontinued();
    }
}
