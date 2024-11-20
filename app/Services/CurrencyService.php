<?php

namespace App\Services;
use Illuminate\Support\Facades\Cache;
use Exception;
use Aws\S3\S3Client;

class CurrencyService
{
    private array $currencies = ['USD', 'EUR', 'RUB'];
    private const CACHE_KEY = 'currency_rates';
    private const CACHE_TTL = 3600;

    /**
     * Convert an amount to the specified currency.
     *
     * @param float $amount
     * @param string $currency
     * @return float
     * @throws Exception
     */
    public function convert(float $amount, string $currency): float
    {
        $rate = $this->getRate($currency);
        return round($amount / $rate, 2);
    }

    /**
     * Get the rate for the specified currency.
     *
     * @param string $currency
     * @return float
     * @throws Exception
     */
    private function getRate(string $currency): float
    {
        $rates = $this->getRates();

        if (!isset($rates[$currency])) {
            throw new Exception("Currency rate for {$currency} not found.");
        }

        return $rates[$currency];
    }

    /**
     * Get the cached currency rates.
     *
     * @return array
     */
    private function getRates(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->fetchRatesFromBank();
        });
    }

    /**
     * Fetch currency rates from the bank.
     *
     * @return array
     * @throws Exception
     */
    private function fetchRatesFromBank(): array
    {
        $rates = [];
        $xml = simplexml_load_file('https://bankdabrabyt.by/export_courses.php');

        foreach ($xml->filials->filial[0]->rates->value as $value) {
            $currency = (string)$value['iso'];
            if (in_array($currency, $this->currencies)) {
                $rates[$currency] = (float)$value['sale'];
            }
        }

        if (empty($rates)) {
            throw new Exception('Failed to fetch currency rates from bank');
        }

        return $rates;
    }
}
