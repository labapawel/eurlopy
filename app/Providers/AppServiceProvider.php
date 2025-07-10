<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

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
            //  FilamentView::registerRenderHook(
            //         'panels::styles.after',
            //         fn (): string => '<style>
            //         body {
            //             margin: 0;
            //             padding-bottom: 80px; /* Wysokość stopki + margines */
            //         }
                    
            //         .fi-footer {
            //             position: fixed;
            //             bottom: 0;
            //             left: 0;
            //             right: 0;
            //             width: 100%;
            //             z-index: 1000;
            //             background: white;
            //             border-top: 1px solid #e5e7eb;
            //         }
                    
            //         .dark .fi-footer {
            //             background: rgb(24, 24, 27);
            //             border-top-color: #374151;
            //         }
                    
            //         /* Usuń przewijanie poziome */
            //         html, body {
            //             overflow-x: hidden;
            //         }
            //     </style>'
            //     );

        FilamentView::registerRenderHook(
            'panels::body.end',
                fn (): string => view('components.layouts.footer')->render()
            );
    }
}
