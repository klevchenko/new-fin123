@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <ul data-test="123456" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Home</a>
                            </li>
                             <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-partner-tab" data-toggle="pill" href="#pills-partner" role="tab" aria-controls="pills-partner" aria-selected="true">Партнери</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-courses-tab" data-toggle="pill" href="#pills-courses" role="tab" aria-controls="pills-courses" aria-selected="false">Курси</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Options</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <transactions-data></transactions-data>
                            </div>
                            <div class="tab-pane fade" id="pills-courses" role="tabpanel" aria-labelledby="pills-courses-tab">
                                <courses></courses>
                            </div>
                             <div class="tab-pane fade" id="pills-partner" role="tabpanel" aria-labelledby="pills-partner-tab">
                                 <partners></partners>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <transactions-clear-list></transactions-clear-list>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
