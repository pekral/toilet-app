<div class="p-4 md:p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Rezervace záchodů A, B, C</h1>

    {{-- Formulář --}}
    <form wire:submit="save" class="bg-white dark:bg-[#161615] rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label for="nickname" class="block text-sm font-medium mb-1">Přezdívka</label>
                <input type="text" id="nickname" wire:model="nickname" placeholder="např. Honza"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2 text-sm"
                    maxlength="50" required>
                @error('nickname')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="toilet" class="block text-sm font-medium mb-1">Záchod</label>
                <select id="toilet" wire:model="toilet"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2 text-sm">
                    @foreach(['A', 'B', 'C'] as $t)
                        <option value="{{ $t }}">Záchod {{ $t }}</option>
                    @endforeach
                </select>
                @error('toilet')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="slot_date" class="block text-sm font-medium mb-1">Datum</label>
                <input type="date" id="slot_date" wire:model="slot_date"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label for="slot_time" class="block text-sm font-medium mb-1">Čas (15 min)</label>
                <select id="slot_time" wire:model="slot_time"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2 text-sm">
                    @foreach($timeSlots as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
                @error('slot_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <button type="submit" class="w-full bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] font-medium py-2 px-4 rounded hover:opacity-90 transition">
                    Rezervovat
                </button>
            </div>
        </div>
    </form>

    {{-- Výběr data pro zobrazení rozvrhu --}}
    <div class="mb-4 flex items-center gap-2">
        <span class="text-sm text-gray-600 dark:text-gray-400">Rozvrh na:</span>
        <input type="date" wire:model.live="slot_date"
            class="rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-1.5 text-sm">
    </div>

    {{-- Tabulka rozvrhu: řádky = časy, sloupce = A, B, C --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#161615]">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <th class="text-left py-2 px-3 font-medium w-20">Čas</th>
                    <th class="text-left py-2 px-3 font-medium">Záchod A</th>
                    <th class="text-left py-2 px-3 font-medium">Záchod B</th>
                    <th class="text-left py-2 px-3 font-medium">Záchod C</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grid as $time => $cells)
                    <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                        <td class="py-2 px-3 text-gray-600 dark:text-gray-400">{{ $time }}</td>
                        @foreach(['A', 'B', 'C'] as $toilet)
                            <td class="py-2 px-3">
                                @if($cells[$toilet])
                                    <span class="inline-flex items-center gap-2">
                                        <span class="font-medium">{{ $cells[$toilet]->nickname }}</span>
                                        <button type="button" wire:click="deleteReservation({{ $cells[$toilet]->id }})"
                                            wire:confirm="Smazat rezervaci?"
                                            class="text-red-500 hover:text-red-700 text-xs" title="Smazat">
                                            ×
                                        </button>
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">Jedna rezervace = 15 minut. Kliknutím na × smažete rezervaci.</p>
</div>
