<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Veterinarian;
use App\Models\News;
use Illuminate\Http\Request;
use App\Models\Order;

class PageController extends Controller
{


    public function pastAppointments()
    {
        return view('pages.past-appointments');
    }


    public function startAppointment(Appointment $appointment)
    {
        $appointments = Appointment::with(['user', 'pet', 'veterinarian.user', 'services'])
            ->whereHas('veterinarian', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->where('status', 'scheduled')
            ->whereDate('scheduled_at', now())
            ->get();
        $services = Service::with('items')->get();
        $selectedAppointmentId = $appointment->id;

        return view('pages.start-appointment', compact('appointments', 'services', 'selectedAppointmentId'));
    }
    public function selectAppointment()
    {
        $appointments = Appointment::with(['user', 'pet', 'veterinarian.user', 'services'])
            ->whereHas('veterinarian', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->where('status', 'scheduled')
            ->whereDate('scheduled_at', now())
            ->get();
        $services = Service::with('items')->get();

        return view('pages.start-appointment', compact('appointments', 'services'));
    }

    public function aboutMe()
    {
        $user = auth()->user();

        $pets = Pet::where('client_id', $user->id)->get();

        return view('pages.about', [
            'user' => $user,
            'pets' => $pets,
        ]);
    }
    public function myAppointments()
    {
        return view('pages.my-appointments');
    }

    public function index()
    {
        return view('home');
    }

    public function users(Request $request)
    {
        $search = $request->input('search');
        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        return view('pages.users', compact('users', 'search'));
    }

    public function orders()
    {
        $services = Service::with('items')->get();
        $orders = Order::with('item.service')->latest()->get();
        return view('pages.orders', compact('services', 'orders'));
    }

    public function pets(Request $request)
    {
        $search = $request->input('search');
        $query = Pet::with('client');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $pets = $query->get();
        $users = User::select('id', 'first_name', 'last_name', 'middle_name')->get();
        return view('pages.pets', compact('pets', 'search', 'users'));
    }

    public function appointments(Request $request)
    {
        $date = $request->input('date');
        $query = Appointment::with(['user', 'pet', 'veterinarian.user', 'services']);

        if ($date) {
            $query->whereDate('scheduled_at', $date);
        }

        $appointments = $query->get();
        $users = User::select('id', 'first_name', 'last_name', 'middle_name')->get();
        $pets = Pet::with('client')->get();
        $veterinarians = Veterinarian::with('user')->get();

        return view('pages.appointments', compact(
            'appointments',
            'date',
            'users',
            'pets',
            'veterinarians'
        ));
    }

    public function services()
    {
        $services = Service::with('items')->get();
        return view('pages.services', compact('services'));
    }
    public function veterinarians(Request $request)
    {
        $search = $request->input('search');
        $query = Veterinarian::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $veterinarians = $query->get();
        return view('pages.veterinarians', compact('veterinarians', 'search'));
    }

    public function changeRoles()
    {
        $users = User::all();
        return view('pages.change-roles', compact('users'));
    }

    public function news()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        return view('pages.news', compact('news'));
    }
    public function storePet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:100',
        ]);

        $user = auth()->user();

        Pet::create([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'age' => $request->age,
            'client_id' => $user->id,
        ]);

        return redirect()->route('about')->with('success', 'Питомец добавлен!');
    }
    public function updateProfileUser(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email'       => 'required|email|unique:users,email,' . auth()->id(),
            'phone'       => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
        ]);

        auth()->user()->update($validated);

        return response()->json(['message' => 'Данные обновлены']);
    }

}
