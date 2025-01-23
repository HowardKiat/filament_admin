<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Users';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Change this to your desired color
                    'borderColor' => 'rgba(75, 192, 192, 1)', // Optional: border color
                    'borderWidth' => 5, // Optional: border width
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
