<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'post_title' => 'required|string|max:255',
            'page_content' => 'required|string',
            'image' => 'required|url',
            'image_wide' => 'required|url',
            'minimal_prices' => 'required|json',
        ]);

        Service::create($validated);

        return redirect()->route('services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Service $service
     * @return Application|Factory|View
     */
    public function show(Service $service): Application|Factory|View
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Service $service
     * @return Application|Factory|View
     */
    public function edit(Service $service): Application|Factory|View
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Service $service
     * @return RedirectResponse
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'post_title' => 'required|string|max:255',
            'page_content' => 'required|string',
            'image' => 'required|url',
            'image_wide' => 'required|url',
            'minimal_prices' => 'required|json',
        ]);

        $service->update($validated);

        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $service
     * @return RedirectResponse
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('services.index');
    }
}
