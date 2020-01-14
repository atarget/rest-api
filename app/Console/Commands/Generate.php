<?php

namespace App\Console\Commands;

use App\Lang;
use Illuminate\Console\Command;
use Nette\PhpGenerator\PhpNamespace;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate {model} {serviceGetter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model, migration, service, controller and routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $model = $this->argument("model");
        $serviceGetter = $this->argument("serviceGetter");
        $this->call("make:model", ["-m", "name" => $model]);
        $this->call("generate:service", ["model" => $model]);
        $this->call("generate:controller", ["model" => $model, "serviceGetter" => $serviceGetter]);
        $this->call("generate:service-getter", ["model" => $model, "getter" => $serviceGetter]);
        $this->call("generate:routes", ["model" => $model, "base" => $serviceGetter]);

        $this->info("All done! Do not forgot edit model => extends Entity and make validators");
    }
}
