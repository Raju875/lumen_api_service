<?php

namespace App\Jobs;

use App\Libraries\CommonFunction;
use App\Models\Ticket;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TicketJob extends Job
{
    /**
     * Ticket array
     *
     * @var array
     */
    protected $ticket;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            Ticket::create($this->ticket);

        } catch (\Exception $e) {
            // exception generate & response
            return response()->json(
                CommonFunction::dataResponse($e->getMessage(), 'TICKET-STORE', HTTPResponse::HTTP_BAD_REQUEST)
            );
        }
    }
}
