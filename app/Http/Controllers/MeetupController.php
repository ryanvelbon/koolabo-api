<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Meetup;
use App\Models\Rsvp;
use App\Http\Resources\MeetupResource;


class MeetupController extends Controller
{
    public function index(): JsonResource
    {
        $meetups = Meetup::query()
            ->when(request('organizer_id'), fn ($builder) => $builder->whereOrganizerId(request('organizer_id')))
            ->when(
                request('lat') && request('lng'),
                fn($builder) => $builder->nearestTo(request('lat'), request('lng')),
                fn($builder) => $builder->orderBy('id', 'ASC')
            )
            ->withCount(['rsvps' => fn($builder) => $builder->whereStatus(Rsvp::STATUS_ACCEPTED)]) // *PENDING* this attribute should be named 'n_attendees'
            ->paginate(20);

        return MeetupResource::collection(
            $meetups
        );
    }

    public function show($id)
    {
        return Meetup::find($id)->load('images', 'rsvps', 'comments');
    }

    public function store(Request $request)
    {
        // validate data
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'address_line1' => 'required|string',
            'start_time' => 'required|date|after:today',
            'end_time' => 'required|date|after:start_date'
        ]);

        $data = $request->all();
        $data['organizer_id'] = $request->user()->id;

        return Meetup::create($data);
    }

    public function update(Request $request, $id)
    {
        $meetup = Meetup::findOrFail($id);

        Gate::authorize('isOrganizer', $meetup);

        // *PENDING* validate data

        $meetup->update($request->all());

        return $meetup;
    }

    public function destroy($id)
    {
        $meetup = Meetup::findOrFail($id);

        Gate::authorize('isOrganizer', $meetup);

        return Meetup::destroy($id);
    }
}
