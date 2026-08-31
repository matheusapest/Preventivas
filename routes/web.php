<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Arquivo principal de rotas
|--------------------------------------------------------------------------
|
| Responsável apenas por carregar os arquivos de rotas dos módulos
| da aplicação.
|
*/

require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
require __DIR__ . '/users.php';
require __DIR__ . '/companies.php';
require __DIR__ . '/branches.php';
require __DIR__ . '/branch-codes.php';
require __DIR__ . '/categories.php';
require __DIR__ . '/manufacturers.php';
require __DIR__ . '/equipment-models.php';
require __DIR__ . '/equipments.php';
require __DIR__ . '/transfer.php';
require __DIR__ . '/maintenance-external.php';
require __DIR__ . '/api.php';

require __DIR__ . '/preventives-execution.php';

require __DIR__ . '/configurations/unit-types.php';
require __DIR__ . '/configurations/operational-profiles.php';
require __DIR__ . '/configurations/operational-unit.php';
require __DIR__ . '/configurations/preventive-types.php';
require __DIR__ . '/configurations/activities.php';
require __DIR__ . '/configurations/activity-categories.php';
require __DIR__ . '/configurations/preventive-profiles.php';
require __DIR__ . '/configurations/preventives.php';
require __DIR__ . '/test.php';

