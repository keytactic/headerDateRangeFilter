<?php

namespace Keytactic\HeaderDateRangeFilter;

use Filament\Actions\Action;
use Illuminate\Support\Carbon;

class HeaderDateRangeFilterAction extends Action
{
    protected string $view = 'headerdaterangefilter::date-range-filter';
    
    public function __construct(string $name = 'dateRange')
    {
        parent::__construct($name);
        
        $this->mountUsing(function (HeaderDateRangeFilterAction $action, $livewire) {
            // Initialize dateRange if it's not set
            if (!isset($livewire->dateRange) || empty($livewire->dateRange)) {
                $livewire->dateRange = [
                    'from' => request()->input('dateRange.from', now()->subDays(30)->startOfDay()->toDateString()),
                    'until' => request()->input('dateRange.until', now()->endOfDay()->toDateString()),
                ];
            }
        });
        
        $this->after(function ($action, $livewire) {
            // Dispatch filter-updated event after the action is executed
            if (method_exists($livewire, 'dispatch')) {
                $livewire->dispatch('filter-updated');
            }
        });
    }

    public static function make(?string $name = null): static
    {
        $name ??= 'dateRange';
        return app(static::class, ['name' => $name]);
    }
}