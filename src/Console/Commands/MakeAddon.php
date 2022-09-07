<?php

namespace Streams\Sdk\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class MakeAddon extends Command
{
    protected $signature = 'make:addon
        {name : The addon package name.}';

    protected $description = 'Make an addon for Laravel Streams.';

    public function handle()
    {
        $name = $this->argument('name');

        if (!preg_match("/^[a-z0-9]([_.-]?[a-z0-9]+)*\/[a-z0-9](([_.]?|-{0,2})[a-z0-9]+)*$/", $name)) {
            throw new \Exception("The name [{$name}] is not a valid: https://getcomposer.org/doc/04-schema.md#name");
        }

        $description = $this->ask('A short description of the package', 'An addon for Laravel Streams.');

        list($vendor, $name) = explode('/', $name);

        $namespace = implode('\\', [
            Str::studly($vendor),
            Str::studly($name),
        ]);

        $class = Str::studly($name) . 'Provider';

        $provider = $namespace . '\\' . $class;

        $composer = View::parse(
            file_get_contents(__DIR__ . '/stubs/composer.stub'),
            compact(
                'name',
                'vendor',
                'description',
                'namespace',
                'provider',
            )
        );

        if (!File::isDirectory($path = base_path("addons/$vendor/$name"))) {
            File::makeDirectory($path, 0755, true);
        }

        File::put($path . '/composer.json', $composer);

        $provider = View::parse(
            file_get_contents(__DIR__ . '/stubs/provider.stub'),
            compact(
                'namespace',
                'class',
            )
        );

        if (!File::isDirectory($path . '/src')) {
            File::makeDirectory($path . '/src', 0755, true);
        }

        File::put($path . '/src/' . $class . '.php', "<?php\n\n" . $provider);
    }
}
