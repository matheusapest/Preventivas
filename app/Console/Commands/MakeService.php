<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeService extends GeneratorCommand
{
    protected $signature = 'make:service
                            {name : Nome do Service a ser criado}';

    protected $description = 'Create a new service class';

    protected $type = 'Service';

    protected function getStub(): string
    {
        return base_path('stubs/service.stub');
    }

    protected function getPath($name): string
    {
        $name = Str::replaceFirst(
            $this->rootNamespace(),
            '',
            $name
        );

        return app_path(
            str_replace('\\', '/', $name) . '.php'
        );
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Services';
    }
}