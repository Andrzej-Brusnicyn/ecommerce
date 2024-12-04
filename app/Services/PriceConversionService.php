<?php
namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PriceConversionService
{
    private CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function convertPrices(LengthAwarePaginator $products, array $currencies): LengthAwarePaginator
    {
        foreach ($products as $product) {
            foreach ($currencies as $currency) {
                $priceKey = 'price_' . strtolower($currency);
                $product->$priceKey = $this->currencyService->convert($product->price, $currency);
            }
        }

        return $products;
    }
}
