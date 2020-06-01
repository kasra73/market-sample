<?php

namespace App\Jobs;

use App\Product;
use App\Services\ElasticsearchService;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class IndexProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Product
     */
    private $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ElasticsearchService $elasticsearchService)
    {
        Log::debug('Index product in elasticsearch', [ 'product' => $this->product->id ]);
        $params = [
            'index' => config('search.products_index'),
            'type' => 'product',
            'id' => $this->product->id,
            'body' => [
                'name' => $this->product->name,
                'description' => $this->product->description,
                 // we can also use array type if have recursive or multilevel categories
                'category' => $this->product->category->title,
                'categoryId' => $this->product->category->id
            ]
        ];
        /** @var ElasticsearchClient */
        $elasticsearchClient = $elasticsearchService->getClient();
        $elasticsearchClient->create($params);
        Log::info('new product indexed', ['product_id' => $this->product->id]);
    }
}
