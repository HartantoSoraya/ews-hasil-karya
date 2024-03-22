<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create API files: model, migration, controller, store request, update request, resource.';

    public function handle()
    {
        $this->info('Creating magic... 🪄');

        $this->createModel();
        $this->createController();
        $this->createRequests();
        $this->createResource();
        $this->modifyMigration();
        $this->modifyRepository();
        $this->createTest();
        $this->createFactory();

        $this->comment('Playground created successfully. Happy coding hugo! 🚀');
    }

    protected function createModel()
    {
        $name = $this->argument('name');
        $this->call('make:model', ['name' => $name, '-m' => true]);

        $modelPath = app_path("Models/{$name}.php");

        $modelContent = "<?php\n\n";
        $modelContent .= "namespace App\Models;\n\n";
        $modelContent .= "use Illuminate\Database\Eloquent\Factories\HasFactory;\n";
        $modelContent .= "use Illuminate\Database\Eloquent\Model;\n";
        $modelContent .= "use Illuminate\Database\Eloquent\SoftDeletes;\n";
        $modelContent .= "use App\Traits\UUID;\n\n";
        $modelContent .= "class {$name} extends Model\n";
        $modelContent .= "{\n";
        $modelContent .= "    use HasFactory, UUID, SoftDeletes;\n\n";
        $modelContent .= "    protected \$fillable = [\n";
        $modelContent .= "        // Add your columns here\n";
        $modelContent .= "    ];\n";
        $modelContent .= "}\n";

        file_put_contents($modelPath, $modelContent);
    }

    protected function createRequests()
    {
        $name = $this->argument('name');
        $this->call('make:request', ['name' => "Store{$name}Request"]);
        $this->call('make:request', ['name' => "Update{$name}Request"]);

        $storeRequestPath = app_path("Http/Requests/Store{$name}Request.php");
        $storeRequestContent = "<?php\n\n";
        $storeRequestContent .= "namespace App\Http\Requests;\n\n";
        $storeRequestContent .= "use Illuminate\Foundation\Http\FormRequest;\n\n";
        $storeRequestContent .= "class Store{$name}Request extends FormRequest\n";
        $storeRequestContent .= "{\n";
        $storeRequestContent .= "    /**\n";
        $storeRequestContent .= "     * Get the validation rules that apply to the request.\n";
        $storeRequestContent .= "     *\n";
        $storeRequestContent .= "     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>\n";
        $storeRequestContent .= "     */\n";
        $storeRequestContent .= "    public function rules()\n";
        $storeRequestContent .= "    {\n";
        $storeRequestContent .= "        return [\n";
        $storeRequestContent .= "            // Add your validation rules here\n";
        $storeRequestContent .= "        ];\n";
        $storeRequestContent .= "    }\n";
        $storeRequestContent .= "}\n";

        file_put_contents($storeRequestPath, $storeRequestContent);

        $updateRequestPath = app_path("Http/Requests/Update{$name}Request.php");
        $updateRequestContent = "<?php\n\n";
        $updateRequestContent .= "namespace App\Http\Requests;\n\n";
        $updateRequestContent .= "use Illuminate\Foundation\Http\FormRequest;\n\n";
        $updateRequestContent .= "class Update{$name}Request extends FormRequest\n";
        $updateRequestContent .= "{\n";
        $updateRequestContent .= "    /**\n";
        $updateRequestContent .= "     * Get the validation rules that apply to the request.\n";
        $updateRequestContent .= "     *\n";
        $updateRequestContent .= "     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>\n";
        $updateRequestContent .= "     */\n";
        $updateRequestContent .= "    public function rules()\n";
        $updateRequestContent .= "    {\n";
        $updateRequestContent .= "        return [\n";
        $updateRequestContent .= "            // Add your validation rules here\n";
        $updateRequestContent .= "        ];\n";
        $updateRequestContent .= "    }\n";
        $updateRequestContent .= "}\n";

        file_put_contents($updateRequestPath, $updateRequestContent);
    }

    protected function createController()
    {
        $name = $this->argument('name');
        $this->call('make:controller', ['name' => "Api/{$name}Controller", '--api' => true]);

        $controllerPath = app_path("Http/Controllers/Api/{$name}Controller.php");

        $controllerContent = "<?php\n\n";
        $controllerContent .= "namespace App\Http\Controllers\Api;\n\n";
        $controllerContent .= "use App\Http\Controllers\Controller;\n";
        $controllerContent .= "use App\Http\Requests\\Store{$name}Request;\n";
        $controllerContent .= "use App\Http\Requests\\Update{$name}Request;\n";
        $controllerContent .= "use App\Http\Resources\\{$name}Resource;\n";
        $controllerContent .= "use App\Interfaces\\{$name}RepositoryInterface;\n";
        $controllerContent .= "use Illuminate\Http\Request;\n";
        $controllerContent .= "use App\Helpers\ResponseHelper;\n\n";
        $controllerContent .= "class {$name}Controller extends Controller\n";
        $controllerContent .= "{\n";
        $controllerContent .= "    protected \${$name}Repository;\n\n";
        $controllerContent .= "    public function __construct({$name}RepositoryInterface \${$name}Repository)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        \$this->{$name}Repository = \${$name}Repository;\n";
        $controllerContent .= "    }\n\n";
        $controllerContent .= "    public function index(Request \$request)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        //add your code here\n";
        $controllerContent .= "    }\n\n";
        $controllerContent .= "    public function store(Store{$name}Request \$request)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        //add your code here\n";
        $controllerContent .= "    }\n\n";
        $controllerContent .= "    public function show(\$id)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        //add your code here\n";
        $controllerContent .= "    }\n\n";
        $controllerContent .= "    public function update(Update{$name}Request \$request, \$id)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        //add your code here\n";
        $controllerContent .= "    }\n\n";
        $controllerContent .= "    public function destroy(\$id)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        //add your code here\n";
        $controllerContent .= "    }\n";
        $controllerContent .= "}\n";

        file_put_contents($controllerPath, $controllerContent);
    }

    protected function createResource()
    {
        $name = $this->argument('name');
        $this->call('make:resource', ['name' => "{$name}Resource"]);

        $resourcePath = app_path("Http/Resources/{$name}Resource.php");
        $resourceContent = "<?php\n\n";
        $resourceContent .= "namespace App\Http\Resources;\n\n";
        $resourceContent .= "use Illuminate\Http\Resources\Json\JsonResource;\n\n";
        $resourceContent .= "class {$name}Resource extends JsonResource\n";
        $resourceContent .= "{\n";
        $resourceContent .= "    /**\n";
        $resourceContent .= "     * Transform the resource into an array.\n";
        $resourceContent .= "     *\n";
        $resourceContent .= "     * @param  \Illuminate\Http\Request  \$request\n";
        $resourceContent .= "     * @return array<string, mixed>\n";
        $resourceContent .= "     */\n";
        $resourceContent .= "    public function toArray(\$request)\n";
        $resourceContent .= "    {\n";
        $resourceContent .= "        return [\n";
        $resourceContent .= "            // Add your resource here\n";
        $resourceContent .= "        ];\n";
        $resourceContent .= "    }\n";
        $resourceContent .= "}\n";

        file_put_contents($resourcePath, $resourceContent);
    }

    protected function modifyMigration()
    {
        $name = $this->argument('name');
        $name = Str::snake($name);
        $name = Str::plural($name);
        $migration = database_path('migrations/'.date('Y_m_d_His').'_create_'.$name.'_table.php');

        $migrationContent = "<?php\n\n";
        $migrationContent .= "use Illuminate\Database\Migrations\Migration;\n";
        $migrationContent .= "use Illuminate\Database\Schema\Blueprint;\n";
        $migrationContent .= "use Illuminate\Support\Facades\Schema;\n\n";
        $migrationContent .= "return new class extends Migration\n";
        $migrationContent .= "{\n";
        $migrationContent .= "    /**\n";
        $migrationContent .= "     * Run the migrations.\n";
        $migrationContent .= "     */\n";
        $migrationContent .= "    public function up()\n";
        $migrationContent .= "    {\n";
        $migrationContent .= "        Schema::create('{$name}', function (Blueprint \$table) {\n";
        $migrationContent .= "            \$table->uuid('id')->primary();\n";
        $migrationContent .= "            // Add your columns here\n";
        $migrationContent .= "            \$table->softDeletes();\n";
        $migrationContent .= "            \$table->timestamps();\n";
        $migrationContent .= "        });\n";
        $migrationContent .= "    }\n\n";
        $migrationContent .= "    /**\n";
        $migrationContent .= "     * Reverse the migrations.\n";
        $migrationContent .= "     */\n";
        $migrationContent .= "    public function down()\n";
        $migrationContent .= "    {\n";
        $migrationContent .= "        Schema::dropIfExists('{$name}');\n";
        $migrationContent .= "    }\n";
        $migrationContent .= "};\n";

        file_put_contents($migration, $migrationContent);
    }

    protected function modifyRepository()
    {
        $name = $this->argument('name');
        $interfacePath = app_path("Interfaces/{$name}RepositoryInterface.php");
        $repositoryPath = app_path("Repositories/{$name}Repository.php");

        $interfaceContent = "<?php\n\nnamespace App\Interfaces;\n\ninterface {$name}RepositoryInterface\n{\n    //\n}\n";
        $repositoryContent = "<?php\n\nnamespace App\Repositories;\n\nuse App\Interfaces\\{$name}RepositoryInterface;\n\nclass {$name}Repository implements {$name}RepositoryInterface\n{\n    //\n}\n";

        file_put_contents($interfacePath, $interfaceContent);
        file_put_contents($repositoryPath, $repositoryContent);

        $repositoryServiceProvider = app_path('Providers/RepositoryServiceProvider.php');
        $repositoryServiceProviderContent = file_get_contents($repositoryServiceProvider);

        $replacement = <<<PHP
\$this->app->bind(\App\Interfaces\\{$name}RepositoryInterface::class, \App\Repositories\\{$name}Repository::class);
    }\n
PHP;

        $repositoryServiceProviderContent = preg_replace('/public function register\(\)\s*{([^}]*)}/s', "public function register() {\n$1$replacement", $repositoryServiceProviderContent, 1);

        file_put_contents($repositoryServiceProvider, $repositoryServiceProviderContent);
    }

    protected function createTest()
    {
        $name = $this->argument('name').'API';
        $test = base_path("tests/Feature/{$name}Test.php");
        $testContent =
            <<<'EOT'
            <?php

            namespace Tests\Feature;

            use Illuminate\Support\Facades\Storage;
            use Tests\TestCase;

            class @name extends TestCase
            {   
                public function setUp(): void
                {
                    parent::setUp();
                    
                    Storage::fake('public');
                }

                //
            }
            EOT;
        $testContent = str_replace('@name', $name.'Test', $testContent);

        file_put_contents($test, $testContent);
    }

    protected function createFactory()
    {
        $name = $this->argument('name');
        $factory = database_path("factories/{$name}Factory.php");

        $factoryContent = "<?php\n\n";
        $factoryContent .= "namespace Database\Factories;\n\n";
        $factoryContent .= "use Illuminate\Database\Eloquent\Factories\Factory;\n";
        $factoryContent .= "use Illuminate\Support\Str;\n\n";
        $factoryContent .= "class {$name}Factory extends Factory\n";
        $factoryContent .= "{\n";
        $factoryContent .= "    /**\n";
        $factoryContent .= "     * Define the model's default state.\n";
        $factoryContent .= "     *\n";
        $factoryContent .= "     * @return array<string, mixed>\n";
        $factoryContent .= "     */\n";
        $factoryContent .= "    public function definition(): array\n";
        $factoryContent .= "    {\n";
        $factoryContent .= "        return [\n";
        $factoryContent .= "            // Define your default state here\n";
        $factoryContent .= "        ];\n";
        $factoryContent .= "    }\n";
        $factoryContent .= "}\n";

        file_put_contents($factory, $factoryContent);
    }
}
