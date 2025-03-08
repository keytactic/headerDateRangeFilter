<?php

namespace Keytactic\HeaderDateRangeFilter\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasDateRangeFilter
{
    public $dateRange = [];

    public function mountHasDateRangeFilter(): void
    {
        $this->dateRange = [
            'from' => request()->input('dateRange.from', now()->subDays(30)->startOfDay()->toDateString()),
            'until' => request()->input('dateRange.until', now()->endOfDay()->toDateString()),
        ];
    }

    public function initializeHasDateRangeFilter(): void
    {
        $this->listeners = array_merge($this->listeners ?? [], [
            'filter-updated' => '$refresh',
        ]);
    }

    public function getDateRangeFilterLabel(): string
    {
        if (empty($this->dateRange['from']) || empty($this->dateRange['until'])) {
            return 'All time';
        }
        
        $from = Carbon::parse($this->dateRange['from'])->format('M j, Y');
        $until = Carbon::parse($this->dateRange['until'])->format('M j, Y');
        
        return "{$from} - {$until}";
    }

    public function updatedDateRange(): void
    {
        $this->dispatch('filter-updated');
    }

    public function setPresetDateRange(string $preset): void
    {
        $this->dateRange = match($preset) {
            '7days' => [
                'from' => now()->subDays(7)->startOfDay()->toDateString(),
                'until' => now()->endOfDay()->toDateString(),
            ],
            '30days' => [
                'from' => now()->subDays(30)->startOfDay()->toDateString(),
                'until' => now()->endOfDay()->toDateString(),
            ],
            '90days' => [
                'from' => now()->subDays(90)->startOfDay()->toDateString(),
                'until' => now()->endOfDay()->toDateString(),
            ],
            '6months' => [
                'from' => now()->subMonths(6)->startOfDay()->toDateString(),
                'until' => now()->endOfDay()->toDateString(),
            ],
            'this-month' => [
                'from' => now()->startOfMonth()->toDateString(),
                'until' => now()->endOfMonth()->toDateString(),
            ],
            'last-month' => [
                'from' => now()->subMonth()->startOfMonth()->toDateString(),
                'until' => now()->subMonth()->endOfMonth()->toDateString(),
            ],
            'this-year' => [
                'from' => now()->startOfYear()->toDateString(),
                'until' => now()->endOfYear()->toDateString(),
            ],
            default => $this->dateRange,
        };

        $this->dispatch('filter-updated');
    }
    
    /**
     * Apply the date range filter to a query builder
     */
    protected function applyDateRangeFilter(Builder $query, string $column = 'created_at'): Builder
    {
        if (!empty($this->dateRange['from'])) {
            $query->where($column, '>=', $this->dateRange['from'] . ' 00:00:00');
        }
        
        if (!empty($this->dateRange['until'])) {
            $query->where($column, '<=', $this->dateRange['until'] . ' 23:59:59');
        }
        
        return $query;
    }
    
    /**
     * Apply date range filters to the table query
     */
    protected function applyDateRangeFilterToTableQuery(Builder $query, string $column = 'created_at'): Builder
    {
        return $this->applyDateRangeFilter($query, $column);
    }
    
    /**
     * Get widget data with date range filters included
     */
    public function getWidgetData(): array
    {
        return [
            'filters' => [
                'dateRange' => $this->dateRange,
            ],
        ];
    }
}