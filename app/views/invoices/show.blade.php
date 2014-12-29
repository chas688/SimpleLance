@extends('layouts.default')

@section('content')
    <h4>Invoice</h4>

    <div class="well clearfix">
        <div class="col-md-8">
            <p><strong>Title</strong>: {{ $invoice->title }}</p>
            <p><strong>Due Date</strong>: {{ $invoice->due }}</p>
            <p><strong>Status</strong>: {{ $invoice->status->title }}</p>
            <p><strong>Owner</strong>: {{ $invoice->owner->username }}</p>

        </div>
        <div class="col-md-4">
            <p><em>Invoice created: {{ $invoice->created_at }}</em></p>
            <p><em>Last Updated: {{ $invoice->updated_at }}</em></p>
            <button id="edit-{{ $invoice->id }}" class="btn btn-primary" onClick="location.href='{{ action('InvoicesController@edit', array($invoice->id)) }}'">Edit Invoice</button>
        </div>
        <div class="col-md-2">
            {{ Form::open(array(
                 'action' => array('InvoicesController@destroy', $invoice->id),
                 'method' => 'delete',
                 'class' => $invoice->id . '-delete',
                 'id' => $invoice->id . '-delete',
                 'name' => $invoice->id . '-delete',
                 'role' => ''
                 )) }}

            {{ Form::submit('Delete', ['class' => 'btn btn-danger', 'id' => 'delete-' . $invoice->id])}}
            {{ Form::close() }}
        </div>
    </div>
@stop
