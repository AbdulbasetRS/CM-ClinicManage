@extends('admin.structure')

@section('title', __('admin.appointments'))

@section('main.script')
    {{-- Scripts are handled by the component --}}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.appointments') }}</h4>
                    </div>

                    <div class="card-body">
                        {{-- Use the reusable component --}}
                        <x-admin.patient-appointments-table-component />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
