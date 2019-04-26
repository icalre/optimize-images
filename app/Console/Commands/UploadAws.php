<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadAws extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload-aws';

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

        //$profiles = Profile::all();
        $profiles = Profile::where('Photo', 'LIKE', '%4197fd49.%')->get();

        $i = 1;

        foreach ($profiles as $profile)
        {

            if(!empty($profile->Photo))
            {
                $name = substr($profile->Photo, strrpos($profile->Photo, '/') + 1);

                $img = storage_path('app/public/temp/' . $name);

                Storage::disk('s3')->put($name, File::get($img), 'public');

                $url = Storage::disk('s3')->url($name);

                $profile->Photo = $url;
                $profile->save();

                $this->info('processing - '.$i);

                $i++;
            }
        }

        $this->info('Finish');
    }
}
