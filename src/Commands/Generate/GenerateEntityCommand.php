<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;

class GenerateEntityCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "alejojperez-generate:entity
                            {name : The entity name; if there are not options specified, all the components will be created}
                            {--repository-contract : Create the entity repository contract}
                            {--repository : Create the entity repository implementation}
                            {--repository-service-provider : Create the entity service provider}
                            {--transformer : Create the entity transformer}
                            {--plain : Do not create any component}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new entity class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Entity';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        parent::fire();

        if($this->option('repository-contract'))
            $this->callCreateRepositoryContract();

        else if($this->option('repository'))
            $this->callCreateRepository();

        else if($this->option('repository-service-provider'))
            $this->callCreateRepositoryServiceProvider();

        else if($this->option('transformer'))
            $this->callCreateTransformer();

        else if( ! $this->option('plain') )
            $this->callCreateAllCompanionClasses();
    }

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/entity.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Data\Entities';
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
            'DummyTableName', strtolower( snake_case( str_plural( $this->getNameInput() ) ) ), $stub
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return ucfirst( str_plural($this->getNameInput())."Repository" );
    }

    /**
     * @return string
     */
    protected function getRepositoryServiceProviderName()
    {
        return $this->getRepositoryName()."ServiceProvider";
    }

    /**
     * @return string
     */
    protected function getTransformerName()
    {
        return $this->getNameInput()."Transformer";
    }

    /**
     * @return void
     */
    protected function callCreateAllCompanionClasses()
    {
        $this->callCreateRepositoryContract();
        $this->callCreateRepository();
        $this->callCreateRepositoryServiceProvider();
    }

    /**
     * @return void
     */
    protected function callCreateRepositoryContract()
    {
        app('Illuminate\Contracts\Console\Kernel')->call('generate:repository-contract', ['name' => $this->getRepositoryName()] );
    }

    /**
     * @return void
     */
    protected function callCreateRepository()
    {
        app('Illuminate\Contracts\Console\Kernel')->call('generate:repository', ['name' => $this->getRepositoryName()] );
    }

    /**
     * @return void
     */
    protected function callCreateRepositoryServiceProvider()
    {
        app('Illuminate\Contracts\Console\Kernel')->call('generate:repository-service-provider', ['name' => $this->getRepositoryServiceProviderName()] );
    }

    /**
     * @return void
     */
    protected function callCreateTransformer()
    {
        app('Illuminate\Contracts\Console\Kernel')->call('generate:transformer', ['name' => $this->getTransformerName()] );
    }
}
