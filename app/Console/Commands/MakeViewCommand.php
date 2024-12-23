<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {view}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Blade template.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $view = $this->argument('view');
        $path = $this->viewPath($view);

        // Tạo thư mục nếu chưa tồn tại
        $this->createDir($path);

        // Kiểm tra nếu file đã tồn tại
        if (File::exists($path)) {
            $this->error("File {$path} already exists!");
            return;
        }

        // Nội dung mặc định cho file view
        $defaultContent = "<!-- Blade view: {$view} -->";

        // Tạo file và ghi nội dung mặc định
        File::put($path, $defaultContent);
        $this->info("File {$path} created successfully.");
    }

    /**
     * Get the view's full path.
     *
     * @param string $view
     *
     * @return string
     */
    public function viewPath($view)
    {
        $view = str_replace('.', '/', $view) . '.blade.php';
        return resource_path("views/{$view}");
    }

    /**
     * Create a view directory if it does not exist.
     *
     * @param string $path
     */
    public function createDir($path)
    {
        $dir = dirname($path);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true);
        }
    }
}
