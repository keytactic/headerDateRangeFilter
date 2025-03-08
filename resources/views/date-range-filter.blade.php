<div
    x-data="{
        open: false,
        from: @entangle('dateRange.from').live,
        until: @entangle('dateRange.until').live,
        
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date + 'T00:00:00Z');
            return d.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                timeZone: 'UTC'
            });
        },
        
        init() {
            this.$watch('from', value => {
                if (value && this.until && value > this.until) {
                    this.until = value;
                }
            });
            
            this.$watch('until', value => {
                if (value && this.from && value < this.from) {
                    this.from = value;
                }
            });
        },

        applyDateRange() {
            this.open = false;
            // Emit an event to update the filters
            $wire.dispatch('filter-updated');
        },
        
        setPresetRange(days) {
            const end = new Date();
            const start = new Date();
            start.setDate(start.getDate() - days);
            
            this.from = start.toISOString().split('T')[0];
            this.until = end.toISOString().split('T')[0];
            
            // Use the same apply method as the custom date range
            this.applyDateRange();
        }
    }"
    class="relative inline-block text-left"
    @click.away="open = false"
>
    <button
        type="button"
        @click="open = !open"
        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
    >
        <x-heroicon-m-calendar class="w-5 h-5 text-gray-400 dark:text-gray-500" />
        <span x-text="from && until ? `${formatDate(from)} - ${formatDate(until)}` : 'Select dates'"></span>
        <x-heroicon-m-chevron-down x-show="!open" class="w-5 h-5 ml-2 -mr-1 text-gray-400 dark:text-gray-500" />
        <x-heroicon-m-chevron-up x-show="open" class="w-5 h-5 ml-2 -mr-1 text-gray-400 dark:text-gray-500" />
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute left-0 z-50 mt-2 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:ring-gray-700 border border-gray-300 dark:border-gray-700"
        style="min-width: 300px;"
    >
        <div class="p-4 space-y-4">
            <!-- Preset Ranges -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Preset Ranges</label>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        @click="setPresetRange(7)"
                        type="button"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200"
                    >
                        Last 7 Days
                    </button>
                    <button
                        @click="setPresetRange(30)"
                        type="button"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200"
                    >
                        Last 30 Days
                    </button>
                    <button
                        @click="setPresetRange(90)"
                        type="button"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200"
                    >
                        Last 90 Days
                    </button>
                    <button
                        @click="setPresetRange(180)"
                        type="button"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200"
                    >
                        Last 6 Months
                    </button>
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800">or choose date range</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                <input
                    type="date"
                    x-model="from"
                    class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm"
                >
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                <input
                    type="date"
                    x-model="until"
                    class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm"
                >
            </div>

            <div class="flex justify-end space-x-2">
                <button
                    @click="applyDateRange()"
                    type="button" 
                    class="px-3 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                >
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>