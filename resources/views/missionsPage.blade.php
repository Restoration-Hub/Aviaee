<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missions</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])</head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<body>
    <div class="header">
        <div>AVIAEE</div>
    </div>

    <div class="missions-header-container">
        <h1 class="missions-header">Missions</h1>
    </div>

    <div class="missions-main">

        <div class="missions-top-bar">
            <div class="missions-top-bar-left">
                <form id="missionSearchForm" action="{{ url()->current() }}" method="GET" role="search" class="missions-search-form">
                    <input
                        type="search"
                        name="q"
                        id="missionSearch"
                        value="{{ request('q') }}"
                        placeholder="Search..."
                        autocomplete="off"
                        class="search-input"
                    />
                    
                    {{-- TODO: add a tooltip on this button maybe? --}}
                    <button type="submit" class="search-button">
                        <img src="{{ Vite::asset('resources/assets/search-icon.svg') }}" alt="Search Icon">
                    </button> 

                    {{-- TODO: add more styling and functionality --}}
                    <select name="status" id="statusFilter" class="status-filter">
                        <option value="">All statuses</option>
                        <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="packed" {{ request('status') == 'packed' ? 'selected' : '' }}>Packed</option>
                        <option value="inTransit" {{ request('status') == 'inTransit' ? 'selected' : '' }}>In Transit</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </form>
            </div>
            <div>
                <button id="createMissionBtn" class="create-mission-button">
                    <img src="{{ Vite::asset('resources/assets/plus-icon.svg') }}" alt="Plus Icon">
                    Create Mission
                </button>
            </div>
        </div>

        <div class="missions-table">
            <div>
                Header Row
            </div>
            <div>
                Mission Rows
            </div>
        </div>
    </div>

    <script>
        document.getElementById('createMissionBtn').addEventListener('click', function() {
            // window.location.href = '/missions/create';
        });

        // Debounced search input that calls a future backend endpoint (e.g. /missions/search?q=...)
        (function() {
            const input = document.getElementById('missionSearch');
            const status = document.getElementById('statusFilter');
            const table = document.querySelector('.missions-table');
            let timeout = null;

            function showPlaceholder() {
                const sections = table.querySelectorAll('div');
                if (sections.length >= 2) {
                    sections[1].innerHTML = '<div class="placeholder">Search UI ready — backend search endpoint not implemented yet.</div>';
                }
            }

            function performSearch() {
                const q = input ? input.value.trim() : '';
                const s = status ? status.value : '';
                const url = `/missions/search?q=${encodeURIComponent(q)}&status=${encodeURIComponent(s)}`;

                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(resp => {
                        if (!resp.ok) throw new Error('No API');
                        return resp.json();
                    })
                    .then(data => {
                        // Expecting an array of missions; adapt later when backend is implemented
                        const rows = data.map(m => `
                            <div class="mission-row">
                                <div><strong>${m.name || 'Untitled'}</strong></div>
                                <div>${m.location || ''} — ${m.status || ''}</div>
                            </div>`).join('');
                        const content = `<div>Header Row</div><div>${rows || '<div>No results</div>'}</div>`;
                        table.innerHTML = content;
                    })
                    .catch(() => {
                        // Backend not ready — show helpful placeholder
                        showPlaceholder();
                    });
            }

            if (input) {
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(performSearch, 400);
                });
            }

            if (status) {
                status.addEventListener('change', function() {
                    if (input) input.dispatchEvent(new Event('input'));
                    else performSearch();
                });
            }

            // If page loaded with query params, run initial search attempt
            if ((input && input.value) || (status && status.value)) {
                performSearch();
            } else {
                // Show helpful placeholder so users know front-end is ready
                showPlaceholder();
            }
        })();
    </script>

</body>
</html>