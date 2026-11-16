<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $itemsQuery = GalleryItem::with('gallery')->latest();

        if ($request->has('category') && $request->category !== 'all') {
            $itemsQuery->where('caption_en', $request->category);
        }

        $items = $itemsQuery->paginate(12);
        $categories = GalleryItem::distinct()->pluck('caption_en')->filter();

        return view('pages.gallery', compact('items', 'categories'));
    }
}
