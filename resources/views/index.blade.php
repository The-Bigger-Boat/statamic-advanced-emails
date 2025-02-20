@extends('statamic::layout')
@section('title', 'Advanced Emails')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>Advanced Emails</h1>
        <a href="{{ cp_route('advanced-emails.create') }}" class="btn-primary">Create New</a>
    </div>

    <div class="card overflow-hidden p-0 relative">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Form</th>
                    <th>Recipient(s)</th>
                    <th>Field</th>
                    <th>Operator</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
            @foreach($entries as $id => $entry)
                <tr>
                    <td><a href="{{ cp_route('advanced-emails.edit', $id) }}">{{ $entry['form'] }}</a></td>
                    <td>{{ $entry['to'] }}</td>
                    <td>{{ $entry['field'] }}</td>
                    <td>{{ $entry['operator'] }}</td>
                    <td>{{ $entry['value'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
