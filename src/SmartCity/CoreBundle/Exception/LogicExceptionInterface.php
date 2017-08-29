<?php

namespace BodoFood\Bundle\Exception;

/**
 * LogicExceptionInterface.
 *
 * @author Faraz Shamshirdar <faraz@palang.co>
 */
interface LogicExceptionInterface
{
    /**
     * Returns the metadata.
     *
     * @return array of metadata
     */
    public function getMeta();

    /**
     * Returns error code
     *
     * @return integer of error code
     */
    public function getErrorCode();
}