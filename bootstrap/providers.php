<?php

use Barryvdh\DomPDF\ServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\InvoicerPanelProvider::class,
    App\Providers\VoltServiceProvider::class,
    ServiceProvider::class,
];
