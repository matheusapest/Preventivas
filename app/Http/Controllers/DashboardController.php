<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o painel principal do sistema.
     */
    public function __invoke(): View
    {
        return view('dashboard.index');
    }
}
