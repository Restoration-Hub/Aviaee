<div class="missions-table-component">
    @php $missions = $missions ?? collect(); @endphp

    <div class="missions-top-bar">
        <div class="missions-search-form">
            <input type="search" wire:model.debounce.500ms="search" placeholder="Search..." class="search-input" aria-label="Search missions" />
            <img src="{{ Vite::asset('resources/assets/search-icon.svg') }}" alt="Search Icon" class="search-icon">

            <select wire:model="status" class="status-filter">
                <option value="">All statuses</option>
                <option value="ordered">Ordered</option>
                <option value="packed">Packed</option>
                <option value="inTransit">In Transit</option>   
                <option value="delivered">Delivered</option>
            </select>
        </div>

        {{-- TODO: update with actual create missions url once done implementation --}}
        <div>
            <a href="{{ url('/missions/create') }}" class="create-mission-button">
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
                <tbody>
                    @forelse($missions as $m)
                        <tr>
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
                        @php $s = \App\Enums\MissionStatus::ORDERED; @endphp
                        <tr>
                            <td>Test Mission 1</td>
                            <td><span class="status-badge" style="background: {{ $s->color() }}">{{ $s->label() }}</span></td>
                            <td>Point A</td>
                            <td>Point B</td>
                            <td>2026-01-10</td>
                            <td>N/A</td>
                        </tr>

                        @php $s = \App\Enums\MissionStatus::PACKED; @endphp
                        <tr>
                            <td>Test Mission 2</td>
                            <td><span class="status-badge" style="background: {{ $s->color() }}">{{ $s->label() }}</span></td>
                            <td>Point C</td>
                            <td>Point D</td>
                            <td>2026-01-12</td>
                            <td>N/A</td>
                        </tr>

                        @php $s = \App\Enums\MissionStatus::IN_TRANSIT; @endphp
                        <tr>
                            <td>Test Mission 3</td>
                            <td><span class="status-badge" style="background: {{ $s->color() }}">{{ $s->label() }}</span></td>
                            <td>Point E</td>
                            <td>Point F</td>
                            <td>2026-01-10</td>
                            <td>N/A</td>
                        </tr>

                        @php $s = \App\Enums\MissionStatus::DELIVERED; @endphp
                        <tr>
                            <td>Test Mission 4</td>
                            <td><span class="status-badge" style="background: {{ $s->color() }}">{{ $s->label() }}</span></td>
                            <td>Point G</td>
                            <td>Point H</td>
                            <td>2026-01-10</td>
                            <td>2026-01-15</td>
                        </tr>

                        {{-- TODO: uncomment this when the backend implementation is complete --}}
                        {{-- <tr>
                            <td colspan="6" class="no-results">No missions found.</td>
                        </tr> --}}
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

