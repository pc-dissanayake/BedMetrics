<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


FilamentColor::register([
    'slate' => Color::Slate,
    'gray' => Color::Gray,
    'zinc' => Color::Zinc,
    'neutral' => Color::Neutral,
    'stone' => Color::Stone,
    'red' => Color::Red,
    'orange' => Color::Orange,
    'amber' => Color::Amber,
    'yellow' => Color::Yellow,
    'lime' => Color::Lime,
    'green' => Color::Green,
    'emerald' => Color::Emerald,
    'teal' => Color::Teal,
    'cyan' => Color::Cyan,
    'sky' => Color::Sky,
    'blue' => Color::Blue,
    'indigo' => Color::Indigo,
    'violet' => Color::Violet,
    'purple' => Color::Purple,
    'fuchsia' => Color::Fuchsia,
    'pink' => Color::Pink,
    'rose' => Color::Rose,
]);
    }
}
