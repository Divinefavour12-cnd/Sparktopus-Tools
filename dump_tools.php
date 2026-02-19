<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tools = \App\Models\Tool::with('translations')->get()->map(function($tool) {
    return [
        'id' => $tool->id,
        'slug' => $tool->slug,
        'name' => $tool->name,
        'icon_class' => $tool->icon_class,
        'icon_type' => $tool->icon_type,
    ];
});

file_put_contents('tools_dump.json', json_encode($tools, JSON_PRETTY_PRINT));
echo "Dumped " . $tools->count() . " tools to tools_dump.json\n";
