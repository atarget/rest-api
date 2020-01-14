<?php

namespace App\Console\Commands;

use App\Lang;
use Illuminate\Console\Command;
use Nette\PhpGenerator\PhpNamespace;

class GenerateService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:service {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function makeInterface($model) {
        $namespace = new PhpNamespace("App\Api\Service\Contract");
        $interface = $namespace->addInterface("I" . $model . "Service");
        $interface->addExtend("\App\Api\Service\Contract\IService");
        $dirname = realpath(dirname(__FILE__ ) . "/../../Api/Service/Contract");
        if (!file_exists($dirname . "/I" . $model . "Service.php")) {
            file_put_contents($dirname . "/I" . $model . "Service.php", "<?php\n\n" . $namespace);
        } else {
            $this->error("File: \"" . $dirname . "/I" . $model . "Service.php\"" . " is exists");
        }
    }

    private function makeClass($model) {
        $namespace = new PhpNamespace("App\Api\Service\Implementation");
        $namespace->addUse("App\Api\Service\Contract\I".$model."Service");
        $namespace->addUse("App\\".$model);
        $class = $namespace->addClass($model . "Service");
        $class->addExtend("App\Api\Service\Implementation\AbstractService");
        $class->addImplement("App\Api\Service\Contract\I".$model."Service");
        $save = $class->addMethod("save");
        $save->addParameter("entity");
        $save->addParameter("query")->setDefaultValue(null);
        $save->addBody('return parent::save($entity, '.$model.'::query());');

        $get = $class->addMethod("get");
        $get->addParameter("entity");
        $get->addParameter("query")->setDefaultValue(null);
        $get->addBody('return parent::get($entity, '.$model.'::query());');

        $find = $class->addMethod("find");
        $find->addParameter("findQueryBuilder");
        $find->addParameter("query")->setDefaultValue(null);
        $find->addBody('return parent::find($findQueryBuilder, '.$model.'::query());');

        $delete = $class->addMethod("delete");
        $delete->addParameter("entity");
        $delete->addParameter("query")->setDefaultValue(null);
        $delete->addBody('return parent::delete($entity, '.$model.'::query());');

        $makeEntity = $class->addMethod("makeEntity");
        $makeEntity->addParameter("data");
        $makeEntity->addBody('$entity = $this->makeEntityHelper($data, new '.$model.'());
        return $entity;');

        $dirname = realpath(dirname(__FILE__ ) . "/../../Api/Service/Implementation");
        if (!file_exists($dirname . "/" . $model . "Service.php")) {
            file_put_contents($dirname . "/" . $model . "Service.php", "<?php\n\n" . $namespace);
        } else {
            $this->error("File: \"" . $dirname . "/" . $model . "Service.php\"" . " is exists");
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $model = $this->argument("model");
        $this->makeInterface($model);
        $this->makeClass($model);
    }
}
