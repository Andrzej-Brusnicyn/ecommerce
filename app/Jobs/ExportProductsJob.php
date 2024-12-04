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

    /**
     * ExportProductsJob constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Filesystem $filesystem
     */
    public function __construct(ProductRepositoryInterface $productRepository, Filesystem $filesystem)
    {
        $this->productRepository = $productRepository;
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->productRepository->chunk(1000, function ($products) {
            $this->filesystem->put(config('constants.storage.s3.products_path'), $products->toJson());
        });
    }
}
