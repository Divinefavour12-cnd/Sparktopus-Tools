<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tool;

$tool = Tool::where('slug', 'ai-humanizer')->first();

if (!$tool) {
    echo "Tool not found!\n";
    exit(1);
}

$properties = $tool->properties ?? [];

// Ensure structure exists
if (!isset($properties['properties'])) {
    $properties['properties'] = [];
}
if (!in_array('fs-tool', $properties['properties'])) {
    $properties['properties'][] = 'fs-tool';
}
if (!isset($properties['guest'])) {
    $properties['guest'] = [];
}
if (!isset($properties['auth'])) {
    $properties['auth'] = [];
}

// Set file size limits (in MB)
$properties['guest']['fs-tool'] = 2;  // 2MB for guests
$properties['auth']['fs-tool'] = 5;   // 5MB for authenticated users

// Save
$tool->properties = $properties;
$tool->save();

echo "✅ Updated ai-humanizer tool with fs-tool property:\n";
echo "   Guest limit: 2MB\n";
echo "   Auth limit: 5MB\n";
