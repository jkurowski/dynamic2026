<?php

namespace App\Http\Controllers\Admin\Crm\Statistics;

use App\Charts\HourlyChart;
use App\Charts\RoomChart;
use App\Charts\SourceChart;
use App\Http\Controllers\Controller;
use App\Models\ClientMessage;
use App\Models\Investment;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('pl');
        $borderColors = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];

        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"
        ];

        $clientMessages = ClientMessage::all();

        $sources = [];
        $campaigns = [];

        foreach ($clientMessages as $clientMessage) {
            $arguments = json_decode($clientMessage->arguments, true);

            if (isset($arguments['dp_source'])) {
                $dpSource = $arguments['dp_source'];
                if (!in_array($dpSource, $sources)) {
                    $sources[] = $dpSource;
                }
            }

            if (isset($arguments['dp_campaign'])) {
                $dpCampaign = $arguments['dp_campaign'];
                if (!in_array($dpCampaign, $campaigns)) {
                    $campaigns[] = $dpCampaign;
                }
            }
        }

        $messages = ClientMessage::orderByDesc('created_at')->get();

        // Apply your filtering logic to $messages
        if ($request->filled('campaign')) {
            $campaign = $request->input('campaign');
            $messages = $messages->filter(function ($message) use ($campaign) {
                $arguments = json_decode($message->arguments, true);
                return isset($arguments['dp_campaign']) && $arguments['dp_campaign'] === $campaign;
            });
        }

        if ($request->filled('source')) {
            $source = $request->input('source');
            $messages = $messages->filter(function ($message) use ($source) {
                $arguments = json_decode($message->arguments, true);
                return isset($arguments['dp_source']) && $arguments['dp_source'] === $source;
            });
        }

        if ($request->filled('date_from')) {
            $dateFrom = date('Y-m-d', strtotime($request->input('date_from')));
            $messages = $messages->filter(function ($message) use ($dateFrom) {
                return $message->created_at >= $dateFrom;
            });
        }

        if ($request->filled('date_to')) {
            $dateTo = date('Y-m-d', strtotime($request->input('date_to')));
            $messages = $messages->filter(function ($message) use ($dateTo) {
                return $message->created_at <= $dateTo;
            });
        }

        $messages_campaigns = $messages->map(function ($message) {
            $arguments = json_decode($message->arguments, true);
            return $arguments['dp_campaign'] ?? null;
        })->groupBy(function ($campaign) {
            return $campaign;
        })->map->count();

        $campaigns_chart = new RoomChart();
        $campaigns_chart->labels($messages_campaigns->keys()->all());
        $campaigns_chart->dataset('Wiadomości', 'bar', $messages_campaigns->values()->all());

        $messages_sources = $messages->map(function ($message) {
            $arguments = json_decode($message->arguments, true);
            return $arguments['dp_source'] ?? null;
        })->groupBy(function ($source) {
            return $source;
        })->map->count();

        $sources_chart = new SourceChart();
        $sources_chart->labels($messages_sources->keys()->all());
        $sources_chart->dataset('Wiadomości', 'bar', $messages_sources->values()->all());


        $messages_rooms = $messages->map(function ($message) {
            $arguments = json_decode($message->arguments, true);
            return $arguments['rooms'] ?? null;
        })->groupBy(function ($source) {
            return $source;
        })->map->count();

        $rooms_chart = new RoomChart();
        $rooms_chart->labels($messages_rooms->keys()->all());
        $rooms_chart->dataset('Pokoje', 'bar', $messages_rooms->values()->all());

        $hourlyStatistics = $messages->groupBy(function ($message) {
            return $message->created_at->format('H');
        })->map->count();

        $hourly_chart = new HourlyChart();
        $hourly_chart->labels($hourlyStatistics->keys()->all());
        $hourly_chart->dataset('Godzina', 'bar', $messages_rooms->values()->all())
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        $weeklyStatistics = $messages->groupBy(function ($message) {
            return $message->created_at->translatedFormat('l'); // 'l' returns the full day name (e.g., Monday, Tuesday)
        })->map->count();

        $weekly_chart = new HourlyChart();
        $weekly_chart->labels($weeklyStatistics->keys()->all());
        $weekly_chart->dataset('Dnia tygodnia', 'bar', $messages_rooms->values()->all());

        $monthlyStatistics = $messages->groupBy(function ($message) {
            return $message->created_at->translatedFormat('F'); // 'F' returns the full month name (e.g., January, February)
        })->map->count();

        $monthly_chart = new HourlyChart();
        $monthly_chart->labels($monthlyStatistics->keys()->all());
        $monthly_chart->dataset('Miesiące', 'bar', $messages_rooms->values()->all());

        $investments = Investment::all()->pluck('id', 'name');

        return view('admin.crm.statistics.index', compact([
            'sources',
            'campaigns',
            'messages',
            'messages_campaigns',
            'campaigns_chart',
            'messages_sources',
            'sources_chart',
            'messages_rooms',
            'rooms_chart',
            'hourlyStatistics',
            'hourly_chart',
            'weeklyStatistics',
            'weekly_chart',
            'monthlyStatistics',
            'monthly_chart',
            'investments'
        ]));
    }

    public function rooms(Request $request){

        $investments = Investment::all()->pluck('id', 'name');

        $room = $request->input('room');
        $investmentId = $request->input('investment');

        $properties = Property::all(['id', 'name', 'status', 'rooms', 'investment_id', 'views', 'name']); // Retrieve all columns needed

        $allProperties = new Collection($properties); // Create a separate collection for all properties

        if ($request->filled('room')) {
            $filteredProperties = $allProperties->where('rooms', $room);
        } else {
            $filteredProperties = $allProperties;
        }

        if ($request->filled('investment')) {
            $filteredProperties = $filteredProperties->where('investment_id', $investmentId);
        }

        $topProperties = $filteredProperties->sortByDesc('views')->take(10)->values()->all();
        $lowestProperties = $filteredProperties->sortBy('views')->take(10)->values()->all();
        $uniqueRooms = $allProperties->pluck('rooms')->unique()->values()->all();
        $statusCounts = $filteredProperties->pluck('status')->countBy()->sortKeys();

        return view('admin.crm.statistics.rooms', compact('investments', 'uniqueRooms', 'statusCounts', 'topProperties', 'lowestProperties'));
    }
}
