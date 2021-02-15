<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Gender</th>
        <th>Status</th>
        <th>Post Count</th>
        <th>Blocked_at</th>
        <th>created Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($record as $records)
        <tr>
            <td>{{ $records->name }}</td>
            <td>{{ $records->email }}</td>
            <td>{{ $records->tel_code}} {{$records->phone}}</td>
            @if($records->gender ==1)<td>Male</td>@else<td>Female</td>@endif
            @if($records->active ==1)<td>Active</td>@else<td>Inactive</td>@endif
            <td>{{ count($records->posts) }}</td>
            <td>{{ $records->blocked_at }}</td>
            <td>{{ $records->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>