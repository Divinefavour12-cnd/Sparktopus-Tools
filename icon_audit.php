<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tool;
use Illuminate\Support\Facades\DB;

$tools = Tool::all();
$defaultIcon = 'bi-tools';

// More comprehensive mapping for common tool types
$iconMapping = [
    // Text/AI
    'summarizer' => 'bi-file-earmark-text',
    'rewriter' => 'bi-pencil-square',
    'checker' => 'bi-shield-check',
    'generator' => 'bi-gear-wide-connected',
    'converter' => 'bi-arrow-left-right',
    'translator' => 'bi-translate',
    'spinner' => 'bi-arrow-repeat',
    
    // PDF/Files
    'pdf' => 'bi-file-earmark-pdf',
    'jpg' => 'bi-file-earmark-image',
    'png' => 'bi-file-earmark-image',
    'image' => 'bi-image',
    'word' => 'bi-file-earmark-word',
    'excel' => 'bi-file-earmark-excel',
    'zip' => 'bi-file-zip',
    
    // SEO/Web
    'seo' => 'bi-graph-up-arrow',
    'domain' => 'bi-globe',
    'hosting' => 'bi-server',
    'ip' => 'bi-laptop',
    'dns' => 'bi-journal-code',
    'sitemap' => 'bi-diagram-3',
    'analytics' => 'bi-bar-chart-line',
    
    // Dev Tools
    'json' => 'bi-filetype-json',
    'xml' => 'bi-filetype-xml',
    'html' => 'bi-filetype-html',
    'css' => 'bi-filetype-css',
    'js' => 'bi-filetype-js',
    'binary' => 'bi-file-binary',
    'hex' => 'bi-file-code',
    'base64' => 'bi-file-lock',
    
    // Math/Calc
    'calculator' => 'bi-calculator',
    'percentage' => 'bi-percent',
    'tax' => 'bi-receipt',
    'discount' => 'bi-tag',
];

echo "Auditing tool icons...\n";
$updatedCount = 0;

foreach ($tools as $tool) {
    $currentIcon = $tool->icon_class;
    
    // If icon is missing or looks like a placeholder
    if (empty($currentIcon) || $currentIcon == 'bi-tools' || $currentIcon == 'online-text-editor') {
        $slug = strtolower($tool->slug);
        $newIcon = $defaultIcon;
        
        foreach ($iconMapping as $keyword => $icon) {
            if (strpos($slug, $keyword) !== false) {
                $newIcon = $icon;
                break;
            }
        }
        
        if ($newIcon !== $currentIcon) {
            $tool->icon_class = $newIcon;
            $tool->icon_type = 'class';
            $tool->save();
            echo "Updated tool '{$tool->name}' icon to: {$newIcon}\n";
            $updatedCount++;
        }
    }
}

echo "Icon audit complete. Total updated: {$updatedCount}\n";
