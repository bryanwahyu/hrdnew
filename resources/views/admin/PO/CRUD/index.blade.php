@extends('layout.admin')

@section('isi')

<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-12 my-3">
        <button class="btn btn-success" onclick="create_PO()">Buat PO </button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">Daftar PO</div>
            <div class="card-body">
                <div class="col-12">
                    <table id="data">
                        <thead>
                            <th>Supplier</th>
                            <th>kode</th>
                            <th>PIC</th>
                            <th>jumlah</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function create_PO(){
        window.location.replace(`${url}/po/create`);
    }
    let table=$("#data").DataTable()
    function getStatus(code){
        const status=['PO Telah dibuat','PO Telah Di Approved',"PO Telah Di Tolak",'KIRIM PO KE SUPPLIER','Supplier on Delivered','Supplier On Check','PO Close','PO Selesai']
        return status[code]
    }
    function getAction(code,id){
        const action=[
            `
            <div class="btn-group">
             <button class="btn btn-primary" onclick="change(${id},'approval')">Approved</button>
             <button class="btn btn-warning" onclick="change(${id},'rejection')">Rejected</button>
             <button class="btn btn-success" onclick="detail(${id})">detail</button>
             <button class="btn btn-danger" onclick="hapus(${id})">Hapus PO</button>
            </div>
             `,
            `
            <div class='btn-group'>
                <button class="btn btn-primary" onclick="change(${id},'request')">Request</button>
                <button class="btn btn-warning" onclick="change(${id},'rejection')">Rejected</button>
                <button class="btn" onclick="detail(${id})">detail</button>
                <button class="btn btn-danger" onclick="hapus(${id})">Hapus PO</button>
            </div>
             `,
             `
             <div class='btn-group'>
                <button class="btn btn-primary" onclick="change(${id},'completed')">Completed</button>
                <button class="btn" onclick="detail(${id})">detail</button>
                <button class="btn btn-danger" onclick="hapus(${id})">Hapus PO</button>
            </div>

             `,
             `
            <div class="btn-group">
                 <button class="btn btn-primary" onclick="change(${id},'send')">Pengiriman</button>
                 <button class="btn btn-warning" onclick="change(${id},'rejection')">Rejected</button>
                 <button class="btn" onclick="detail(${id})">detail</button>
                 <button class="btn btn-danger" onclick="hapus(${id})">Hapus PO</button>
             </div>
             `,
             `
             <div class="btn-group">
                 <button class="btn btn-primary" onclick="change(${id},'check')">Supplier sudah sampai</button>
                 <button class="btn" onclick="detail(${id})">detail</button>
             </div>
             `,
            `
             <div class="btn-group"
                <button class="btn btn-primary" onclick="change(${id},'close')">Close</button>
                <button class="btn" onclick="detail(${id})">Detail</button>
             </div>
                `,
            `

                 <button class="btn" onclick="detail(${id})">detail</button>
            `,
        ]
        return action[code]
    }
    function hapus(id){
        $.ajax({
            method:"DELETE",
            url:`${api}/v1/purchase-order/${id}`,
            headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert("PO sudah di hapus ")
                window.location.reload()
            },
            error:res=>{
               let  error=res.responseJSON
                if(error.code!=500){
                    alert(error.message)
                }else{
                    alert("hubungin backend")
                }
            }
        })
    }
    function detail(id){
        window.location.replace(`${url}/po/${id}`)
    }
    function change(id,status){
        $.ajax({
            method:"Patch",
            url:`${api}/v1/purchase-order/${id}/${status}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert('status telah diubah')
                window.location.reload()
            },
            error:res=>{
               let  error=res.responseJSON
                if(error.code!=500){
                    alert(error.message)
                }else{
                    alert("hubungin backend")
                }
            }


        })
    }
    $.ajax({
        method:"GET",
        url:`${api}/v1/purchase-order?with=supply&count=detail`,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            res.data.forEach(item =>{
                let status=getStatus(item.status)
                let action=getAction(item.status,item.id)
                table.row.add([
                item.supply.nama,
                item.code,
                item.client,
                item.detail_count,
                status,
                action
                ]).draw(false)
               })
        }
    })
</script>
@endsection
