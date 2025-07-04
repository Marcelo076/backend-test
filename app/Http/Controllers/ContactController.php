<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactsRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::paginate(10);
        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }
    public function store(ContactsRequest $request)
    {
        $data = $request->validated();
        $data['phone'] = preg_replace('/\D/', '', $data['phone']);

        $contact = Contact::create($data);

        return response()->json($contact, 200)->header('X-Inertia', 'false');
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|min:3',
            'email' => "required|email|unique:contacts,email,{$id}",
            'phone' => ['required', 'regex:/^\(?\d{2}\)? ?\d{4,5}-?\d{4}$/'],
        ]);

        $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);

        $contact->update($validated);

        return response()->json($contact)->header('X-Inertia', 'false');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        Mail::raw("Olá {$contact->name}, seu contato foi removido do nosso sistema.", function ($message) use ($contact) {
            $message->to($contact->email)
                ->subject('Contato removido');
        });

        $contact->delete();

        return response()->json(['message' => 'Contato removido com sucesso.'])->header('X-Inertia', 'false');
    }

    public function create()
    {
        return Inertia::render('Contacts/Create');
    }
}
