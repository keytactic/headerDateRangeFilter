# Filament Header Date Range Filter

A Filament plugin that adds a date range filter component as a header action. Perfect for filtering tables, charts, and metrics by date ranges with preset options or custom date selection.

## Installation

You can install the package via composer:

```bash
composer require keytactic/headerdaterangefilter
```

## Usage

### Basic Usage

Add the trait and action to your Filament page or resource:

```php
use Keytactic\HeaderDateRangeFilter\Concerns\HasDateRangeFilter;
use Keytactic\HeaderDateRangeFilter\HeaderDateRangeFilterAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListClaims extends ListRecords
{
    use HasDateRangeFilter;
    
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
            HeaderDateRangeFilterAction::make(),
        ];
    }
    
    // Override getTableQuery to apply the date range filter
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        return $this->applyDateRangeFilterToTableQuery($query);
    }
}
```

### Dashboard Integration

You can also use the date range filter on dashboard pages:

```php
use Keytactic\HeaderDateRangeFilter\Concerns\HasDateRangeFilter;
use Keytactic\HeaderDateRangeFilter\HeaderDateRangeFilterAction;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use HasDateRangeFilter;
    
    public function mount()
    {
        parent::mount();
        $this->mountHasDateRangeFilter();
    }
    
    protected function getHeaderActions(): array
    {
        return [
            HeaderDateRangeFilterAction::make(),
        ];
    }
    
    // Pass the date range to your dashboard widgets
    public function getWidgetData(): array
    {
        return [
            'filters' => [
                'dateRange' => $this->dateRange,
            ],
        ];
    }
}
```

### Widget Integration

In your widgets, you can access the date range filter like this:

```php
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ClaimStats extends \Filament\Widgets\StatsOverviewWidget
{
    use InteractsWithPageFilters;
    
    protected function getStats(): array
    {
        $filters = $this->getFilters();
        $dateRange = $filters['dateRange'] ?? [];
        
        // Apply date range to your stats queries
        $query = Claim::query();
        
        if (!empty($dateRange['from'])) {
            $query->where('created_at', '>=', $dateRange['from'] . ' 00:00:00');
        }
        
        if (!empty($dateRange['until'])) {
            $query->where('created_at', '<=', $dateRange['until'] . ' 23:59:59');
        }
        
        return [
            // Your stats based on the filtered query
        ];
    }
}
```

## Customization

### Customizing Preset Ranges

You can override the `setPresetDateRange` method in your component to add custom presets:

```php
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
        'mtd' => [
            'from' => now()->startOfMonth()->toDateString(),
            'until' => now()->endOfDay()->toDateString(),
        ],
        'ytd' => [
            'from' => now()->startOfYear()->toDateString(),
            'until' => now()->endOfDay()->toDateString(),
        ],
        // Add your custom presets here
        default => $this->dateRange,
    };

    $this->dispatch('filter-updated');
}
```

### Customizing the View

You can publish the view and customize it:

```bash
php artisan vendor:publish --tag="headerdaterangefilter-views"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
