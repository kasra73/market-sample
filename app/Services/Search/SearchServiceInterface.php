<?php

namespace App\Services\Search;

interface SearchServiceInterface
{
    /**
     * Search among products
     *
     * @param string $query
     * @param integer $category
     * @param integer $per_page
     * @param integer $page
     * @return Product[]
     */
    public function search(string $query = null, int $category = null, int $per_page = 10, int $page = 1);
}
