<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class MissionsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;

    protected $updatesQueryString = ['search', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    protected function defaultTestMissions()
    {
        return collect([
            [
                'id' => 1,
                'missionName' => 'Test 1234',
                'status' => 'delivered',
                'startingLocation' => 'Point A',
                'destination' => "Point B",
                'email' => 'alpha@example.com',
                'dateCreated' => '2026-01-10',
                'dateDelivered' => '2026-01-15',
            ],
            [
                'id' => 2,
                'missionName' => 'Test 4321',
                'status' => 'inTransit',
                'startingLocation' => 'Point C',
                'destination' => 'Point D',
                'email' => 'beta@example.com',
                'dateCreated' => '2026-01-12',
                'dateDelivered' => null,
            ],
        ]);
    }

    public function render()
    {
        // TODO: update to fetch from database
        $items = $this->defaultTestMissions();

        // apply filters on collection
        $filtered = $items->filter(function ($m) {
            if ($this->status && $m['status'] !== $this->status) return false;

            if ($this->search === '') return true;

            $q = strtolower($this->search);
            return str_contains(strtolower($m['missionName']), $q)
                || str_contains(strtolower($m['startingLocation']), $q)
                || str_contains(strtolower($m['destination']), $q)
                || str_contains(strtolower($m['email'] ?? ''), $q);
        })->values();

        $page = request()->get('page', 1);
        $perPage = $this->perPage;
        $currentItems = $filtered->forPage($page, $perPage);

        $missions = new LengthAwarePaginator(
            $currentItems->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.missions-table', ['missions' => $missions]);
    }
}
