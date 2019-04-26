<?php

namespace App\Console\Commands;

use App\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProjectPoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project-poster';

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
        $this->info('Running');


        /*$profiles = Project::where('Poster', 'NOT LIKE', '%storyrocket-aws3%')->get();


        $base_url = 'https://www.storyrocket.com/public/projects/';


        $i = 1;

        foreach ($profiles as $profile) {
            if (!empty($profile->Poster)) {
                $photo = $base_url . $profile->ProjectID . '/poster/poster_new' . $profile->Poster;

                $this->info('processing - '.$i);

                $info = pathinfo($photo);
                $contents = file_get_contents($photo);
                $name = substr(md5($profile->ProjectID . uniqid(rand(), true)), 0, 8) . '.' . $info['extension'];
                Storage::disk('public')->put('temp/' . $name, $contents);
                $this->info(storage_path('public/temp/' . $name));

                $profile->Poster = storage_path('public/temp/' . $name);
                $profile->save();

                $i++;

            }

        }*/

        //$profiles = Project::where('Poster', 'LIKE', '%storyrocket-aws3%')->get();

        $profiles = Project::where('Poster', 'LIKE', '%thumb_5c097809bbb63%')->get();

        $i = 1;

        foreach ($profiles as $profile) {
            if (!empty($profile->Poster)) {
                $photo = $profile->Poster;

                $ext = substr($photo, -3,3);

                $this->info('processing-aws-'.$i);

                $contents = file_get_contents($photo);
                $name = substr(md5($profile->ProjectID . uniqid(rand(), true)), 0, 8) . '.' . $ext;
                Storage::disk('public')->put('temp/' . $name, $contents);
                $this->info(storage_path('public/temp/' . $name));

                $profile->Poster = storage_path('public/temp/' . $name);
                $profile->save();

                $i++;
            }

        }


        $this->info('Finish');
    }
}
