<?php

namespace App\Services;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Exception;

class CurrencyService
{
    private array $currencies;
    private string $cacheKey;
    private int $cacheTtl;
    private string $bankUrl;
    private CacheRepository $cache;

    /**
     * CurrencyService constructor.
     *
     */
    public function __construct(CacheRepository $cache)
    {
        $this->currencies = config('constants.currencies');
        $this->cacheKey = config('constants.currency.cache_key');
        $this->cacheTtl = config('constants.currency.cache_ttl');
        $this->bankUrl = config('constants.currency.bank_url');
        $this->cache = $cache;
    }

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
        return $this->cache->remember($this->cacheKey, $this->cacheTtl, function () {
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
        try {
            $xml = simplexml_load_file($this->bankUrl);

            if ($xml === false) {
                throw new Exception('Failed to load XML from the bank');
            }

            foreach ($xml->filials->filial[0]->rates->value as $value) {
                $currency = (string)$value['iso'];
                if (in_array($currency, $this->currencies)) {
                    $rates[$currency] = (float)$value['sale'];
                }
            }

            if (empty($rates)) {
                throw new Exception('No valid currency rates found');
            }

        } catch (Exception $e) {
            throw new Exception('Failed to fetch currency rates: ' . $e->getMessage());
        }

        return $rates;
    }
}
