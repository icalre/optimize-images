<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function upAws()
    {
        $profiles = Profile::where('Photo', 'NOT LIKE', '%storyrocket-aws3%')->where('Photo', '!=', '')->get();

        $profiles = Profile::where('Photo', 'LIKE', '%storyrocket-aws3%')->get();

        dd($profiles->toArray());

        $base_url = 'https://www.storyrocket.com/public/images/profile/';

        foreach ($profiles as $profile) {
            $photo = $base_url . $profile->UserID . '/' . $profile->Photo;

            echo $photo . '<br>';
        }

        dd('-----');

        $url = "https://storyrocket-aws3.s3.amazonaws.com/thumb_5ac265358a752.png";
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);


        dd($name);
        $img = public_path('temp/xxx.png');

        $image = Storage::disk('s3')->put('prue-xx.png', File::get($img), 'public');

        $url = Storage::disk('s3')->url('prue-xx.png');

        dd($url);

    }


    public function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if ($result !== FALSE) {
            return true;
        } else {
            return false;
        }
    }


}
