<?php

return [
    'debug'          => true,
    'appDir'         => __DIR__,
    'cacheDir'       => __DIR__,
    'cacheFileMode'  => 0770 & ~umask(),
    'features'       => 0,
    'includePaths'   => [],
    'excludePaths'   => [],
    'containerClass' => \Go\Core\GoAspectContainer::class,
];
