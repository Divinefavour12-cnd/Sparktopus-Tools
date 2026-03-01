<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Language;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:content.moderate')->only(['index']);
        $this->middleware('permission:content.create')->only(['create', 'store']);
        $this->middleware('permission:content.edit')->only(['edit', 'update']);
        $this->middleware('permission:content.delete')->only(['destroy']);
    }

    /**
     * Display a listing of updates.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $posts = Post::with(['translations', 'author'])
            ->when($search, function($q) use ($search) {
                $q->whereTranslationLike('title', "%$search%");
            })
            ->latest()
            ->paginate(15);

        return view('spark-admin.updates.index', compact('posts', 'search'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $locales = Language::getLocales();
        $categories = Category::with('translations')->post()->get();
        return view('spark-admin.updates.create', compact('locales', 'categories'));
    }

    /**
     * Store a new update.
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'author_id' => 'required',
            'en.title' => 'required|string|max:255',
        ]);

        $post = Post::create($request->only('status', 'author_id'));
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if (!empty($translation[$lang->locale]['title'])) {
                $post->fill($translation);
            }
        }
        $post->save();

        if ($request->hasFile("featured_image")) {
            $post->addMediaFromRequest("featured_image")->toMediaCollection('featured-image');
        }

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        return redirect()->route('spark-admin.updates.index')->with('success', 'Update created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Post $post)
    {
        $post->load('media');
        $locales = Language::getLocales();
        $categories = Category::with('translations')->post()->get();
        return view('spark-admin.updates.edit', compact('post', 'locales', 'categories'));
    }

    /**
     * Update an existing update.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'status' => 'required',
            'author_id' => 'required',
            'en.title' => 'required|string|max:255',
        ]);

        $post->update($request->only('status', 'author_id'));
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if (!empty($translation[$lang->locale]['title'])) {
                $post->fill($translation);
            }
        }
        $post->save();

        if ($request->hasFile("featured_image")) {
            $post->clearMediaCollection("featured-image");
            $post->addMediaFromRequest("featured_image")->toMediaCollection('featured-image');
        }

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        return redirect()->route('spark-admin.updates.index')->with('success', 'Update updated successfully.');
    }

    /**
     * Delete an update.
     */
    public function destroy(Post $post)
    {
        $post->forceDelete();
        return redirect()->route('spark-admin.updates.index')->with('success', 'Update deleted.');
    }
}
