<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $commits = GitHub::api('repo')->commits()->all(config('github.repo.user'), config('github.repo.repo'), array('sha' => config('github.repo.branch')));

        $commits = array_map(function($item) {
            $item['commit']['author']['date'] = date('Y-m-d H:i:s', strtotime($item['commit']['author']['date']));

            return $item;
        }, $commits);

        return view('backend.home.index', [
            'commits' => $commits
        ]);
    }
}
