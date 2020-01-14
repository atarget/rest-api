<?php

namespace App\Console\Commands;

use App\Lang;
use Illuminate\Console\Command;
use Nette\PhpGenerator\PhpNamespace;

class GenerateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controller {model} {serviceGetter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function makeClass($model, $serviceGetter) {
        $namespace = new PhpNamespace("App\Http\Controllers\\rest\\v1");
        $namespace->addUse("App\Api\Service\Service");
        $namespace->addUse("App\Http\Controllers\\rest\BaseRestController");
        $namespace->addUse("Illuminate\Http\Request");

        $class = $namespace->addClass($model . "Controller");
        $class->addExtend("App\Http\Controllers\\rest\\BaseRestController");

        $routes = $class->addMethod("routes")->setStatic();
        $routes->addParameter("base")->setType("string");
        $routes->addParameter("controller")->setType("string")->setDefaultValue(null);
        $routes->setBody('BaseRestController::routes($base, get_class(new self()));');

        $save = $class->addMethod("save");
        $save->addParameter("request")->setType("Illuminate\Http\Request");
        $save->setBody('return Service::'.$serviceGetter.'()->save($request->all());');

        $get = $class->addMethod("get");
        $get->addParameter("id")->setType("string");
        $get->setBody('return Service::'.$serviceGetter.'()->get($id);');

        $find = $class->addMethod("find");
        $find->addParameter("request")->setType("Illuminate\Http\Request");
        $find->setBody('return Service::'.$serviceGetter.'()->find(Service::MakeFindQueryBuilder($req));');

        $find = $class->addMethod("delete");
        $find->addParameter("id")->setType("string");
        $find->setBody('return Service::'.$serviceGetter.'()->delete($id);');

        $dirname = realpath(dirname(__FILE__ ) . "/../../Http/Controllers/rest/v1");

        if (!file_exists($dirname . "/" . $model . "Controller.php")) {
            file_put_contents($dirname . "/" . $model . "Controller.php", "<?php\n\n" . $namespace);
        } else {
            $this->error("File: \"" . $dirname . "/" . $model . "Controller.php\"" . " is exists");
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $model = $this->argument("model");
        $serviceGetter = $this->argument("serviceGetter");
        $this->makeClass($model, $serviceGetter);
    }
}
