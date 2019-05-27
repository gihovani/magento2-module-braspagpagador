<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as DebitCardConfigInterface;

/**
 * Validator
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Validator implements ValidatorInterface
{
    protected $debitCardConfigInterface;

    public function __construct(
        DebitCardConfigInterface $debitCardConfigInterface
    ) {
        $this->debitCardConfigInterface = $debitCardConfigInterface;
    }

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Debit Order Request object should be provided');
        }

        $request = $validationSubject['request'];
        $status = true;
        $message = [];

        if ($this->debitCardConfigInterface->isAuthentication3Ds20Active()) {

            $failureType = $request->getPaymentExternalAuthenticationFailureType();

            if (( $failureType == DebitCardConfigInterface::BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                    || $failureType == DebitCardConfigInterface::BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                ) && !$this->debitCardConfigInterface->isAuthentication3Ds20AuthorizedOnFailure()
            ) {
                return new Result(false, ["Debit Card Payment Failure. #MPI{$failureType}"]);
            }

            if ($failureType == DebitCardConfigInterface::BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$this->debitCardConfigInterface->isAuthentication3Ds20AuthorizeOnUnenrolled()
            ) {
                return new Result(false, ["Debit Card Payment Failure. #MPI{$failureType}"]);
            }
        }

        return new Result($status, [$message]);
    }
}
