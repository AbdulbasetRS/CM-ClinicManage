@extends('admin.structure')

@section('title', __('admin.visits'))

@section('main.script')
    {{-- Scripts are handled by the component --}}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.visits') }}</h4>
                    </div>

                    <div class="card-body">
                        {{-- Use the reusable component --}}
                        <x-admin.patient-visits-table-component />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
