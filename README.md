# laravel-skeleton-generator
A package containing a set of commands to generate any kind of component

###Include the service provider

```php
// config/app.php

return [

    "providers" => [
        ...
        AlejoJPerez\LaravelSkeletonGenerator\LaravelSkeletonGeneratorServiceProvider::class,
        ...
    ]

];
```

###Include all the commands to the console

```php
// app/Console/Kernel.php

...
protected $commands = [
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateSkeletonCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateEntityCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateJobCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateJobValidatorCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateRepositoryCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateRepositoryContractCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateRepositoryServiceProviderCommand::class,
    \AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate\GenerateTransformerCommand::class,
];
...
```