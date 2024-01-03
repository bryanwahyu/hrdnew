<table>
    <thead>
    <tr>

        <th>Code</th>
        <th>Nama_Bahan</th>
        <th>Satuan</th>
        <th>Category</th>
        <th>Stok_saat_ini</th>
        <th>Stok</th>
        <th>Nama_pegawai</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{$item->sku}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->satuan->name}}</td>
                <td>{{$item->category->name}}</td>
                <td>{{$item->stok}}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
