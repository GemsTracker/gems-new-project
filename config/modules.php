<?php

declare(strict_types=1);

return [
    \Gems\ConfigProvider::class,
    \Gems\LegacyConfigProvider::class,
    \Gems\Dev\ConfigProvider::class,

    // Default App module config
    App\ConfigProvider::class,
];