<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\GeneratorCommand;

class GenerateTransformerCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "generate:transformer {name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new entity transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * @return mixed
     */
    public function getStub()
    {
        return __DIR__.'/stubs/transformer.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Data\Transformers';
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

        $stub = str_replace(
            '// DummyArrayReturn', $this->getReturnContent(), $stub
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return ucfirst( str_replace('Transformer', '', $this->getNameInput()) );
    }

    /**
     * @return string
     */
    protected function getReturnContent()
    {
        $response = "\n\t\t\t'id' => $"."entity->id,";
        $entity = app('\App\Data\Entities\\'.$this->getEntityClass());

        $reflectionClass = new \ReflectionClass($entity);

        $fillables = $reflectionClass->getProperty('fillable');
        $fillables->setAccessible(true);
        $fillables = $fillables->getValue($entity);

        foreach ($fillables as $value) {
            $response .= "\n\t\t\t'{$value}' => $"."entity->{$value},";
        }

        return $response;
    }
}
