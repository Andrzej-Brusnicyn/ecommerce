<?php

namespace App\Services;
use Aws\S3\S3Client;

class CurrencyService
{
    private $currencies = ['USD', 'EUR', 'RUB'];

    public function convert($amount, $currency): float
    {
        $rate = $this->getRate($currency);
        return round($amount / $rate, 2);
    }

    private function getRate($currency)
    {
        $xml = simplexml_load_file('https://bankdabrabyt.by/export_courses.php');

        foreach ($xml->filials->filial[0]->rates->value as $value) {
            if ((string)$value['iso'] === $currency && in_array($currency, $this->currencies)) {
                return (float)$value['sale'];
            }
        }

        throw new Exception("Currency rate for {$currency} not found.");
    }
}
