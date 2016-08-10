<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateJobValidatorCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "alejojperez-generate:job-validator {name} {--type=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job validator class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Job Validator';

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/job-validator.stub';
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
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];


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
        
        $replace = $this->getReturnContent();
        
        if(!empty($this->option('type')) && ($this->option('type') == 'delete' || $this->option('type') == 'edit'))
        {
            if($this->option('type') == 'delete') {
                $table = snake_case(explode("/", $this->getNameInput())[0]);
                $replace = "\n\t\t\t\"id\" => \"required|exists:$table,id\",";
            }
            elseif ($this->option('type') == 'edit') {
                $table = snake_case(explode("/", $this->getNameInput())[0]);
                $idRule = "\n\t\t\t\"id\" => \"required|exists:$table,id\",";
                $replace = $idRule . $replace;
            }
        }
        
        $stub = str_replace(
            '// Validation Rules', $replace, $stub
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return str_singular(explode("/", $this->getNameInput())[0]);
    }

    /**
     * @return string
     */
    protected function getReturnContent()
    {
        $response = '';
        $entity = app('\App\Data\Entities\\'.$this->getEntityClass());

        $reflectionClass = new \ReflectionClass($entity);

        $fillables = $reflectionClass->getProperty('fillable');
        $fillables->setAccessible(true);
        $fillables = $fillables->getValue($entity);

        foreach ($fillables as $value) {
            $response .= "\n\t\t\t\"{$value}\" => \"\",";
        }

        return $response;
    }
}
