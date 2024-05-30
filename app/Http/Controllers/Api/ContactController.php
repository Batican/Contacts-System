<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    public function __construct(private ContactService $contactService)
    {
    }

    public function index(Request $request)
    {
        return $this->sendResponse(
            $this->contactService->getPaginated($request),
            'Contacts fetched successfully'
        );
    }

    public function store(ContactRequest $request)
    {
        return $this->sendResponse(
            $this->contactService->create($request),
            'Contact created successfully'
        );
    }

    public function show(string $id)
    {
        return $this->sendResponse(
            $this->contactService->getContact($id),
            'Contact fetched successfully'
        );
    }

    public function update(ContactRequest $request, string $id)
    {
        return $this->sendResponse(
            $this->contactService->update($request, $id),
            'Contact updated successfully'
        );
    }

    public function destroy(string $id)
    {
        return $this->sendResponse(
            $this->contactService->delete($id),
            'Contact deleted successfully'
        );
    }
}
