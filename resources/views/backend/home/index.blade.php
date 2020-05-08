@extends('backend.master')

@section('title')
Homepage
@endsection

@section('content-header')
<h1 class="m-0 mb-2 text-dark">Trang chủ</h1>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-striped table-light">
        <thead class="bg-lightblue">
            <tr>
                <th>Commit</th>
                <th>Author</th>
                <th>Datetime</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commits as $item)
                <tr>
                    <td>
                        <strong>{{ $item['commit']['message'] }}</strong>
                        <br>
                        <small class="text-secondary">{{ $item['sha'] }}</small>
                    </td>
                    <td>{{ $item['commit']['author']['name'] }}</td>
                    <td>{{ $item['commit']['author']['date'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
