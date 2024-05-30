<?php

namespace App\Services;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactService 
{
    public function getPaginated(Request $request)
    {
        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;

        $contacts = Contact::query();

        $contacts->where('user_id', Auth::id());
        
        if ($request->query('search_key')) {
            $contacts->where(function ($query) use ($request) {
                $query->where("name", 'LIKE', "%" . $request->query('search_key') . "%");
                $query->orWhere("company", 'LIKE', "%" . $request->query('search_key') . "%");
                $query->orWhere("phone", 'LIKE', "%" . $request->query('search_key') . "%");
                $query->orWhere("email", 'LIKE', "%" . $request->query('search_key') . "%");
            });
        }

        return $contacts->paginate($per_page);
    }


    public function create(ContactRequest $request)
    {
        $contact = Contact::create([
            ...$request->toArray(),
            'user_id' => $request->user()->id,
        ]);

        return $contact;
    }

    public function getContact($id)
    {
        return Contact::find($id);
    }

    public function update(ContactRequest $request, $id)
    {
        $contact = $this->getContact($id);

        $contact->update($request->toArray());

        return $contact->fresh();
    }

    public function delete($id)
    {
        $contact = $this->getContact($id);
        
        $contact->delete();

        return null;
    }
}