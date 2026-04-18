<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryTreeResource;
use App\Services\CategoryTreeService;

class CategoryController extends Controller
{
    public function tree(CategoryTreeService $service)
    {
        $tree = $service->getTree();

        return response()->json([
            'data' => CategoryTreeResource::collection(collect($tree)),
        ]);
    }
}