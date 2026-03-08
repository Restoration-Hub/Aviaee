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
                {{-- TODO: not sure about these buttons (TBD when update API done) --}}
                    <button type="button" class="action-button" wire:click="updateStatus()">
                        Save Status
                    </button>
                    <button type="button" class="cancel-button" wire:click="toggleEditStatus()">
                        Cancel
                    </button>
                @else
                    <button type="button" class="action-button-secondary" wire:click="toggleEditStatus()">
                        Edit Status
                    </button>
                    <button type="button" class="action-button" wire:click="close()">
                        Close
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
