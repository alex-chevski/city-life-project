<?php

declare(strict_types=1);

return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOSTS')),
    'retries' => 1,
];
