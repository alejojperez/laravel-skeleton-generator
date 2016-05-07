# laravel-skeleton-generator
A package containing a set of commands to generate any kind of component

###Include the service provider
config/app.php

```php
return [

    "providers" => [
        ...
        LaravelSkeletonGenerator\LaravelSkeletonGeneratorServiceProvider::class,
        ...
    ]

];
```

###Include all the commands to the console
app/Console/Kernel.php

```php
...
protected $commands = [
    \LaravelSkeletonGenerator\Commands\Generate\GenerateSkeletonCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateEntityCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateJobCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateJobValidatorCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateRepositoryCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateRepositoryContractCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateRepositoryServiceProviderCommand::class,
    \LaravelSkeletonGenerator\Commands\Generate\GenerateTransformerCommand::class,
];
...
```