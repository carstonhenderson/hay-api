<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entry;
use App\Http\Resources\Entry as EntryResource;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EntryResource::collection(auth()->user()->entries->sortByDesc('created_at'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'mood' => 'required',
            'notes' => 'nullable'
        ]);

        $entry = auth()->user()->entries()->create($attributes);

        return new EntryResource(Entry::find($entry->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function show(Entry $entry)
    {
        $this->authorize('owns', $entry);

        return new EntryResource($entry);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entry $entry)
    {
        $this->authorize('owns', $entry);

        $attributes = $request->validate([
            'mood' => 'required',
            'notes' => 'nullable'
        ]);

        $entry->update($attributes);

        return new EntryResource($entry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entry $entry)
    {
        $this->authorize('owns', $entry);

        $entry->delete();

        return true;
    }
}
