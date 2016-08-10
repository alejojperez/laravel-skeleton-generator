<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;

class GenerateRepositoryServiceProviderCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'alejojperez-generate:repository-service-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new repository\'s service provider.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository\'s Service Provider';

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/repository-service-provider.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Data\Repositories\Binders';
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
            'DummyRepository', $this->getRepositoryClass(), $stub
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getRepositoryClass()
    {
        return ucfirst( str_replace('ServiceProvider', '', $this->getNameInput()) );
    }
}
