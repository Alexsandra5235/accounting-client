<?php

namespace App\Http\Controllers\History;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = History::query()
            ->with(['action', 'user'])
            ->orderBy('created_at', 'desc');

        // Фильтр по типу действия
        if ($request->filled('action') && $request->action !== 'all') {
            $query->whereHas('action', function ($q) use ($request) {
                $q->where('value', $request->action);
            });
        }

        // Фильтр по дате
        if ($request->filled('date') && $request->date !== 'all') {
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereDate('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('action', function ($q2) use ($search) {
                        $q2->where('value', 'like', "%{$search}%");
                    });
            });
        }

        $history = $query->paginate(10)->withQueryString();

        return view('history.history', compact('history'));
    }
}
