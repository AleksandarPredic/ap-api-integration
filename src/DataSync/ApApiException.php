<?php

namespace ApApi\DataSync;

if (!defined('ABSPATH')) {
    exit;
}

class ApApiException extends \Exception
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param string $message
     * @param array $data
     */
    public function __construct($message, array $data)
    {
        parent::__construct($message);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
