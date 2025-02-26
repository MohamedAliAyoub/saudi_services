<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-storage-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        if (file_exists($link)) {
            $this->error("The [$link] link already exists.");
            return;
        }

        if (symlink($target, $link)) {
            $this->info("The [$link] link has been connected to [$target].");
        } else {
            $this->error("Failed to create the symbolic link.");
        }
    }
}
