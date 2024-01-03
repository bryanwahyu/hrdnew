@extends('layout.admin')
@section('isi')
<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-12 form-horizontal form-row">
        <label>From</label>
        <div class="col-3">
            <input   type="date" id="from" class="form-control" onchange="change_from()">
        </div>
        <label> To</label>
        <div class="col-3">
            <input type="date" id="to" class="form-control" onchange="change_to()">
        </div>
            <button class="btn btn-success" onclick="print()">Print Excel </button>
     </div>
     <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    Laporan Stok
                </div>
                <div class="card-body">
                    <div class="col-12 mb-2">
                        <div class="table table-responsive">
                            <table id="data">
                                <thead>
                                    <th>Tipe</th>
                                    <th>Bahan</th>
                                    <th>Nama</th>
                                    <th>jumlah</th>
                                    <th>keterangan</th>
                                    <th>tanggal</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
     </div>
</div>

<script>
    let tabel=$("#data").DataTable()
    let urlgethistory=`${api}/v1/history?with=bahan`
    $.ajax({
        method:"GET",
        url:urlgethistory,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            res.data.forEach(item=>{
                tabel.row.add([
                    item.tipe,
                    item.bahan.name,
                    item.nama,
                    item.jumlah,
                    item.keterangan,
                    new Date(item.created_at).toLocaleString(),
                ]).draw(false)
            })
        }
    })
    function change_from(){
        let from =$('#from').val()
        let to=$("#to").val()
        let newurlgethistory=urlgethistory+`&from=${from}`
        if(to!=''){
            newurlgethistory=newurlgethistory+`&to=${to}`
        }
        $.ajax({
        method:"GET",
        url:newurlgethistory,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            tabel.clear().draw();
            res.data.forEach(item=>{

                tabel.row.add([
                    item.tipe,
                    item.bahan.name,
                    item.nama,
                    item.jumlah,
                    item.keterangan,
                    new Date(item.created_at).toLocaleString(),

                ]).draw(false)
            })
        }
    })
}
    function change_to(){
        let from =$('#from').val()
        let to=$("#to").val()
        let newurlgethistory=urlgethistory+`&to=${to}`
        if(from!=''){
            newurlgethistory=newurlgethistory+`&from=${from}`
        }
        $.ajax({
        method:"GET",
        url:newurlgethistory,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            tabel.clear().draw();
            res.data.forEach(item=>{

                tabel.row.add([
                    item.tipe,
                    item.bahan.name,
                    item.nama,
                    item.jumlah,
                    item.keterangan,
                    new Date(item.created_at).toLocaleString(),

                ]).draw(false)
            })
        }
    })
   }
   function print(){
    let from=$("#from").val()
    let to=$('#to').val()
    let downloadurl = `${api}/v1/export/excel/history`;

    const parameters = [];

    if (from !== '') {
        parameters.push(`from=${from}`);
    }

    if (to !== '') {
        parameters.push(`to=${to}`);
    }

    if (parameters.length > 0) {
        downloadurl += `?${parameters.join('&')}`;
    }
        $.ajax({
            method:"get",
            url:downloadurl ,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            responseType: 'arraybuffer', // Set the response type to 'arraybuffer'
            success:res=>{
                let blob = new Blob([res], { type: 'text/csv' });

                // Create a data URL representing the Blob
                var blobUrl = URL.createObjectURL(blob);

                // Create a temporary link element

                // Create a temporary link element
                var link = document.createElement('a');
                link.href = blobUrl;
                link.download = 'satuan_list.csv';

                // Append the link to the body and simulate a click
                document.body.appendChild(link);
                link.click();

                // Clean up: remove the link and revoke the Blob URL
                 document.body.removeChild(link);
                URL.revokeObjectURL(blobUrl);
            },
             error:res=>{
                 let error=res.responseJSON
                    if(error.code!=500){
                        alert(error.message)
                    }else{
                        alert("hubungin backend")
                    }
            }
        })
 }
</script>
@endsection
