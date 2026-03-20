<?php

namespace App\Services;

use Illuminate\Support\Str;

class InvoiceHelper
{
    public static function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
    }
}
