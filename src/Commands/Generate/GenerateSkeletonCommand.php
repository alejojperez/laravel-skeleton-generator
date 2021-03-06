<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace AlejoJPerez\LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\Command;

class GenerateSkeletonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alejojperez-generate:skeleton';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a laravel structure with entities, repositories, jobs, etc...';
    
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $stubsPath;

    /**
     * Create a new command instance.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct();
        
        $this->config = $config;
        $this->stubsPath = __DIR__."/stubs";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ask = "Remember that this command should be called only one time when the project is created.\n" .
            " Are you sure you wish to continue? [y|N]";
        if (!$this->confirm($ask)) {
            return;
        }

        /**
         * @TODO Create the default transformer and job
         * @TODO Create a package for the command bus
         * @TODO Create a package for the binders
         */

        $this->createDataFolderStructure();
        $this->createAbstractDataClasses();
        $this->createUserEntity();
    }

    #region Helpers

    /**
     * @return void
     */
    protected function createAbstractDataClasses()
    {
        $this->createAbstractEntity();
        $this->createAbstractRepositoryContract();
        $this->createAbstractRepositoryImplementation();
    }

    /**
     * @return void
     */
    protected function createAbstractEntity()
    {
        if($msg = $this->validKeyConfigurationValue("app_path") !== true) {
            $this->error($msg);
            exit;
        }
        
        $this->warn("Creating \"abstract entity\"...");

        $filePath = $this->config["app_path"]."/Data/Entities/AbstractEntity.php";

        if($this->makeFile($filePath)) {
            $fileContents = file_get_contents($this->stubsPath."/abstract-entity.stub");
            file_put_contents($filePath, $fileContents);
        }

        $this->info("Created \"abstract entity\".\n");
    }

    /**
     * @return void
     */
    protected function createAbstractRepositoryContract()
    {
        if(($msg = $this->validKeyConfigurationValue("app_path")) !== true) {
            $this->error($msg);
            exit;
        }

        $this->warn("Creating \"abstract repository contract\"...");

        $filePath = $this->config["app_path"]."/Data/Repositories/Contracts/AbstractRepository.php";

        if($this->makeFile($filePath)) {
            $fileContents = file_get_contents($this->stubsPath."/abstract-repository-contract.stub");
            file_put_contents($filePath, $fileContents);
        }

        $this->info("Created \"abstract repository contract\".\n");
    }

    /**
     * @return void
     */
    protected function createAbstractRepositoryImplementation()
    {
        if(($msg = $this->validKeyConfigurationValue("app_path")) !== true) {
            $this->error($msg);
            exit;
        }

        $this->warn("Creating \"abstract repository implementation\"...");

        $filePath = $this->config["app_path"]."/Data/Repositories/Implementations/AbstractRepository.php";

        if($this->makeFile($filePath)) {
            $fileContents = file_get_contents($this->stubsPath."/abstract-repository-implementation.stub");
            file_put_contents($filePath, $fileContents);
        }

        $this->info("Created \"abstract repository implementation\".\n");
    }

    /**
     * @return void
     */
    protected function createDataFolderStructure()
    {
        if(($msg = $this->validKeyConfigurationValue("app_path")) !== true) {
            $this->error($msg);
            exit;
        }

        if(($msg = $this->validKeyConfigurationValue("folder_permission")) !== true) {
            $this->error($msg);
            exit;
        }

        $this->warn("Creating \"data\" folder structure...");

        $this->makeDirectory($this->config["app_path"]."/Data");
        $this->makeDirectory($this->config["app_path"]."/Data/Entities");
        $this->makeDirectory($this->config["app_path"]."/Data/Repositories");
        $this->makeDirectory($this->config["app_path"]."/Data/Repositories/Binders");
        $this->makeDirectory($this->config["app_path"]."/Data/Repositories/Contracts");
        $this->makeDirectory($this->config["app_path"]."/Data/Repositories/Implementations");
        $this->makeDirectory($this->config["app_path"]."/Data/Transformers");

        $this->info("Created \"data\" folder structure.\n");
    }

    /**
     * @return void
     */
    protected function createUserEntity()
    {
        if(($msg = $this->validKeyConfigurationValue("app_path")) !== true) {
            $this->error($msg);
            exit;
        }

        $this->warn("Creating \"user entity\"...");

        $filePath = $this->config["app_path"]."/Data/Entities/User.php";

        if($this->makeFile($filePath)) {
            $fileContents = file_get_contents($this->stubsPath."/user-entity.stub");
            file_put_contents($filePath, $fileContents);
        }
        app(\Illuminate\Contracts\Console\Kernel::class)->call('alejojperez-generate:repository-contract', ['name' => 'UsersRepository']);
        app(\Illuminate\Contracts\Console\Kernel::class)->call('alejojperez-generate:repository', ['name' => 'UsersRepository']);
        app(\Illuminate\Contracts\Console\Kernel::class)->call('alejojperez-generate:repository-service-provider', ['name' => 'UsersRepositoryServiceProvider']);

        $this->info("Created \"user entity\".\n");

        $filePath = $this->config["app_path"]."/User.php";

        if(is_file($filePath)) {

            $this->warn("Trying to remove laravel's default user model...");
            if (unlink($filePath))
                $this->info("Laravel's default user model deleted.\n");
            else
                $this->error("Unable to delete laravel's default user model.\n");
        }
    }

    /**
     * @param $path
     * @param bool $throwWarning
     * @return bool
     */
    protected function makeDirectory($path, $throwWarning = true)
    {
        if(!is_dir($path)) {
            mkdir($path, $this->config["folder_permission"]);

            return true;
        } else {
            if($throwWarning) {
                $displayPath = explode("/app/", $path)[1];
                $this->warn("Folder \"app" . DIRECTORY_SEPARATOR . "$displayPath\" already exists.");
            }

            return false;
        }
    }

    /**
     * @param $path
     * @param string $contents
     * @param bool $throwWarning
     * @return bool
     */
    protected function makeFile($path, $contents = "", $throwWarning = true)
    {
        if(!is_file($path)) {
            file_put_contents($path, $contents);

            return true;
        } else {
            if($throwWarning) {
                $displayPath = explode("/app/", $path)[1];
                $this->warn("File \"app" . DIRECTORY_SEPARATOR . "$displayPath\" already exists.");
            }

            return false;
        }
    }

    /**
     * @param $key
     * @return bool|string
     */
    protected function validKeyConfigurationValue($key)
    {
        if(!array_key_exists($key, $this->config) || empty($this->config[$key])) {
            return "\n\n Missing \"$key\" key on the configuration file.\n";
        }

        return true;
    }

    #endregion
}