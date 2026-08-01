<?php

namespace App\Modules\Projects\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        $response = [
            'data' => ProjectResource::collection($this->collection),
        ];

        if ($this->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            $response['links'] = $this->paginationLinks();
            $response['meta'] = [
                'pagination' => [
                    'total' => $this->resource->total(),
                    'count' => $this->resource->count(),
                    'per_page' => $this->resource->perPage(),
                    'current_page' => $this->resource->currentPage(),
                    'total_pages' => $this->resource->lastPage(),
                ],
            ];
        }

        return $response;
    }

    protected function paginationLinks()
    {
        $paginator = $this->resource;
        $nextPage = $paginator->currentPage() + 1;
        $links = $nextPage <= $paginator->lastPage() ? $paginator->url($nextPage) : null;

        return $links;
    }
}
