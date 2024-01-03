    <table>
        <thead>
        <tr>
            <th>Nama Bahan</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($dets as $det )
                <tr>
                    <td>{{$det->bahan->nama}}</td>
                    <td>{{$det->stok_order}}</td>
                    <td>{{$det->price}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
