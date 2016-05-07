<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;

class GenerateJobCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "generate:job {name} {--validator} {--type=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Job';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        parent::fire();

        if($this->option('validator'))
            $this->callCreateValidator();
    }

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/job.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Jobs';
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
            'DummyRepository', $this->getRepositoryName(), $stub
        );

        if(!empty($this->option('type')))
        {
            $stub = str_replace(
                '// Handle Logic', $this->getHandleContent($this->option('type')), $stub
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected  function getEntityName()
    {
        return ucfirst( str_singular( explode("/", $this->getNameInput())[0] ));
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return ucfirst( str_plural( explode("/", $this->getNameInput())[0] )."Repository" );
    }

    /**
     * @return string
     */
    protected function getValidatorName()
    {
        return $this->getNameInput()."Validator";
    }

    /**
     * @return void
     */
    protected function callCreateValidator()
    {
        $arguments['name'] = $this->getValidatorName();
        
        if(!empty($this->option('type')))
            $arguments['--type'] = $this->option('type');
        
        app('Illuminate\Contracts\Console\Kernel')->call('generate:job-validator', $arguments);
    }

    /**
     * @param $action
     *
     * @return string
     */
    protected function getHandleContent($action)
    {        
        $validActions = ['add', 'edit', 'delete'];
        
        if(in_array($action, $validActions)) {
            $response = "";
            $response .= '$entity = $this->repository->'.$action.'( $this->data, false );'."\n\n";
            $response .= "\t\t".'if(! $entity)'."\n";
            $response .= "\t\t\t".'return $this->defaultDatabaseErrorResponse();'."\n\n";
            $response .= "\t\t".'return $this->generateReturn($entity, $this->data, "'.$action.'");';

            return $response;
        }
        
        return '//';
    }
}
