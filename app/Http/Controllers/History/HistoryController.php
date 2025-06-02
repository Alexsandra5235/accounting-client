<?php

namespace App\Http\Controllers\History;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(): View
    {
        return view('history.history', ['history' => History::query()->orderBy('created_at', 'desc')->get()]);
    }
}
