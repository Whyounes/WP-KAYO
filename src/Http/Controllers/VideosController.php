<?php

namespace App\Http\Controllers;

use App\Application;
use Illuminate\Http\Response;

class VideosController extends BaseController
{
    public function dashboard()
    {
        return "Dashboard";
    }

    public function index()
    {
        $twig = Application::get('twig');
        $responseContent = $twig->render('videos.twig.htm', ['name' => 'younes']);

        return $responseContent;
    }

    public function videosToJson()
    {
        return [
            'first video',
            'second video'
        ];
    }
}