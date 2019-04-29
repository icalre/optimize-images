<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class OptimizeImagesCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize-images-check';

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

    public function processImages()
    {
        ini_set('memory_limit', '-1');
        ini_set('default_charset', 'utf-8');

        $this->info('Running');

        $files = \File::allFiles(env('DIR_IMAGES'));
        $names_new = [];

        $names = \File::get(storage_path('app/public/temp/names.txt'));
        $names = explode('@', $names);

        $i = 1;
        foreach ($files as $file) {

            $this->info('Process-' . $i);

            $file_name = $file->getFilename();

            $names_new[] = $file_name;
            Storage::disk('public')->put('temp/' . 'names.txt', implode("@", $names_new));

            $lastmodified = \File::lastModified(env('DIR_IMAGES') . $file_name);
            $lastmodified = \DateTime::createFromFormat("U", $lastmodified);
            $lastmodified = $lastmodified->format('Y-m-d');

            if ($lastmodified != '2019-04-26') {


            }


            if (!in_array($file_name, $names)) {
                $file_name_save = str_replace(
                    array('a?', 'e?', 'i?', 'o?', 'u?', '%'),
                    array('a', 'e', 'i', 'o', 'u', ''),
                    utf8_decode($file_name)
                );


                rename(env('DIR_IMAGES') . $file_name, env('DIR_IMAGES') . $file_name_save);

                echo $file_name_save . "\n";


                Image::load(env('DIR_IMAGES') . $file_name_save)
                    ->optimize()
                    ->save();

                rename(env('DIR_IMAGES') . $file_name_save, env('DIR_IMAGES') . $file_name);

            }


            $i++;
        }

        $this->info('Finish');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $this->processImages();
        }catch (\Exception $exception)
        {
            $this->processImages();
        }
    }
}
