<?php

namespace App\Console\Commands;

use App\Api\Service\Service;
use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;


class GenerateRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:routes {model} {base}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function makeRoutes($model, $base) {
        $routesFileName = realpath(dirname(__FILE__ ) . "/../../../routes/rest_api_v1.php");
        $routesCode = file_get_contents($routesFileName);
        $line = "\App\Http\Controllers\\rest\\v1\\${model}Controller::routes(\"/${base}\");";
        if (strpos($routesCode, $line) === false) {
            $routesCode = str_replace("/**{{{routes-anchor}}}**/",
                "$line\n    /**{{{routes-anchor}}}**/",
                $routesCode);
            file_put_contents($routesFileName, $routesCode);
        } else {
            $this->error("Route for $base exists");
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $model = $this->argument("model");
        $base = $this->argument("base");
        $this->makeRoutes($model, $base);
    }
}
