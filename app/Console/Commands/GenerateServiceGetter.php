<?php

namespace App\Console\Commands;

use App\Api\Service\Service;
use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;


class GenerateServiceGetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:service-getter {model} {getter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service getter';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function makeGetter($model, $getter) {
        $serviceFactoryFileName = realpath(dirname(__FILE__ ) . "/../../Api/Service/Service.php");
        $serviceFactoryCode = file_get_contents($serviceFactoryFileName);

        if (strpos($serviceFactoryCode, "public static function ${getter}()") === false) {
            $serviceFactoryCode = str_replace("/**{{{service-getter-anchor}}}**/",
                "static \$${getter}Service = null;
    /**
     * @return I${model}Service
     */
    public static function ${getter}() {
        if (self::\$${getter}Service) return self::\$${getter}Service;
        self::\$${getter}Service = app()->get(\"service.${getter}\");
        return self::\$${getter}Service;
    }
    /**{{{service-getter-anchor}}}**/",
                $serviceFactoryCode);
            $serviceFactoryCode = str_replace("/**{{{use-anchor}}}**/",
            "use App\Api\Service\Contract\I" . $model . "Service;
/**{{{use-anchor}}}**/", $serviceFactoryCode);
            file_put_contents($serviceFactoryFileName, $serviceFactoryCode);
        } else {
            $this->error("Service getter ${getter} exists");
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $model = $this->argument("model");
        $getter = $this->argument("getter");
        $this->makeGetter($model, $getter);
    }
}
