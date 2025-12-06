<?php

namespace ApApi\Widgets\Traits;

trait StoreTrait
{
    /**
     * Format phone number for tel: links
     *
     * Formats US phone numbers to XXX-XXX-XXXX format for tel: links.
     * Handles 10 and 11 digit numbers (removes leading 1 for country code).
     *
     * @param string $phone Raw phone number
     * @return string Formatted phone number for tel: links
     */
    private function formatPhoneForTel(string $phone): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($cleanPhone) === 11 && substr($cleanPhone, 0, 1) === '1') {
            $cleanPhone = substr($cleanPhone, 1);
        }

        if (strlen($cleanPhone) === 10) {
            return substr($cleanPhone, 0, 3) . '-' . substr($cleanPhone, 3, 3) . '-' . substr($cleanPhone, 6, 4);
        }

        return $cleanPhone;
    }
}
