@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="w-full ml-2 mr-2 md:w-2/3 md:mx-auto">
            <h2 class="text-3xl mb-8">{{ $faction->Name }}</h2>
            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md w-1/3">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Mitglieder
                </div>

                <table class="table w-full">
                    <tr>
                        <th>Name</th>
                        <th>Rang</th>
                    </tr>
                    @foreach($faction->members()->with('user')->orderBy('FactionRank', 'DESC')->get() as $character)
                        <tr>
                            <td><a href="{{ route('users.show', [$character->Id]) }}">{{ $character->user->Name }}</a></td>
                            <td>{{ $character->FactionRank }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection