<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class CampaignsController extends Controller
{
    public function index()
    {
        $campaigns =  Campaign::orderBy('title')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($campaign) => [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'description' => $campaign->description,
                'start_date' => $campaign->start_date,
                'end_date' => $campaign->end_date,
                'amount' => $campaign->amount,
                'deleted_at' => $campaign->deleted_at,
            ]);
        return Inertia::render('Campaigns/Index', [
            'filters' => Request::all('search', 'trashed'),
            'campaigns' => $campaigns,
        ]);
    }

    public function create()
    {
        return Inertia::render('Campaigns/Create');
    }

    public function store()
    {
        Campaign::create(
            Request::validate([
                'title' => ['required', 'max:256'],
                'description' => ['nullable'],
                'start_date' => ['required', 'date_format:Y-m-d'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
                'amount' => ['required', 'min:0'],
            ])
        );

        return Redirect::route('campaigns')->with('success', 'Campaign created.');
    }

    public function edit(Campaign $campaign)
    {
        return Inertia::render('Campaigns/Edit', [
            'campaign' => [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'description' => $campaign->description,
                'start_date' => $campaign->start_date,
                'end_date' => $campaign->end_date,
                'amount' => $campaign->amount,
                'deleted_at' => $campaign->deleted_at,
            ],
        ]);
    }

    public function update(Campaign $campaign)
    {
        $campaign->update(
            Request::validate([
                'title' => ['required', 'max:256'],
                'description' => ['nullable'],
                'start_date' => ['required', 'date_format:Y-m-d'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
                'amount' => ['required', 'min:0'],
            ])
        );

        return Redirect::route('campaigns')->with('success', 'Campaign updated.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return Redirect::route('campaigns')->with('success', 'Campaign deleted.');
    }
}
