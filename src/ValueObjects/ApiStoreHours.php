<?php

namespace ApApi\ValueObjects;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Value object representing store hours
 */
class ApiStoreHours
{
    /**
     * Constructor for ApiStoreHours
     *
     * @param string $monFriHours Monday to Friday hours
     * @param string $saturdayHours Saturday hours
     * @param string $sundayHours Sunday hours
     */
    public function __construct(
        private readonly string $monFriHours,
        private readonly string $saturdayHours,
        private readonly string $sundayHours
    ) {
    }

    /**
     * Get Monday to Friday hours
     *
     * @return string
     */
    public function getMonFriHours(): string
    {
        return $this->monFriHours;
    }

    /**
     * Get Saturday hours
     *
     * @return string
     */
    public function getSaturdayHours(): string
    {
        return $this->saturdayHours;
    }

    /**
     * Get Sunday hours
     *
     * @return string
     */
    public function getSundayHours(): string
    {
        return $this->sundayHours;
    }
}
