@extends('layouts.mini')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>Villkor</h1>

            <ul>
                <li>Uppgifter du lämnar till oss hålls privata tills dess du önskar göra något publikt.</li>
                <li>Dina uppgifter kommer inte säljas vidare till tredjepart.</li>
                <li>Vi kan komma att skicka e-post till dig med nyheter om tjänsten, t.ex. om vi lanserar skarpt,
                    släpper nya funktioner, om någon går med i dina träningspass, eller om våra villkor uppdateras.
                </li>
                <li>Vi använder oss av cookies för att kunna erbjuda tjänsten.</li>
                <li>Vi använder oss av cookies för att kunna erbjuda tjänsten.</li>
            </ul>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-success btn-lg center-block"
               href="{{ route('register') }}">{{ __('user.create_account') }}</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-default btn-lg center-block" href="{{ route('login') }}">{{ __('user.login') }}</a>
        </div>
    </div>

@endsection