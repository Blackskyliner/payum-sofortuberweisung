<?php

/**
 * @copyright wiseape GmbH
 * @author Ruben Rögels
 * @license LGPL-3.0+
 */

namespace Wiseape\Payum\SofortUberweisung\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\CaptureRequest;
use Payum\Core\Request\SecuredCaptureRequest;
use Payum\Core\Request\SyncRequest;
use Wiseape\Payum\SofortUberweisung\Request\Api\RequestSofortUberweisungRequest;
use Wiseape\Payum\SofortUberweisung\Model\SecurityToken;

class CaptureAction extends PaymentAwareAction {

    public function execute($request) {

        if(!$this->supports($request)) {
            RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if($request instanceof SecuredCaptureRequest) {
            if(!isset($model['success_url'])) {
                $model['success_url'] = $request->getToken()->getTargetUrl();
            }

            if(!isset($model['abort_url'])) {
                $model['abort_url'] = $request->getToken()->getTargetUrl();
            }
        }

        if(!isset($model['txn'])) {
            $this->payment->execute(new RequestSofortUberweisungRequest($model));
        }
    }

    public function supports($request) {
        return $request instanceof CaptureRequest
                && $request->getModel() instanceof \ArrayAccess;
    }

}
