@extends

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Audit loge - {{ $timeLog->log_date }}</h1>
    <table class="w-full border">
          <thead>
            <tr>
                <th class="border p-2">Time</th>
                <th class="border p-2">Action</th>
                <th class="border p-2">Made By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $audit)
            <tr>
                <td class="border p-2">{{ $audit->created_at }}</td>
                <td class="border p-2">{{ $audit->action }}</td>
                <td class="border p-2">{{ $audit->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection