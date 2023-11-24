<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MatureResource;
use App\Models\Mature;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
 
class CalendarWidget extends FullCalendarWidget
{
    protected int | string | array $columnSpan = 4;
    protected static ?int $sort = 2;

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        return Mature::query()
            ->where('mature_date', '>=', $fetchInfo['start'])
            ->where('mature_date', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Mature $mature) => [
                    'id' => $mature->id,
                    'title' => $mature->expected_amount . ' '. $mature->wallet->name,
                    'start' => $mature->mature_date?->format('Y-m-d'),
                    'end' => $mature->mature_date?->addDay(2)?->format('Y-m-d'),
                    'color' => $mature->is_paid ? 'black' : 'green',
                    'url' => MatureResource::getUrl(name: 'edit', parameters: ['record' => $mature]),
                ],
            )
            ->toArray();
    }
}
