<div class="min-h-screen p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto">
    {{-- Header s ikonkou --}}
    <header class="flex flex-wrap items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
        <span class="text-4xl sm:text-5xl" aria-hidden="true">🧻</span>
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100">
                Rezervace záchodů
            </h1>
            <p class="text-sm sm:text-base text-pink-600 dark:text-pink-400 mt-0.5">
                A, B, C — vyber čas, my navrhneme volný záchod
            </p>
        </div>
        <span class="text-3xl sm:text-4xl opacity-80" aria-hidden="true" title="Hurá na záchod!">💩</span>
    </header>

    {{-- Formulář --}}
    <form wire:submit="save" class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-pink-200 dark:border-pink-900/50 shadow-lg shadow-pink-100 dark:shadow-pink-950/20 p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 items-end">
            <div>
                <label for="nickname" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-300">Přezdívka</label>
                <input type="text" id="nickname" wire:model="nickname" placeholder="např. Honza"
                    class="w-full rounded-xl border-2 border-pink-200 dark:border-pink-800 dark:bg-gray-800 px-3 py-2.5 text-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 dark:focus:ring-pink-900/50 transition"
                    maxlength="50" required>
                @error('nickname')
                    <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="toilet" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-300">Záchod</label>
                <select id="toilet" wire:model="toilet"
                    class="w-full rounded-xl border-2 border-pink-200 dark:border-pink-800 dark:bg-gray-800 px-3 py-2.5 text-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 dark:focus:ring-pink-900/50 transition">
                    @foreach(['A', 'B', 'C'] as $t)
                        @php($isFree = in_array($t, $freeToilets))
                        <option value="{{ $t }}">
                            Záchod {{ $t }}{{ $isFree ? ' (volný)' : ' (obsazený)' }}
                        </option>
                    @endforeach
                </select>
                @if(count($freeToilets) === 0)
                    <p class="text-amber-600 dark:text-amber-400 text-xs mt-1">V tomto čase jsou všechny záchody obsazené.</p>
                @else
                    <p class="text-pink-600 dark:text-pink-400 text-xs mt-1">Podle času je navržen první volný záchod (bez překryvu).</p>
                @endif
                @error('toilet')
                    <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="slot_date" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-300">Datum</label>
                <input type="date" id="slot_date" wire:model.live="slot_date"
                    class="w-full rounded-xl border-2 border-pink-200 dark:border-pink-800 dark:bg-gray-800 px-3 py-2.5 text-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 dark:focus:ring-pink-900/50 transition" required>
            </div>
            <div>
                <label for="slot_time" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-300">Čas (15 min)</label>
                <select id="slot_time" wire:model.live="slot_time"
                    class="w-full rounded-xl border-2 border-pink-200 dark:border-pink-800 dark:bg-gray-800 px-3 py-2.5 text-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 dark:focus:ring-pink-900/50 transition">
                    @foreach($timeSlots as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
                @error('slot_time')
                    <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 active:bg-pink-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md shadow-pink-300/50 dark:shadow-pink-950/30 transition focus:ring-2 focus:ring-pink-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    Rezervovat
                </button>
            </div>
        </div>
    </form>

    {{-- Výběr data pro rozvrh --}}
    <div class="mb-4 flex flex-wrap items-center gap-2 sm:gap-3">
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Rozvrh na:</span>
        <input type="date" wire:model.live="slot_date"
            class="rounded-xl border-2 border-pink-200 dark:border-pink-800 dark:bg-gray-800 px-3 py-2 text-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 dark:focus:ring-pink-900/50 transition">
    </div>

    {{-- Tabulka rozvrhu --}}
    <div class="overflow-x-auto rounded-2xl border-2 border-pink-200 dark:border-pink-900/50 bg-white dark:bg-gray-900 shadow-lg shadow-pink-100 dark:shadow-pink-950/20">
        <table class="w-full text-sm min-w-[280px]">
            <thead>
                <tr class="border-b-2 border-pink-200 dark:border-pink-800 bg-pink-50 dark:bg-pink-950/30">
                    <th class="text-left py-3 px-3 sm:px-4 font-bold text-pink-800 dark:text-pink-300 w-20">Čas</th>
                    <th class="text-left py-3 px-3 sm:px-4 font-bold text-pink-800 dark:text-pink-300">Záchod A</th>
                    <th class="text-left py-3 px-3 sm:px-4 font-bold text-pink-800 dark:text-pink-300">Záchod B</th>
                    <th class="text-left py-3 px-3 sm:px-4 font-bold text-pink-800 dark:text-pink-300">Záchod C</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grid as $time => $cells)
                    <tr class="border-b border-pink-100 dark:border-pink-900/50 hover:bg-pink-50/50 dark:hover:bg-pink-950/20 transition">
                        <td class="py-2.5 px-3 sm:px-4 text-gray-600 dark:text-gray-400 font-medium">{{ $time }}</td>
                        @foreach(['A', 'B', 'C'] as $toilet)
                            <td class="py-2.5 px-3 sm:px-4">
                                @if($cells[$toilet])
                                    <span class="inline-flex items-center gap-2 flex-wrap">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $cells[$toilet]->nickname }}</span>
                                        <button type="button" wire:click="deleteReservation({{ $cells[$toilet]->id }})"
                                            wire:confirm="Smazat rezervaci?"
                                            class="text-pink-500 hover:text-rose-600 dark:hover:text-rose-400 text-sm font-bold w-6 h-6 rounded-full hover:bg-pink-100 dark:hover:bg-pink-900/40 transition" title="Smazat">
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

    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
        Jedna rezervace = 15 minut. Kliknutím na × smažete rezervaci.
    </p>
</div>
