<?php

namespace App\Livewire;

use App\Models\ToiletReservation;
use Carbon\Carbon;
use Livewire\Component;

class ToiletReservations extends Component
{
    public string $nickname = '';

    public string $toilet = 'A';

    public string $slot_date = '';

    public string $slot_time = '';

    public function mount(): void
    {
        $today = now()->format('Y-m-d');
        $this->slot_date = $today;
        $this->slot_time = $this->nextSlotTime();
    }

    public function nextSlotTime(): string
    {
        $now = now();
        $minute = (int) ceil($now->minute / 15) * 15;
        if ($minute >= 60) {
            $now = $now->copy()->addHour()->setMinute(0);
        } else {
            $now = $now->copy()->setMinute($minute)->setSecond(0);
        }
        $time = $now->format('H:i');
        if ($time === '24:00' || $now->hour >= 24) {
            return '06:00';
        }
        return $time;
    }

    public function getTimeSlots(): array
    {
        $slots = [];
        for ($h = 6; $h < 24; $h++) {
            for ($m = 0; $m < 60; $m += 15) {
                $slots[] = sprintf('%02d:%02d', $h, $m);
            }
        }
        return $slots;
    }

    public function save(): void
    {
        $this->validate([
            'nickname' => 'required|string|max:50',
            'toilet' => 'required|in:A,B,C',
            'slot_date' => 'required|date',
            'slot_time' => 'required|string',
        ]);

        $slotAt = Carbon::parse($this->slot_date . ' ' . $this->slot_time);

        if ($slotAt->isPast()) {
            $this->addError('slot_time', 'Čas rezervace už uplynul.');
            return;
        }

        $exists = ToiletReservation::where('toilet', $this->toilet)
            ->where('slot_at', $slotAt)
            ->exists();

        if ($exists) {
            $this->addError('toilet', "Záchod {$this->toilet} je v tomto čase již obsazen.");
            return;
        }

        ToiletReservation::create([
            'nickname' => trim($this->nickname),
            'toilet' => $this->toilet,
            'slot_at' => $slotAt,
        ]);

        $this->reset('nickname');
        $this->slot_time = $this->nextSlotTime();
        $this->dispatch('reservation-saved');
    }

    public function deleteReservation(int $id): void
    {
        ToiletReservation::where('id', $id)->delete();
    }

    public function getReservationsProperty()
    {
        $date = $this->slot_date ?: now()->format('Y-m-d');
        return ToiletReservation::whereDate('slot_at', $date)
            ->orderBy('slot_at')
            ->orderBy('toilet')
            ->get();
    }

    public function getGridProperty(): array
    {
        $slots = $this->getTimeSlots();
        $date = $this->slot_date ?: now()->format('Y-m-d');
        $reservations = ToiletReservation::whereDate('slot_at', $date)
            ->get()
            ->keyBy(fn ($r) => $r->toilet . '-' . $r->slot_at->format('H:i'));

        $grid = [];
        foreach ($slots as $time) {
            $grid[$time] = [
                'A' => $reservations->get('A-' . $time),
                'B' => $reservations->get('B-' . $time),
                'C' => $reservations->get('C-' . $time),
            ];
        }
        return $grid;
    }

    public function render()
    {
        return view('livewire.toilet-reservations', [
            'reservations' => $this->reservations,
            'grid' => $this->grid,
            'timeSlots' => $this->getTimeSlots(),
        ])->layout('layouts.app', ['title' => 'Rezervace záchodů']);
    }
}
