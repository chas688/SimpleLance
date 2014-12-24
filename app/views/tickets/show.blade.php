@extends('layouts.default')

@section('content')
    <h4>Support Ticket</h4>

    <div class="well clearfix">
        <div class="col-md-8">
            <p><strong>Title</strong>: {{ $ticket->title }}</p>
            <p><strong>Description</strong>: {{ $ticket->description }}</p>
            <p><strong>Priority</strong>: {{ $ticket->priority->title }}</p>
            <p><strong>Owner</strong>: {{ $ticket->owner->username }}</p>

        </div>
        <div class="col-md-4">
            <p><em>Ticket created: {{ $ticket->created_at }}</em></p>
            <p><em>Last Updated: {{ $ticket->updated_at }}</em></p>
            <button id="edit-{{ $ticket->id }}" class="btn btn-primary" onClick="location.href='{{ action('TicketsController@edit', array($ticket->id)) }}'">Edit Ticket</button>
        </div>
        <div class="col-md-2">
            {{ Form::open(array(
                 'action' => array('TicketsController@destroy', $ticket->id),
                 'method' => 'delete',
                 'class' => $ticket->id . '-delete',
                 'id' => $ticket->id . '-delete',
                 'name' => $ticket->id . '-delete',
                 'role' => ''
                 )) }}

            {{ Form::submit('Delete', ['class' => 'btn btn-danger', 'id' => 'delete-' . $ticket->id])}}
            {{ Form::close() }}
        </div>
    </div>
@stop
