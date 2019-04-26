<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;
use Spatie\Image\Image;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        ini_set('memory_limit', '-1');

        $this->info('Running');

        //$profiles = Profile::all();
        $profiles = Profile::where('Photo', 'LIKE', '%4197fd49.%')->get();

        $i = 1;

        foreach ($profiles as $profile) {
            if (!empty($profile->Photo)) {
                $this->info('processing - ' . $i);

                $name = substr($profile->Photo, strrpos($profile->Photo, '/') + 1);

                try {
                    Image::load(storage_path('app/public/temp/' . $name))
                        ->width(340)
                        ->optimize()
                        ->save();
                } catch (\Exception $exception) {
                    $profile->Photo = '';
                    $profile->save();
                }

                $i++;
            }

        }

        $this->info('Finish');
    }
}
