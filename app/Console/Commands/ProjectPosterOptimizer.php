<?php

namespace App\Console\Commands;

use App\Project;
use Illuminate\Console\Command;
use Spatie\Image\Image;

class ProjectPosterOptimizer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project-optimize';

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

        //$profiles = Project::all();
        $profiles = Project::where('Poster', 'Like', '%97c533aa%')->get();

        $i = 1;

        foreach ($profiles as $profile) {
            if (!empty($profile->Poster)) {

                $this->info('processing - ' . $i);
                $name = substr($profile->Poster, strrpos($profile->Poster, '/') + 1);

                try {
                    Image::load(storage_path('app/public/temp/' . $name))
                        ->width(1500)
                        ->optimize()
                        ->save();
                } catch (\Exception $exception) {

                }

                $i++;
            }

        }

        $this->info('Finish');
    }
}
