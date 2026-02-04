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
                    <tr id="missions-loading"><td colspan="6">Loading missions...</td></tr>

                    @forelse($missions as $m)
                        <tr class="server-mission">
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
                        {{-- No missions from server; will be handled by JS below --}}
                    @endforelse
                </tbody>

                <script>
                    (function() {
                        const tbody = document.getElementById('missions-tbody');
                        if (!tbody) return;

                        const loadingRow = '<tr><td colspan="6">Loading missions...</td></tr>';
                        const noResultsRow = '<tr><td colspan="6" class="no-results">No missions found.</td></tr>';

                        const statusMap = {
                            'ordered': {label: 'Ordered', color: '#F7D579'},
                            'packed': {label: 'Packed', color: '#F5A97B'},
                            'inTransit': {label: 'In Transit', color: '#99D5E4'},
                            'delivered': {label: 'Delivered', color: '#C6E293'},
                        };

                        async function fetchAndRender() {
                            tbody.innerHTML = loadingRow;

                            try {
                                const resp = await fetch('/missions', { credentials: 'same-origin' });
                                if (!resp.ok) {
                                    tbody.innerHTML = '<tr><td colspan="6">Error loading missions.</td></tr>';
                                    return;
                                }

                                const contentType = resp.headers.get('Content-Type') || '';
                                if (!contentType.includes('application/json')) {
                                    // Likely redirected to login or HTML response - show message
                                    tbody.innerHTML = '<tr><td colspan="6">Not authenticated — please sign in.</td></tr>';
                                    return;
                                }

                                const json = await resp.json();
                                const missions = json.missions || [];

                                if (!missions.length) {
                                    tbody.innerHTML = noResultsRow;
                                    return;
                                }

                                const rows = missions.map(function(m) {
                                    const s = statusMap[m.status] || {label: (m.status || 'Unknown'), color: '#6b7280'};
                                    const dateDelivered = m.dateDelivered ? m.dateDelivered : 'N/A';

                                    return '<tr>' +
                                        '<td>' + escapeHtml(m.missionName) + '</td>' +
                                        '<td><span class="status-badge" style="background: ' + s.color + '">' + escapeHtml(s.label) + '</span></td>' +
                                        '<td>' + escapeHtml(m.startingLocation) + '</td>' +
                                        '<td>' + escapeHtml(m.destination) + '</td>' +
                                        '<td>' + escapeHtml(m.dateCreated) + '</td>' +
                                        '<td>' + escapeHtml(dateDelivered) + '</td>' +
                                    '</tr>';
                                }).join('');

                                tbody.innerHTML = rows;

                            } catch (e) {
                                tbody.innerHTML = '<tr><td colspan="6">Network error while loading missions.</td></tr>';
                            }
                        }

                        function escapeHtml(str) {
                            if (str === null || str === undefined) return '';
                            return String(str)
                                .replace(/&/g, '&amp;')
                                .replace(/</g, '&lt;')
                                .replace(/>/g, '&gt;')
                                .replace(/"/g, '&quot;')
                                .replace(/'/g, '&#039;');
                        }

                        // Run on DOM ready
                        document.addEventListener('DOMContentLoaded', fetchAndRender);

                        // Also run after Livewire updates if available
                        document.addEventListener('livewire:load', function() {
                            fetchAndRender();
                            if (window.Livewire && Livewire.hook) {
                                Livewire.hook('message.processed', fetchAndRender);
                            }
                        });
                    })();
                </script>
            </table>

            <div class="pagination-wrap">
                @if (method_exists($missions, 'links'))
                    {{ $missions->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

