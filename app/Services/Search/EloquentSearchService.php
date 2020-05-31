<?php

namespace App\Services\Search;

use App\Product;

class EloquentSearchService implements SearchServiceInterface
{
    /**
     * @inheritDoc
     */
    public function search(string $query = null, int $category = null, int $per_page = 10, int $page = 1)
    {
        $queryBuilder = Product::query();
        if ($category !== null) {
            $queryBuilder->where('category_id', $category);
        }
        if ($query !== null) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            });
        }
        $products = $queryBuilder->paginate($per_page, ['*'], 'page', $page);
        return $products;
    }
}
