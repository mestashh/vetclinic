<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Veterinarian;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function clients(Request $request)
    {
        $search = $request->input('search');
        $query = Client::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $clients = $query->get();
        return view('pages.clients', compact('clients', 'search'));
    }

    public function pets(Request $request)
    {
        $search = $request->input('search');
        $query = Pet::with('client');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $pets = $query->get();
        return view('pages.pets', compact('pets', 'search'));
    }

    public function appointments(Request $request)
    {
        $date = $request->input('date');
        $query = Appointment::with(['client', 'pet', 'veterinarian', 'services']);

        if ($date) {
            $query->whereDate('scheduled_at', $date);
        }

        $appointments = $query->get();
        return view('pages.appointments', compact('appointments', 'date'));
    }

    public function services(Request $request)
    {
        $search = $request->input('search');
        $query = Service::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $services = $query->get();
        return view('pages.services', compact('services', 'search'));
    }

    public function veterinarians(Request $request)
    {
        $search = $request->input('search');
        $query = Veterinarian::query();

        if ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('specialty', 'like', "%{$search}%");
        }

        $veterinarians = $query->get();
        return view('pages.veterinarians', compact('veterinarians', 'search'));
    }
}
