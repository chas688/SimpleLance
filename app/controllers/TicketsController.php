<?php

class TicketsController extends \BaseController {

	protected $ticket;

	public function __construct(Ticket $ticket, User $user, Priority $priority, Status $status, TicketReply $ticketreply)
	{
		$this->ticket = $ticket;
		$this->user = $user;
		$this->priority = $priority;
		$this->status = $status;
		$this->replies = $ticketreply;
	}

	/**
	 * Display a listing of the resource.
	 * GET /tickets
	 *
	 * @return Response
	 */
	public function index()
	{
		$tickets = $this->ticket->with('owner')->get();

		return View::make('tickets.index')
		           ->with('tickets', $tickets);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tickets/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$owners = $this->user->getOwners();
		$priorities = $this->priority->getPriorities();
		$statuses = $this->status->getStatuses();

		return View::make('tickets.create')
			->with('owners', $owners)
			->with('priorities', $priorities)
			->with('statuses', $statuses);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tickets
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();

		$rules = array(
			'title' => 'required',
			'description' => 'required',
			'priority_id' => 'required',
			'status_id' => 'required',
			'owner_id' => 'required'
		);

		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

			return Redirect::route('tickets.create')
				->withErrors($validator)
				->withInput($input);
		} else {
			$ticket = $this->ticket->create($input);

			return Redirect::route('tickets.index')->with('flash', [
				'class' => 'success',
				'message' => 'Ticket Created.'
			]);
		}
	}

	/**
	 * Display the specified resource.
	 * GET /tickets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ticket = $this->ticket->find($id);

		return View::make('tickets.show')
		           ->with('ticket', $ticket);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /tickets/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$thisTicket = $this->ticket->find($id);
		$owners = $this->user->getOwners();
		$priorities = $this->priority->getPriorities();
		$statuses = $this->status->getStatuses();

		return View::make('tickets.edit')
			->with('ticket', $thisTicket)
			->with('owners', $owners)
			->with('priorities', $priorities)
			->with('statuses', $statuses);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /tickets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();

		$rules = array(
			'title' => 'required',
			'description' => 'required',
			'priority_id' => 'required',
			'status_id' => 'required',
			'owner_id' => 'required'
		);

		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

			return Redirect::route('tickets.edit', $id)
			               ->withErrors($validator)
			               ->withInput($input);
		} else {

			$ticket = $this->ticket->find($id);
			$ticket->title = $input['title'];
			$ticket->description = $input['description'];
			$ticket->priority_id = $input['priority_id'];
			$ticket->status_id = $input['status_id'];
			$ticket->owner_id = $input['owner_id'];

			$ticket->save();

			return Redirect::route('tickets.index')->with('flash', [
				'class' => 'success',
				'message' => 'Ticket Updated.'
			]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tickets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if ($this->ticket->destroy($id))
		{
			Session::flash('success', 'Ticket Deleted');
		} else {
			Session::flash('error', 'Unable to Delete Ticket');
		}

		return Redirect::action('TicketsController@index');
	}

}