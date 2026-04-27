<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // show add contact page
    public function create()
    {
        return view('contacts.add');
    }

    // store contact
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        // 1. check if user exists
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->with('error', 'User not registered on system');
        }

        // 2. add contact
        Contact::firstOrCreate([
            'user_id' => auth()->id(),
            'contact_id' => $user->id
        ]);

        return redirect('/app')->with('success', 'Contact added');
    }

    // show contact list
    public function index()
    {
        $contacts = Contact::where('user_id', auth()->id())
            ->with('contactUser')
            ->get();

        return view('contacts.list', compact('contacts'));
    }
}
