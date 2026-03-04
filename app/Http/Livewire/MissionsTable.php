<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

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

    public function render()
    {
        // Fetch missions for the authenticated user via repository
        $items = collect();

        $email = Auth::user()?->email;
        if ($email) {
            $repo = app(\App\Domain\Interfaces\IMissionRepository::class);
            $entities = $repo->getMissions($email);

            $items = collect(array_map(function ($m) {
                return [
                    'id' => $m->id,
                    'missionName' => $m->missionName,
                    'status' => $m->status,
                    'startingLocation' => $m->startingLocation,
                    'destination' => $m->destination,
                    'email' => $m->email,
                    'dateCreated' => (string) $m->dateCreated,
                    'dateDelivered' => $m->dateDelivered ? (string) $m->dateDelivered : '',
                ];
            }, $entities));
        }

        // apply filters on collection
        $filtered = $items->filter(function ($m) {
            if ($this->status && $m['status'] !== $this->status) return false;

            if ($this->search === '') return true;

            $q = strtolower($this->search);

            return str_contains(strtolower((string) ($m['id'] ?? '')), $q)
                || str_contains(strtolower($m['missionName'] ?? ''), $q)
                || str_contains(strtolower($m['startingLocation'] ?? ''), $q)
                || str_contains(strtolower($m['destination'] ?? ''), $q)
                || str_contains(strtolower($m['email'] ?? ''), $q)
                || str_contains(strtolower((string) ($m['dateCreated'] ?? '')), $q)
                || str_contains(strtolower((string) ($m['dateDelivered'] ?? '')), $q);
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
