<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.dashboard', [
            'icon' => 'activity',
            'title' => 'Dashboard',
            'subtitle' => 'Ikhtisar dashboard dan ringkasan konten.',
            'active' => 'dashboard',
        ]);
    }
}
