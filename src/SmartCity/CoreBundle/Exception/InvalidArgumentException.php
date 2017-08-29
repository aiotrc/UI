<?php

namespace BodoFood\Bundle\Exception;

use BodoFood\Bundle\Exception\ErrorCode;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * InvalidArgumentException
 *
 * @author Faraz Shamshirdar <faraz@palang.co>
 */
class InvalidArgumentException extends \RuntimeException
{
    /**
     * @param array $meta
     * @param \Exception $previous
     */
    public function __construct($argument, \Exception $previous = null, $appVersion = null)
    {
        $message = "$argument is required! in app version : $appVersion";

        parent::__construct($message, ErrorCode::INVALID_ARGUMENT, $previous);
    }
}