@extends('layout.admin')

@section('isi')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Daftar PO</div>
            <div class="card-body">
                <div class="col-12 my-3">
                    <div class="form-group form-row">
                        <label  class="col-3"> Kode PO</label>
                        <div class="col-8">
                            <input type="text" id="kode-po" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-3">Supplier</label>
                        <div class="col-8">
                            <select id="supplier-po" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label class="col-3">P.I.C </label>
                        <div class="col-8">
                            <input type="text" id="client-po" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label  class="col-3">Alamat</label>
                        <div class="col-8">
                        <textarea id="alamat-po" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h1>list item </h1>
                    <div class="col-12 my-3">
                        <button class="btn btn-primary" onclick="show_modal()">Tambah</button>
                    </div>
                    <div class="col-12 table-responsive" >
                        <table id="table">
                            <thead>
                                <th>Bahan</th>
                                <th>jumlah pesanan</th>
                                <th>Harga</th>
                                <th>Action</th>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" onclick="send_to_server()">Buat</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pesanan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return add_pesanan();">
                <div class="form-group form-row">
                    <label  class="col-2">Bahan</label>
                    <div class="col-8">
                        <select id="add-bahan-id" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2"> Order</label>
                    <div class="col-3">
                        <input type="text" id="add-jumlah-pesanan" class="form-control">
                    </div>
                    <label class="col-2">harga</label>
                    <div class="col-3">
                        <input type="text" id="add-harga" class="form-control">
                    </div>
                </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save changes</button>
         </form>
        </div>
      </div>
    </div>
  </div>
<script>
    function show_modal(){
        $('#tambah-modal').modal('show')
    }
    let table=$('#table').DataTable()
    let details=[]
    let sequence=0
    $(document).ready(function (){
        $("#supplier-po").select2({
            ajax:{
                url:`${api}/v1/supplier`,
                headers:{
                        Authorization:`Bearer ${localStorage.getItem('token')}`
                    },
                processResults:function(res){
                    let items=[]
                    res.data.forEach(e => {
                        let item={}
                        item.id=e.id
                        item.text=e.nama
                        items.push(item)
                    });
                    return {
                        results:items
                    }
                }
            }
        })
        $("#add-bahan-id").select2({
            ajax:{
                url:`${api}/v1/bahan`,
                headers:{
                        Authorization:`Bearer ${localStorage.getItem('token')}`
                    },
                processResults:function(res){
                    console.log(res.data)
                    let items=[]
                    res.data.forEach(e => {
                        let item={}
                        item.id=e.id
                        item.text=e.name+' '+e.sku

                        items.push(item)
                    });
                    return {
                        results:items
                    }
                }
            }
        })
    })

    function add_pesanan(){
        let data={}
        data.bahan_id=$("#add-bahan-id").val()
        data.price=$('#add-harga').val()
        data.stok_order=$('#add-jumlah-pesanan').val()
        $.ajax({
            method:"Get",
            url:`${api}/v1/bahan/${data.bahan_id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                sequence++
                let bahan=res.data
                data.bahan=bahan
                data.sequence=sequence
                details.push(data)
                let action=`<button class="btn btn-danger" onclick="delete_details(${data.sequence})"> hapus</button>`
                table.row.add([
                    data.bahan.name,
                    data.stok_order,
                    data.price,
                    action
                ]).draw(false)

                $('#tambah-modal').modal('hide')
            }
        })
        return false
    }
    function delete_details(id){
            sequence=0
            details=details.filter(ele=>{
                return ele.sequence!=id
            })
            console.log(details)
            table.clear().draw()
            details.forEach(items => {
                sequence++;
                items.sequence=sequence
                let action=`<button class="btn btn-danger" onclick="delete_details(${items.sequence})"> hapus</button>`
                table.row.add([
                    items.bahan.name,
                    items.stok_order,
                    items.price,
                    action
                ]).draw(false)
            });

            return false;
    }
    function send_to_server(){
        let data={}
        data.details=details
        data.client=$("#client-po").val()
        data.supplier_id=$("#supplier-po").val()
        data.code=$("#kode-po").val()
        data.alamat=$("#alamat-po").val()
        console.log(data)
        $.ajax({
            method:"POST",
            url:`${api}/v1/purchase-order`,
            contentType:"Application/json",
            data:JSON.stringify(data),
            headers:{
                Authorization:`Bearer ${localStorage.getItem("token")}`
            },
            success:res=>{
                alert('data po berhasil  dibuat ');
                window.location.replace(`${url}/po`)
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


</script>
@endsection
