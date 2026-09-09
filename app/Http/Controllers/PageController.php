<?php

namespace App\Http\Controllers;
use App\Models\Page;
use App\Http\Resources\PageResource;
use Illuminate\Http\Request;
use Illuminate\http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Page::query();

    //Search by title
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    //Filter by menu
    if ($request->filled('menu_id')) {
        $query->where('menu_id', $request->menu_id);
    }

    //Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $pages = $query->with('menu')->latest()->paginate($request->input('per_page', 10));

    return PageResource::collection($pages);

    }

    public function show(Page $page): PageResource
    {
        $page->load('menu');
        return new PageResource($page);
    }


    public function store(StorePageRequest $request): PageResource
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('pages', 'public');
        }

        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $page = Page::create($data);

        return new PageResource($page);
    }

    public function update(UpdatePageRequest $request, Page $page): PageResource
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            // Delete the old cover image if it exists
            if ($page->cover_image) {
                Storage::disk('public')->delete($page->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('pages', 'public');
        }

        $data['updated_by'] = $request->user()->id;

        $page->update($data);

        return new PageResource($page->fresh());
    }

    public function destroy(Request $request, Page $page)
    {
        $page->delete();

        return response()->json(['message' => 'Page deleted successfully.']);
    }

    public function restore(int $id):PageResource
    {
        $page = Page::withTrashed()->findOrFail($id);
        $page->restore();

        return new PageResource($page->fresh());
    }

    public function publicPages(){
        $pages = Page::with('menu')->where('status', 'published')->where(function ($query) {
            $query->whereNull('publish_at')->orWhere('publish_at', '<=', now());
        })->latest()->paginate(10);
        return PageResource::collection($pages);
    }
    public function publicShow(int $id): PageResource
    {
        $page = Page::with('menu')->where('status', 'published')->where(function ($query) {
            $query->whereNull('publish_at')->orWhere('publish_at', '<=', now());
        })->where('id', $id)->firstOrFail();
        return new PageResource($page);
    }
}
