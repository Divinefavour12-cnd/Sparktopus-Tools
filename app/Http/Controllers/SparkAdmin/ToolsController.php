<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Language;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ToolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tool.manage');
    }

    /**
     * Display a listing of tools in premium SparkAdmin style.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $tools = Tool::query()
            ->when($search, function ($query) use ($search) {
                $query->whereTranslationLike('name', "%$search%")
                      ->orWhere('slug', 'like', "%$search%");
            })
            ->with(['category', 'media', 'translations', 'views'])
            ->paginate(20);

        return view('spark-admin.tools.index', compact('tools', 'search'));
    }

    /**
     * View for editing a specific tool.
     */
    public function edit(Tool $tool)
    {
        $locales = Language::getLocales();
        $categories = Category::with('translations')->tool()->get();
        $plans = \App\Models\Plan::active()->get();
        $tags = Tag::with('translations')->get();
        $properties = Property::active()->with('translations')->get();

        $instance = new $tool->class_name();
        $form_fields = [];
        if (method_exists($instance, 'getFileds')) {
            $form_fields = $instance->getFileds();
        }

        return view('spark-admin.tools.edit', compact('locales', 'tool', 'categories', 'tags', 'form_fields', 'properties', 'plans'));
    }

    /**
     * Update tool settings and translations.
     */
    public function update(Request $request, Tool $tool)
    {
        // Simple validation for core fields
        $request->validate([
            'slug' => 'required|max:150|unique:tools,slug,' . $tool->id,
            'category' => 'required',
        ]);

        $instance = new $tool->class_name();
        if (method_exists($instance, 'getFileds')) {
            $form_fields = $instance->getFileds();
            $settings_rules = [];
            foreach ($form_fields['fields'] as $field) {
                $settings_rules[$field['id']] = $field['validation'] ?? 'nullable';
            }
            Validator::validate($request->input('settings', []), $settings_rules);
        }

        // Sync category
        $tool->category()->sync([$request->category]);
        
        // Update basic data
        $tool->update($request->only(['slug', 'icon_type', 'icon_class', 'display', 'is_home', 'settings', 'required_plan']));

        // Handle File Uploads (Icon)
        if ($request->hasFile("icon")) {
            $tool->clearMediaCollection("tool-icon");
            $tool->addMediaFromRequest("icon")->toMediaCollection('tool-icon');
        }

        // Handle Translations
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if (!empty($translation[$lang->locale]['name'])) {
                // Handle OG Image for translation if provided
                if ($request->file("{$lang->locale}.og_image")) {
                    $tool->clearMediaCollection("{$lang->locale}-og-image");
                    $tool->addMediaFromRequest("{$lang->locale}.og_image")->toMediaCollection("{$lang->locale}-og-image");
                }
                unset($translation[$lang->locale]['og_image']);
                $tool->fill($translation);
            }
        }

        // Handle Dynamic Properties
        $props_data = $tool->properties;
        if (isset($props_data['properties']) && is_array($props_data['properties'])) {
            foreach ($props_data['properties'] as $property) {
                $key_guest = "property_{$property}_guest";
                $key_auth = "property_{$property}_auth";
                $props_data['auth'][$property] = $request->$key_auth;
                $props_data['guest'][$property] = $request->$key_guest;
            }
            $tool->properties = $props_data;
        }

        $tool->save();

        return redirect()->route('spark-admin.tools.index')->with('success', 'Tool ' . $tool->name . ' updated successfully.');
    }

    /**
     * Change tool status (Active/Inactive).
     */
    public function statusChange(Tool $tool)
    {
        $tool->update(['status' => !$tool->status]);
        return back()->with('success', 'Tool ' . ($tool->status ? 'activated' : 'deactivated') . ' successfully.');
    }

    /**
     * View for managing homepage tool selection.
     */
    public function homePage(Request $request)
    {
        $tools = Tool::active()->get()->filter(function ($tool) {
            return ($tool && class_exists($tool->class_name) && method_exists($tool->class_name, 'index'));
        });

        return view('spark-admin.tools.homepage', compact('tools'));
    }

    /**
     * Set the tool for the main landing page.
     */
    public function setHome(Tool $tool)
    {
        Tool::where('is_home', 1)->update(['is_home' => 0]);
        $tool->update(['is_home' => 1]);
        
        return back()->with('success', 'Homepage tool updated to: ' . $tool->name);
    }
}
