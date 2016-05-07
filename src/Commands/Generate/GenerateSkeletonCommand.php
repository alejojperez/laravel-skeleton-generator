<?php
/**
 * Created by Alejandro Perez on 5/6/16
 * github page: https://github.com/alejojperez
 */

namespace LaravelSkeletonGenerator\Commands\Generate;

use Illuminate\Console\Command;

class GenerateSkeletonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:skeleton';

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

        $this->createDataFolderStructure();
        $this->createAbstractDataClasses();
    }

    #region Helpers

    /**
     * @return void
     */
    protected function createAbstractDataClasses()
    {
        $this->createAbstractEntity();
    }

    /**
     * @return void
     */
    protected function createAbstractEntity()
    {
        if($msg = $this->validKeyConfigurationValue("app_path") !== true) {
            $this->error($msg);
            return;
        }
        
        $this->warn('Creating "abstract entity"...');

        $abstractEntityPath = $this->config["app_path"]."/Data/Entities/AbstractEntity.php";

        if($this->makeFile($abstractEntityPath)) {
            $abstractEntityContents = file_get_contents($this->stubsPath."/abstract-entity.stub");
            file_put_contents($abstractEntityPath, $abstractEntityContents);
        }

        $this->info("Created \"abstract entity\".\n");
    }

    protected function createAbstractRepositoryContract()
    {
        if(($msg = $this->validKeyConfigurationValue("app_path")) !== true) {
            $this->error($msg);
            return;
        }

        $this->warn('Creating "abstract entity"...');

        $abstractEntityPath = $this->config["app_path"]."/Data/Entities/AbstractEntity.php";

        if($this->makeFile($abstractEntityPath)) {
            $abstractEntityContents = file_get_contents($this->stubsPath."/abstract-entity.stub");
            file_put_contents($abstractEntityPath, $abstractEntityContents);
        }

        $this->info("Created \"abstract entity\".\n");
    }

    /**
     * @return void
     */
    protected function createDataFolderStructure()
    {
        if(($msg = $this->validKeyConfigurationValue("app_path")) !== true) {
            $this->error($msg);
            return;
        }

        if(($msg = $this->validKeyConfigurationValue("folder_permission")) !== true) {
            $this->error($msg);
            return;
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
            return "Missing \"$key\" key on the configuration file.";
        }

        return true;
    }

    #endregion
}