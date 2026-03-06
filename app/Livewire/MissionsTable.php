<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class MissionsTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    public int $perPage = 10;

    // TODO: implement sorting in UI and add logic here
    public string $sortField = 'created_at';
    public bool $sortAsc = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function openModal($missionData)
    {
        logger()->info('openModal called for mission', ['id' => $missionData['id'] ?? null]);
        // propagate event for modal component to listen
        $this->dispatch('openMissionModal', $missionData);
    }

    public function render()
    {
        // Fetch missions for the authenticated user via repository
        $items = collect();

        $email = Auth::user()?->email;
        if (!$email) {
            logger()->warning('MissionsTable.render called with no authenticated user');
        }
        if ($email) {
            // TODO: for users with many missions, this becomes inefficient
            // may want to consider pushing filters (status, search) to the repository query
            $repo = app(\App\Domain\Interfaces\IMissionRepository::class);
            $entities = $repo->getMissions($email);

            logger()->info('MissionsTable.render fetched ' . count($entities) . ' entities for user ' . Auth::id());

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
        } else {
            logger()->info('MissionsTable.render: user has no email, returning empty items');
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

        $page = (int) request()->get('page', 1);
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
