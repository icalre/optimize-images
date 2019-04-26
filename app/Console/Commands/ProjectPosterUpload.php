<?php

namespace App\Console\Commands;

use App\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProjectPosterUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project-upload';

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

        $profiles = Project::where('Poster', 'Like', '%97c533aa%')->get();

        $i = 1;

        foreach ($profiles as $profile)
        {

            if(!empty($profile->Poster))
            {
                $name = substr($profile->Poster, strrpos($profile->Poster, '/') + 1);

                $img = storage_path('app/public/temp/' . $name);

                Storage::disk('s3')->put($name, File::get($img), 'public');

                $url = Storage::disk('s3')->url($name);

                $profile->Poster = $url;
                $profile->save();

                $this->info('processing - '.$i);

                $i++;
            }
        }

        $this->info('Finish');
    }
}
