<?php

namespace ApApi\DataSync;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * HTTP Methods enum
 * Defines supported HTTP methods for API requests
 */
enum HttpMethod: string
{
    case GET = 'get';
    case POST = 'post';
}
