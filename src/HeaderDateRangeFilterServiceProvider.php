<?php

namespace Keytactic\HeaderDateRangeFilter;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HeaderDateRangeFilterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'headerdaterangefilter';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        // Register assets if needed - we'll keep this simple for now
        // FilamentAsset::register([
        //     Css::make('headerdaterangefilter-styles', __DIR__ . '/../resources/dist/headerdaterangefilter.css'),
        //     Js::make('headerdaterangefilter-scripts', __DIR__ . '/../resources/dist/headerdaterangefilter.js'),
        // ], $this->getAssetPackageName());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'keytactic/headerdaterangefilter';
    }
}