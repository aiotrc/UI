<?php

namespace BodoFood\Bundle\Exception;

use BodoFood\Bundle\Exception\ErrorCode;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ValidityException
 *
 * @author Faraz Shamshirdar <faraz@palang.co>
 */
class ValidityException extends \RuntimeException
{
    /**
     * @param ConstraintViolationListInterface $meta
     * @param \Exception $previous
     */
    public function __construct(ConstraintViolationListInterface $errors, \Exception $previous = null)
    {
        $message = '';
        $propertyPath = '';
        if (count($errors) > 0) {
            /** @var ConstraintViolation $firstError */
            $firstError = $errors[0];

            $propertyPath = $firstError->getPropertyPath();
            $message = $firstError->getMessage();
        }

        parent::__construct("$propertyPath: $message", ErrorCode::VALIDATION_FAILED, $previous);
    }
}