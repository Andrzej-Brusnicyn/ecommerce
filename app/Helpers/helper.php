<?php
if (!function_exists('getCurrencyPriceKey')) {
    /**
     * This function generates a price key for a given currency
     * @param string $currency The currency code
     * @return string The generated price key
     */

    function getCurrencyPriceKey(string $currency): string
    {
        return 'price_' . strtolower($currency);
    }
}
