<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh-database';

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
        $this->call('migrate:fresh');
        $this->call('db:seed');

        // Bersihkan folder storage di direktori public dan storage/app
        File::cleanDirectory(public_path('storage'));
        File::cleanDirectory(storage_path('app'));

        $this->info('Penyimpanan sudah dibersihkan🍃.');
        $this->info('Oke, Aman. Database sudah di-segarkan🍃.');
    }
}
