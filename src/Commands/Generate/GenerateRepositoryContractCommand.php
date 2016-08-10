<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;

class GenerateRepositoryContractCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'alejojperez-generate:repository-contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new repository interface.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository Interface';

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/repository-contract.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Data\Repositories\Contracts';
    }
}
