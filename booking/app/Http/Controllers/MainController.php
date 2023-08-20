<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    // Главная страница
    public function index()
    {
        // Отображени главной страницы
        return view('index');
    }
}
