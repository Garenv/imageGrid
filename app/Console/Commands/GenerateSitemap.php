<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0))
            ->add(Url::create('/login')->setPriority(0.8))
            ->add(Url::create('/faq')->setPriority(0.5))
            ->add(Url::create('/contact-us')->setPriority(0.5))
            ->writeToFile(public_path('sitemap.xml'));

        Log::channel('site_map')->info("Generated sitemap successfully");
    }
}
