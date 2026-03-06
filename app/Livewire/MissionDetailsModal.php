<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class MissionDetailsModal extends Component
{
    public bool $isOpen = false;
    public array $mission = [];
    public bool $editingStatus = false;
    public string $selectedStatus = '';

    #[On('openMissionModal')]
    public function openMissionModal(?array $missionData = null)
    {
        logger()->debug('openMissionModal called', ['mission_id' => $missionData['id'] ?? null]);
        if (empty($missionData)) {
            return;
        }

        $this->mission = $missionData;
        $this->selectedStatus = $missionData['status'] ?? '';
        $this->editingStatus = false;
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->mission = [];
        $this->editingStatus = false;
        $this->selectedStatus = '';
    }

    public function toggleEditStatus()
    {
        $this->editingStatus = !$this->editingStatus;
    }

    // TODO: TBD when update API is implemented - may need to move this logic? (MissionsTable)
    public function updateStatus()
    {
        if (!isset($this->mission['id'])) {
            return;
        }

        // Emit event to MissionsTable to handle the update
        $this->dispatch('statusUpdated', missionId: $this->mission['id'], newStatus: $this->selectedStatus);

        // Update local mission data
        $this->mission['status'] = $this->selectedStatus;
        $this->editingStatus = false;
    }

    public function render()
    {
        return view('livewire.mission-details-modal');
    }
}
