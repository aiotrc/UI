<?php

namespace BodoFood\Bundle\Exception;

/**
 * LogicException
 *
 * @author Faraz Shamshirdar <faraz@palang.co>
 */
class LogicException extends \RuntimeException implements LogicExceptionInterface
{
    /** @var array $meta */
    private $meta;

    /** @var integer $errorCode */
    private $errorCode;

    /**
     * @param int $errorCode
     * @param array $meta
     * @param \Exception $previous
     */
    public function __construct($errorCode = 0, array $meta = array(), \Exception $previous = null)
    {
        $this->setErrorCode($errorCode);
        $this->setMeta($meta);

        parent::__construct('', $errorCode, $previous);
    }

    /**
     * @param string $meta
     * @return \LogicException
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param integer $code
     * @return \LogicException
     */
    public function setErrorCode($code)
    {
        $this->errorCode = $code;

        return $this;
    }

    /**
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}