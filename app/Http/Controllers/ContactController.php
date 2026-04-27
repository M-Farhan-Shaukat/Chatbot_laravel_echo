<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contacts.add');
    }

    public function store(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->with('error', 'User not registered on system');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot add yourself');
        }

        // Custom name: use input name > contact's real name > phone number
        $name = trim($request->name ?? '');
        if (!$name) $name = $user->name !== 'User' ? $user->name : $user->phone;

        Contact::updateOrCreate(
            ['user_id' => auth()->id(), 'contact_id' => $user->id],
            ['name' => $name]
        );

        return redirect('/app')->with('success', 'Contact added');
    }

    public function index(Request $request)
    {
        $query = Contact::where('user_id', auth()->id())->with('contactUser');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhereHas('contactUser', fn($q2) => $q2->where('name', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%"));
            });
        }

        $contacts = $query->get();
        return view('contacts.list', compact('contacts'));
    }
}
