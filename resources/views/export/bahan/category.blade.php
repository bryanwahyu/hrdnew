<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Category</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cats as $cat)
        <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
