<?php

if (file_exists("heyframe/platform")) {
    exec('rm -rf heyframe/platform');
}

mkdir("heyframe/platform", 0777, true);

$components = [
    'core',
    'administration',
    'frontend',
    'elasticsearch',
];

foreach ($components as $component) {
    exec('cp -R heyframe/' . $component . '/* heyframe/platform');
}

$versions = array_filter(scandir("heyframe/core", SCANDIR_SORT_ASCENDING), function (string $dir) {
    return $dir[0] !== '.';
});

foreach ($versions as $version) {
    $mergedManifest = [];

    foreach($components as $component) {
        $componentManifest = json_decode(file_get_contents('heyframe/' . $component . '/' . $version . '/manifest.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach($componentManifest as $key => $value) {
            if ($key === 'copy-from-recipe') {
                $mergedManifest[$key] = array_replace_recursive($mergedManifest[$key] ?? [], $value);
                continue;
            }

            $mergedManifest[$key] = array_merge_recursive($mergedManifest[$key] ?? [], $value);
        }
    }

    file_put_contents('heyframe/platform/' . $version . '/manifest.json', json_encode($mergedManifest, JSON_PRETTY_PRINT) . PHP_EOL);
}
