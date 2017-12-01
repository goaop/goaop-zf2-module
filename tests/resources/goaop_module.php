<?php

return [
    'debug'          => true,
    'appDir'         => __DIR__ . '/../',
    'cacheDir'       => __DIR__ . '/cache',
    'cacheFileMode'  => 0770 & ~umask(),
    'features'       => 0,
    'includePaths'   => [
        __DIR__ . '/../Advice'
    ],
    'excludePaths'   => [],
    'containerClass' => \Go\Core\GoAspectContainer::class,
];
