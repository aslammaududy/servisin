<?php

use App\Models\SiteSetting;

if (! function_exists('bank_account')) {
    function bank_account(): array
    {
        return SiteSetting::bankAccount();
    }
}
