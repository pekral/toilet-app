<?php

/*
| Compiled view path – vytvoříme složku hned při načtení configu (během
| composer post-autoload / package:discover), aby Forge nehlásil "valid cache path".
*/
$compiledPath = env('VIEW_COMPILED_PATH') ?: storage_path('framework/views');
if ($compiledPath !== '' && ! is_dir($compiledPath)) {
    @mkdir($compiledPath, 0755, true);
}

return [

    'paths' => [
        resource_path('views'),
    ],

    'compiled' => $compiledPath ?: storage_path('framework/views'),

];
