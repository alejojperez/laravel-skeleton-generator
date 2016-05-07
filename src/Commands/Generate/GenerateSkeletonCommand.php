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
        $currentDirectory = __DIR__;

        parent::__construct();
        $this->config = count($config) > 0 ? $config : include $currentDirectory."/../config.php";
        $this->stubsPath = $currentDirectory."/stubs";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
        }
        
        $this->warn('Creating "abstract entity"...');

        $abstractEntityPath = $this->config["app_path"]."/Data/Entities/AbstractEntity.php";
        $abstractEntityContents = file_get_contents($this->stubsPath."/abstract-entity.stub");

        file_put_contents($abstractEntityPath, $abstractEntityContents);

        $this->info('Created "abstract entity".');
    }

    /**
     * @return void
     */
    protected function createDataFolderStructure()
    {
        if($msg = $this->validKeyConfigurationValue("app_path") !== true) {
            $this->error($msg);
        }

        if($msg = $this->validKeyConfigurationValue("folder_permission") !== true) {
            $this->error($msg);
        }

        $this->warn('Creating "data" folder structure...');

        mkdir($this->config["app_path"]."/Data", $this->config["folder_permission"]);
        mkdir($this->config["app_path"]."/Data/Entities", $this->config["folder_permission"]);
        mkdir($this->config["app_path"]."/Data/Repositories", $this->config["folder_permission"]);
        mkdir($this->config["app_path"]."/Data/Repositories/Binders", $this->config["folder_permission"]);
        mkdir($this->config["app_path"]."/Data/Repositories/Contracts", $this->config["folder_permission"]);
        mkdir($this->config["app_path"]."/Data/Repositories/Implementations", $this->config["folder_permission"]);
        mkdir($this->config["app_path"]."/Data/Transformers", $this->config["folder_permission"]);

        $this->info('Created "data" folder structure.');
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