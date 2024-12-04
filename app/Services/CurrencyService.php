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
     * @param CacheRepository $cache
     * @param array $currencies
     * @param string $cacheKey
     * @param int $cacheTtl
     * @param string $bankUrl
     */
    public function __construct(
        CacheRepository $cache,
        array $currencies,
        string $cacheKey,
        int $cacheTtl,
        string $bankUrl
    ) {
        $this->cache = $cache;
        $this->currencies = $currencies;
        $this->cacheKey = $cacheKey;
        $this->cacheTtl = $cacheTtl;
        $this->bankUrl = $bankUrl;
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
