<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ad.manage');
    }

    /**
     * Display a listing of ads in SparkAdmin style.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $advertisements = Advertisement::when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                      ->orWhere('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate(15);

        return view('spark-admin.advertisement.index', compact('advertisements', 'search'));
    }

    /**
     * Show create form.
     */
    public function create($type)
    {
        if (!in_array($type, ['1', '2', '3'])) {
            return redirect()->route('spark-admin.advertisement.index');
        }
        return view('spark-admin.advertisement.create', compact('type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required',
            'name' => 'required|string',
            'options.countdown' => 'nullable|integer|min:0',
        ]);

        $options = $request->options;
        if ($request->file("options.image")) {
            if ($image = fileUpload($request->file("options.image"))) {
                $options['image'] = $image;
            }
        }

        if ($request->file("options.video")) {
            if ($video = fileUpload($request->file("options.video"))) {
                $options['video'] = $video;
            }
        }

        Advertisement::create([
            'title' => $request->title,
            'type' => $request->type,
            'options' => $options,
            'name' => $request->name,
            'status' => 1
        ]);

        return redirect()->route('spark-admin.advertisement.index')->with('success', 'Advertisement created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Advertisement $advertisement)
    {
        return view('spark-admin.advertisement.edit', compact('advertisement'));
    }

    /**
     * Update an ad.
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required',
            'name' => 'required|string',
            'options.countdown' => 'nullable|integer|min:0',
        ]);

        $options = $request->options;
        if ($request->file("options.image")) {
            if ($image = fileUpload($request->file("options.image"))) {
                $options['image'] = $image;
            }
        }

        if ($request->file("options.video")) {
            if ($video = fileUpload($request->file("options.video"))) {
                $options['video'] = $video;
            }
        }

        $advertisement->update([
            'title' => $request->title,
            'type' => $request->type,
            'options' => $options,
            'name' => $request->name,
        ]);

        return redirect()->route('spark-admin.advertisement.index')->with('success', 'Advertisement updated successfully.');
    }

    /**
     * Toggle status.
     */
    public function statusChange(Advertisement $advertisement)
    {
        $advertisement->update(['status' => !$advertisement->status]);
        return back()->with('success', 'Ad status updated.');
    }

    /**
     * Delete an ad.
     */
    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();
        return back()->with('success', 'Advertisement deleted.');
    }
}
