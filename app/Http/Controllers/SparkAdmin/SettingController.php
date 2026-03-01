<?php

namespace App\Http\Controllers\SparkAdmin;

use App\Http\Controllers\Controller;
use Setting;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Helpers\Classes\DynamicCss;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system.settings');
    }

    /**
     * Display the premium settings interface.
     */
    public function index()
    {
        $locales = Language::getLocales();
        return view('spark-admin.settings.index', compact('locales'));
    }

    /**
     * Update site settings (Simplified for SparkAdmin).
     */
    public function update(Request $request, DynamicCss $dynamicCss)
    {
        $settings = $request->input('settings', []);
        
        // Handle ENV updates
        $this->handleEnvUpdates($settings);

        // Handle regular DB settings
        foreach ($settings as $key => $value) {
            if ($key !== 'env') {
                Setting::set($key, $value);
            }
        }

        // Handle File Uploads
        $this->handleFileUploads($request);

        Setting::save();
        $dynamicCss->build();

        return back()->with('success', 'Site settings updated successfully.');
    }

    protected function handleEnvUpdates($settings)
    {
        $env = DotenvEditor::load();
        $envMappings = [
            'app_name' => 'APP_NAME',
            'app_url' => 'APP_URL',
            'mail_from_name' => 'MAIL_FROM_NAME',
            'mail_from_address' => 'MAIL_FROM_ADDRESS'
        ];

        foreach ($envMappings as $key => $envKey) {
            if (isset($settings[$key])) {
                $env->setKey($envKey, $settings[$key]);
            }
        }
        $env->save();
    }

    protected function handleFileUploads($request)
    {
        $files = ['website_logo', 'website_logo_dark', 'favicon', 'og_image'];
        foreach ($files as $field) {
            if ($request->hasFile("settings.$field")) {
                $filename = fileUpload($request->file("settings.$field"), 'uploads');
                Setting::set($field, $filename);
            }
        }
    }
}
