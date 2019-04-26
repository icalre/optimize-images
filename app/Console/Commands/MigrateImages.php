<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-images';

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


       /* $profiles = Profile::where('Photo', 'NOT LIKE', '%storyrocket-aws3%')->whereNotNull('Photo')->get();


        $base_url = 'https://www.storyrocket.com/public/images/profile/';


        $i = 1;

        foreach ($profiles as $profile) {
            if (!empty($profile->Photo)) {
                $photo = $base_url . $profile->UserID . '/' . $profile->Photo;

                $this->info('processing - '.$i);

                $info = pathinfo($photo);
                $contents = file_get_contents($photo);
                $name = substr(md5($profile->UserID . uniqid(rand(), true)), 0, 8) . '.' . $info['extension'];
                Storage::disk('public')->put('temp/' . $name, $contents);
                $this->info(storage_path('public/temp/' . $name));

                $profile->Photo = storage_path('public/temp/' . $name);
                $profile->save();

                $i++;

            }

        }

        $profiles = Profile::where('Photo', 'LIKE', '%storyrocket-aws3%')->get();*/
        $profiles = Profile::where('Photo', 'LIKE', '%full5c11585f00b451544640607sr_jpg%')->get();
        $i = 1;

        foreach ($profiles as $profile) {
            if (!empty($profile->Photo)) {
                $photo = $profile->Photo;

                $ext = substr($photo, -3,3);

                $this->info('processing-aws-'.$i);

                $contents = file_get_contents($photo);
                $name = substr(md5($profile->UserID . uniqid(rand(), true)), 0, 8) . '.' . $ext;
                Storage::disk('public')->put('temp/' . $name, $contents);
                $this->info(storage_path('public/temp/' . $name));

                $profile->Photo = storage_path('public/temp/' . $name);
                $profile->save();

                $i++;
            }

        }


        $this->info('Finish');
    }

}
