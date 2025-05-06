<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Veterinarian;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Главная
    public function home()
    {
        return view('pages.home');
    }

    // Список клиентов
    public function clients()
    {
        $clients = Client::all();
        return view('pages.clients', compact('clients'));
    }

    // Список питомцев
    public function pets()
    {
        $pets = Pet::all();
        return view('pages.pets', compact('pets'));
    }

    // Список приёмов
    public function appointments()
    {
        // eager‐load всех нужных связей, включая services()
        $appointments = Appointment::with([
            'client',
            'pet',
            'veterinarian',
            'services',
        ])->get();

        return view('pages.appointments', compact('appointments'));
    }


    // Список услуг (процедур)
    public function services()
    {
        $services = Service::all();
        return view('pages.services', compact('services'));
    }

    // Список ветеринаров
    public function veterinarians()
    {
        $veterinarians = Veterinarian::all();
        return view('pages.veterinarians', compact('veterinarians'));
    }
}
