<?php

namespace App\Services\Search;

use App\Product;
use Elasticsearch\Client as ElasticsearchClient;
use App\Services\ElasticsearchService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ElasticsearchSearchService implements SearchServiceInterface
{
    /** @var ElasticsearchClient */
    private $elasticsearchClient;

    /**
     * SearchUtil constructor.
     * @param ElasticSearchService $container
     */
    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchClient = $elasticsearchService->getClient();
    }

    /**
     * @inheritDoc
     */
    public function search(string $query = null, int $category = null, int $per_page = 10, int $page = 1)
    {
        // ignore elasticsearch if query is null
        if ($query === null) {
            $queryBuilder = Product::with('category');
            if ($category !== null) {
                $queryBuilder->where('category_id', $category);
            }
            return $queryBuilder->paginate($per_page, ['*'], 'page', $page);
        }
        $elasticQueryBody = array(
            "from" => ($page - 1) * $per_page,
            "size" => $per_page,
            "query" => [
                "bool" => [
                    "must" => [
                        "multi_match" => [
                            "query" => $query,
                            "fields" => ["name", "description", "category"]
                        ]
                    ],
                ]
            ],
            "stored_fields" => []
        );
        if ($category !== null) {
            $elasticQueryBody['query']['bool']['filter'] = [
                "term" =>  ["categoryId" => $category]
            ];
        }
        $result = $this->elasticsearchClient->search([
            'index' => config('search.products_index'),
            'body' => $elasticQueryBody
        ]);
        $products = $result['hits']['hits'];
        $productIds = [];
        foreach($products as $product) {
            $productIds[] = $product['_id'];
        }
        if (count ($productIds) > 0) {
            $items = $this->getSortedProducts($productIds);
        } else {
            $items = [];
        }
        return new LengthAwarePaginator($items, $result['hits']['total'], $per_page, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    private function getSortedProducts(array $productIds)
    {
        $ids = join(",", $productIds);
        // ids don't come from user inputs, so we assume it's secure to use without binding
        return Product::whereIn('id', $productIds)
            ->with('category')
            ->orderBy(DB::raw("FIELD(id, $ids)"), 'ASC')
            ->get();
    }
}
