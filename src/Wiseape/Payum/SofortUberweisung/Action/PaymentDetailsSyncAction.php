<?php

/**
 * @copyright wiseape GmbH
 * @author Ruben Rögels
 * @license LGPL-3.0+
 */

namespace Wiseape\Payum\SofortUberweisung\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\SyncRequest;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Wiseape\Payum\SofortUberweisung\Action\Api\GetTransactionDataAction;
use Wiseape\Payum\SofortUberweisung\Request\Api\GetTransactionDataRequest;

class PaymentDetailsSyncAction extends PaymentAwareAction {

    /**
     * {@inheritdoc}
     */
    public function execute($request) {
        if(false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->payment->execute(new GetTransactionDataRequest($model));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request) {
        $model = $request->getModel();
        return $request instanceof SyncRequest && $model instanceof \ArrayAccess;
    }

}