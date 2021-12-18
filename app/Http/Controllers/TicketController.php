<?php

namespace App\Http\Controllers;

use App\Jobs\TicketJob;
use App\Libraries\CommonFunction;
use App\Models\Ticket;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TicketController extends Controller
{
    public function list()
    {
        try {
            $data = Ticket::orderBy('id', 'desc')->get();

            // success response
            return response()->json(
                CommonFunction::dataResponse('Ticket list.', 'TICKET-LIST', HTTPResponse::HTTP_OK, $data)
            );

        } catch (\Exception $e) {
            // exception generate & response
            return response()->json(
                CommonFunction::dataResponse($e->getMessage(), 'TICKET-LIST', HTTPResponse::HTTP_BAD_REQUEST)
            );
        }
    }

    public function store(Request $request)
    {
        try {
            // input validation
            $rules = [
                'from_counter' => 'required',
                'to_counter' => 'required',
                'amount' => 'required'
            ];

            $validation = CommonFunction::inputValidationCheck($request->all(), $rules, 'TICKET-STORE');
            if (!$validation['validation']) {
                return $validation['error'];
            }

            // Store ticket
            Queue::push(new TicketJob($request->all()));

            // success response
            return response()->json(
                CommonFunction::dataResponse('Successfully store ticket.', 'TICKET-STORE', HTTPResponse::HTTP_OK)
            );

        } catch (\Exception $e) {
            // exception generate & response
            return response()->json(
                CommonFunction::dataResponse($e->getMessage(), 'TICKET-STORE', HTTPResponse::HTTP_BAD_REQUEST)
            );
        }
    }
}
