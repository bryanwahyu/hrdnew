<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Satuan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sats as $sat)
        <tr>
            <td>{{ $sat->id }}</td>
            <td>{{ $sat->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
