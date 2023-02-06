<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Certificate\Send as CertificateSendRequest;
use App\Http\Resources\Document\Report as DocumentReportResource;

class CertificateController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Doc')->reports();
        return response()->result(DocumentReportResource::collection($result));
    }

    public function send(CertificateSendRequest $request)
    {
        $input = $request->only(['firstname', 'lastname', 'date', 'coach', 'book_id', 'id',
            'prevCourse', 'startDate', 'endDate', 'moto', 'course', 'summary',
            'areas', 'comments', 'road', 'planning', 'control', 'general',
            'cornering', 'urban', 'overtalking']);
        $result = service('Pdf')->generate($input);
        return response()->result($result);
    }
}
