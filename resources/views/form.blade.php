@extends('statamic::layout')
@section('title', $isNew ? 'Create New Advanced Email' : 'Edit Advanced Email')


@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>
    <publish-form
        title="{{ $isNew ? 'Create New Advanced Email' : 'Edit Advanced Email' }}"
        action="{{ $isNew
                    ? cp_route('advanced-emails.store')
                    : cp_route('advanced-emails.update', $id) }}"
        :blueprint='@json($blueprint)'
        :values='@json($values)'
        :meta='@json($meta)'
    ></publish-form>
@endsection
