<div>
    @if($isOpen ?? false)
        <!-- Overlay -->
        <div class="mission-modal-overlay" wire:click="close()"></div>

        <!-- Modal -->
        <div class="mission-details-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <!-- Header -->
            <div class="modal-header">
                <h2 id="modal-title" class="modal-title">{{ $mission['missionName'] ?? 'Mission Details' }}</h2>
                <button type="button" class="close-button" wire:click="close()" aria-label="Close modal">
                    <img src="{{ Vite::asset('resources/assets/close-icon.svg') }}" alt="Close Icon">
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="mission-detail-row">
                    @if($editingStatus)
                        <label for="status-select" class="detail-label">STATUS:</label>
                        <select id="status-select" wire:model="selectedStatus" class="status-select">
                            @foreach(\App\Enums\MissionStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    @else
                        @php
                            $statusValue = $mission['status'] ?? null;
                            $statusEnum = \App\Enums\MissionStatus::tryFrom($statusValue);
                            $badgeColor = $statusEnum ? $statusEnum->color() : '#6b7280';
                            $badgeText = $statusEnum ? $statusEnum->label() : \Illuminate\Support\Str::title((string) ($statusValue ?? 'Unknown'));
                        @endphp
                        <span class="mission-details-status-badge" style="background: {{ $badgeColor }}">STATUS: {{ $badgeText }}</span>
                    @endif
                </div>

                <div class="mission-detail-row">
                    <span class="detail-label">Starting Location:</span>
                    <span class="detail-value">{{ $mission['startingLocation'] ?? 'N/A' }}</span>
                </div>

                <div class="mission-detail-row">
                    <span class="detail-label">Destination:</span>
                    <span class="detail-value">{{ $mission['destination'] ?? 'N/A' }}</span>
                </div>

                <div class="mission-detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $mission['email'] ?? 'N/A' }}</span>
                </div>

                <div class="mission-detail-row">
                    <span class="detail-label">Date Created:</span>
                    <span class="detail-value">{{ $mission['dateCreated'] ?? 'N/A' }}</span>
                </div>

                <div class="mission-detail-row">
                    <span class="detail-label">Date Delivered:</span>
                    <span class="detail-value">{{ empty($mission['dateDelivered']) ? 'N/A' : $mission['dateDelivered'] }}</span>
                </div>
            </div>

            <!-- Footer with Actions -->
            <div class="modal-footer">
                @if($editingStatus)
                    <button type="button" class="action-button" wire:click="updateStatus()">
                        Save Status
                    </button>
                    <button type="button" class="action-cancel-button" wire:click="toggleEditStatus()">
                        Cancel
                    </button>
                @else
                    <button type="button" class="action-button-secondary" wire:click="toggleEditStatus()">
                        Edit Status
                    </button>
                    <button type="button" class="delete-button" wire:click="openDeleteConfirmationPopup()">
                        Delete Mission
                    </button>
                    <button type="button" class="action-button" wire:click="close()">
                        Close
                    </button>
                @endif
            </div>

            {{-- Delete confirmation popup --}}
            @if($showDeleteConfirmation)
                <div class="delete-confirmation-overlay" wire:click="closeDeleteConfirmationPopup"></div>

                <div class="delete-confirmation-modal" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
                    <h3 id="delete-modal-title">Delete Mission?</h3>
                    <p>
                        Are you sure you want to delete
                        <strong>{{ $mission['missionName'] ?? 'this mission' }}</strong>?
                        This action cannot be undone.
                    </p>

                    <div class="delete-confirmation-actions">
                        <button type="button" class="cancel-button" wire:click="closeDeleteConfirmationPopup">
                            Cancel
                        </button>
                        <button type="button" class="delete-button" wire:click="deleteMission">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('deleteMission', async ({ missionId }) => {
        if (!missionId) return;

        try {
            const resp = await fetch('/missions/delete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({ missionId })
            });

            const text = await resp.text();
            let data = {};

            try {
                data = text ? JSON.parse(text) : {};
            } catch (e) {
                sessionStorage.setItem(
                    'missionBannerMessage',
                    'Server returned invalid response'
                );

                sessionStorage.setItem('missionBannerType','error');

                // window.location.reload();
                return;
            }

            if (!resp.ok) {
                sessionStorage.setItem(
                    'missionBannerMessage',
                    data.message || 'Failed to delete mission'
                );

                sessionStorage.setItem('missionBannerType','error');

                // window.location.reload();
                return;
            }

            // Store banner before reload
            sessionStorage.setItem(
                'missionBannerMessage',
                data.message || 'Mission deleted successfully!'
            );

            sessionStorage.setItem('missionBannerType','success');

            // Refresh page immediately
            window.location.reload();

        } catch (err) {
            console.error(err);

            sessionStorage.setItem(
                'missionBannerMessage',
                'Error deleting mission'
            );

            sessionStorage.setItem('missionBannerType','error');

            // window.location.reload();
        }
    });

    Livewire.on('updateMissionStatus', async ({ missionId, newStatus }) => {
        if (!missionId || !newStatus) return;

        try {
            const resp = await fetch('/missions/updateStatus', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({ missionId, status: newStatus })
            });

            const text = await resp.text();
            let data = {};

            try {
                data = text ? JSON.parse(text) : {};
            } catch (e) {
                sessionStorage.setItem(
                    'missionBannerMessage',
                    'Server returned invalid response'
                );

                sessionStorage.setItem('missionBannerType','error');

                // window.location.reload();
                return;
            }

            if (!resp.ok) {
                sessionStorage.setItem(
                    'missionBannerMessage',
                    data.message || 'Failed to update mission status'
                );

                sessionStorage.setItem('missionBannerType','error');

                // window.location.reload();
                return;
            }

            // Store banner before reload
            sessionStorage.setItem(
                'missionBannerMessage',
                data.message || 'Mission status updated successfully!'
            );

            sessionStorage.setItem('missionBannerType','success');

            // Refresh page immediately
            window.location.reload();

        } catch (err) {
            console.error(err);

            sessionStorage.setItem(
                'missionBannerMessage',
                'Error updating mission status'
            );

            sessionStorage.setItem('missionBannerType','error');

            // window.location.reload();
        }
    });
});
</script>