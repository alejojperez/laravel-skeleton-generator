<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;

class GenerateRepositoryCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'alejojperez-generate:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Data\Repositories\Implementations';
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        parent::replaceNamespace($stub, $name);

        $stub = str_replace(
            'DummyEntityClass', $this->getEntityClass(), $stub
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return str_singular( ucfirst( str_replace('Repository', '', $this->getNameInput()) ) );
    }
}
