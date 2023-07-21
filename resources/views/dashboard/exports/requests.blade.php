<table>
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Username') }}</th>
        <th>{{ __('Service') }}</th>
        <th>{{ __('Date') }}</th>
        <th>{{ __('Start Time') }}</th>
        <th>{{ __('End Time') }}</th>
        <th>{{ __('Notes') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Reason in rejection') }}</th>
        <th>{{ __('Created at') }}</th>
    </tr>
    </thead>
    <tbody>
    @php
        $status = [
        1     => __('On hold'),
        2     => __('Under Processing'),
        3     => __('Approved'),
        4     => __('Rejected'),
        5     => __('Delayed')
    ];
    @endphp
    @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->user->name }}</td>
            <td>{{ $ticket->service->name }}</td>
            <td>{{ $ticket->date }}</td>
            <td>{{ $ticket->start_time }}</td>
            <td>{{ $ticket->end_time }}</td>
            <td>{{ $ticket->notes }}</td>
            <td>{{ $status[$ticket->status] }}</td>
            <td>{{ $ticket->reason }}</td>
            <td>{{ $ticket->created_at->format('d-M-Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
