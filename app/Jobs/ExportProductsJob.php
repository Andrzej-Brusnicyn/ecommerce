<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;

class ExportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductRepositoryInterface $productRepository;
    private Filesystem $filesystem;
    private string $productsPath;

    /**
     * ExportProductsJob constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Filesystem $filesystem
     * @param string $productsPath
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Filesystem $filesystem,
        string $productsPath
    ) {
        $this->productRepository = $productRepository;
        $this->filesystem = $filesystem;
        $this->productsPath = $productsPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->productRepository->chunk(1000, function ($products) {
            $this->filesystem->put($this->productsPath, $products->toJson());
        });
    }
}
