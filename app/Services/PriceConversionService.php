<?php
namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\Services\CurrencyService;
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
                $priceKey = getCurrencyPriceKey($currency);
                $product->$priceKey = $this->currencyService->convert($product->price, $currency);
            }
        }

        return $products;
    }
}
