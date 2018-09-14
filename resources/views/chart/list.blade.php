<div class="table-responsive">
    <table class="table table-borderless table-striped">
        <thead>
        <tr>
            <th>Nom et prénom</th>
            <th class="d-none">Garderie</th>
            <th class="text-right">Rempl.</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topUsers as $top)
            <tr>
                <td><a href="{{route('users.show', $top->id)}}">{{$top->name}}</a></td>
                <td class="d-none"><a href="{{route('nurseries.show', $top->nursery->id ?? 0)}}">{{$top->nursery->name ?? '-'}}</a></td>
                <td class="text-right">{{$top->bookings->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
