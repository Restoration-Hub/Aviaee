<div class="missions-table-component">
    @php $missions = $missions ?? collect(); @endphp

    <!-- Mission Details Modal Component -->
    <livewire:mission-details-modal />

    <div class="missions-top-bar">
        <div class="missions-search-form">
            <input id="missions-search" type="search" wire:model.live.debounce.300ms="search" placeholder="Search..." class="search-input" aria-label="Search missions" />
            <img src="{{ Vite::asset('resources/assets/search-icon.svg') }}" alt="Search Icon" class="search-icon">

            <select id="missions-status" wire:model.live="status" class="status-filter">
                <option value="">All statuses</option>
                <option value="ordered">Ordered</option>
                <option value="packed">Packed</option>
                <option value="inTransit">In Transit</option>   
                <option value="delivered">Delivered</option>
            </select>
        </div>

        <div>
            <a href="{{ route('create.mission') }}" class="create-mission-button">
                <img src="{{ Vite::asset('resources/assets/plus-icon.svg') }}" alt="Plus Icon"> 
                Create Mission
            </a>
        </div>
    </div>

    <div class="missions-table">
        <div class="table-responsive">
            <table class="missions-table-table" role="table">
                <thead>
                    <tr>
                        <th scope="col">Mission Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Starting Location</th>
                        <th scope="col">Destination</th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Date Delivered</th>
                    </tr>
                </thead>
                <tbody id="missions-tbody">
                    @forelse($missions as $m)
                        @php
                            $missionData = is_array($m) ? $m : [
                                'id' => $m->id,
                                'missionName' => $m->mission_name,
                                'status' => $m->status,
                                'startingLocation' => $m->starting_location,
                                'destination' => $m->destination,
                                'email' => $m->email ?? '',
                                'dateCreated' => optional($m->created_at)->format('Y-m-d'),
                                'dateDelivered' => isset($m->date_delivered) ? optional($m->date_delivered)->format('Y-m-d') : '',
                            ];
                        @endphp
                        <tr class="mission-data-row" wire:click='openModal(@json($missionData))'>
                            <td>{{ is_array($m) ? $m['missionName'] : $m->mission_name }}</td>

                            @php
                                $statusValue = is_array($m) ? ($m['status'] ?? null) : ($m->status ?? null);
                                $statusEnum = \App\Enums\MissionStatus::tryFrom($statusValue);
                                $badgeColor = $statusEnum ? $statusEnum->color() : '#6b7280';
                                $badgeText = $statusEnum ? $statusEnum->label() : (\Illuminate\Support\Str::title((string) $statusValue ?? 'Unknown'));
                            @endphp

                            <td>
                                <span class="status-badge" style="background: {{ $badgeColor }}">{{ $badgeText }}</span>
                            </td>

                            <td>{{ is_array($m) ? $m['startingLocation'] : $m->starting_location }}</td>
                            <td>{{ is_array($m) ? $m['destination'] : $m->destination }}</td>
                            <td>{{ is_array($m) ? $m['dateCreated'] : optional($m->created_at)->format('Y-m-d') }}</td>
                            <td>{{ is_array($m) ? ($m['dateDelivered'] ?: 'N/A') : (isset($m->date_delivered) ? optional($m->date_delivered)->format('Y-m-d') : '-') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">
                                No missions found. <a href="{{ route('create.mission') }}" style="color: #3b82f6; text-decoration: underline;">Create one now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-wrap">
                @if (method_exists($missions, 'links'))
                    {{ $missions->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

