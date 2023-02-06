<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Doc\Item as DocItemResource;
use App\Http\Resources\Book\Item as BookItemResource;
use App\Http\Resources\Book\Enquiry as BookEnquiryResource;
use App\Http\Resources\Event\Item as EventItemResource;

class Customer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dob' => ! empty($this->dob) ? $this->dob->format('d/m/Y') : $this->dob,
            'phone' => $this->phone,
            'exp' => $this->exp,
            'is_active' => $this->is_active,
            'gdpr' => $this->gdpr,
            'payment_request' => $this->payment_request,
            'documents' => DocItemResource::collection($this->docs->sortByDesc('created_at')),
            'books' => BookItemResource::collection($this->books->sortByDesc('created_at')),
            'enquiries' => BookEnquiryResource::collection($this->enquiries->sortByDesc('created_at')),
            'events' => EventItemResource::collection($this->events->sortBy('from')),
            $this->mergeWhen( ! empty($this->address), [
                'address' => $this->address,
            ]),
        ];
    }
}
