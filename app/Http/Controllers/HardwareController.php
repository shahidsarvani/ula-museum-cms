<?php

namespace App\Http\Controllers;

use App\Models\Hardware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HardwareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hardwares = Hardware::whereStatus(1)->get();
        return view('hardwares.index', compact('hardwares'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('hardwares.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $data = $request->except('_token');
            Hardware::create($data);
            return redirect()->route('hardwares.index')->with('success', 'Hardware Added!');
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th->getMessage());
            return redirect()->route('hardwares.index')->with('error', 'Error: Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function show(Hardware $hardware)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function edit(Hardware $hardware)
    {
        //
        return view('hardwares.edit', compact('hardware'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hardware $hardware)
    {
        //
        try {
            $data = $request->except('_token', '_method');
            $hardware->update($data);
            return redirect()->route('hardwares.index')->with('success', 'Hardware Updated!');
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th->getMessage());
            return redirect()->route('hardwares.index')->with('error', 'Error: Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hardware $hardware)
    {
        //
        try {
            $hardware->delete();
            return redirect()->route('hardwares.index')->with('success', 'Hardware deleted!');
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th->getMessage());
            return redirect()->route('hardwares.index')->with('error', 'Error: Something went wrong!');
        }
    }
}
