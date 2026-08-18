<?php

namespace App\Providers;

use App\Services\TranslationService;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;

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
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Event::listen([JobProcessed::class, JobExceptionOccurred::class], function () {
            Mail::purge();
        });

        $this->app->extend('translation.loader', function (FileLoader $originalLoader, $app) {
            $files = $app['files'];
            $path = $app['path.lang'];

            // Bind the original file loader to TranslationService to prevent infinite loops
            $app->when(TranslationService::class)
                ->needs(FileLoader::class)
                ->give(fn () => $originalLoader);

            $translationService = $app->make(TranslationService::class);

            return new class($originalLoader, $translationService, $files, $path) extends FileLoader
            {
                public function __construct(
                    protected FileLoader $fileLoader,
                    protected TranslationService $translationService,
                    $files,
                    $path
                ) {
                    parent::__construct($files, $path);
                }

                /**
                 * Load the messages for the given locale and group.
                 */
                public function load($locale, $group, $namespace = null): array
                {
                    // For namespaced translations, use file loader
                    if ($namespace !== null && $namespace !== '*') {
                        return $this->fileLoader->load($locale, $group, $namespace);
                    }

                    // Load from database first, then merge with file translations
                    return $this->translationService->loadTranslations($locale, $group);
                }
            };
        });
    }
}
