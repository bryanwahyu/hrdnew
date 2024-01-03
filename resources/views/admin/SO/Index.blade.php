@extends('layout.admin')
@section('isi')
<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-12 my-3">
        <button class="btn btn-success" onclick="tambah()">Tambah</button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">Daftar Sales Order </div>
            <div class="card-body">
                <div class="col-12">
                    <table id="data">
                        <thead>
                            <th>Customer</th>
                            <th>Bahan</th>
                            <th>tanggal kirim</th>
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

<div class="modal fade" id="tambah-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return add_order();">
                <div class="form-group form-row">
                    <label class="col-2">Customer : </label>
                    <div class="col-8">
                        <select class="form-control" id="add-customer"></select>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Bahan</label>
                    <div class="col-8">
                        <select class="form-control" id="add-bahan"></select>
                    </div>
                </div>

                <div class="form-group form-row">
                    <label class="col-2">Jumlah</label>
                    <div class="col-8">
                        <input type="text"  id="add-jumlah" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Tanggal Pemesanan</label>
                    <div class="col-8">
                        <input type="date"  id="add-tanggal" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Deskripsi</label>
                    <div class="col-8">
                        <textarea id="add-deskripsi" cols="30" rows="10" class="form-control"></textarea>
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

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return edit_order();">
                <input type="hidden" id="id">
                <div class="form-group form-row">
                    <label class="col-2">Customer : </label>
                    <div class="col-8">
                        <select class="form-control" id="edit-customer"></select>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Bahan</label>
                    <div class="col-8">
                        <select class="form-control" id="edit-bahan"></select>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Tanggal Pemesanan</label>
                    <div class="col-8">
                        <input type="date"  id="edit-tanggal" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Deskripsi</label>
                    <div class="col-8">
                        <textarea id="edit-deskripsi" cols="30" rows="10" class="form-control"></textarea>
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
        let data=$("#data").DataTable()
        $.ajax({
        })
        function tambah(){
            $("#tambah-modal").modal('show')

        $("#add-customer").select2({
            ajax:{
                url:`${api}/v1/customer`,
                headers:{
                        Authorization:`Bearer ${localStorage.getItem('token')}`
                    },
                processResults:function(res){
                    let items=[]
                    console.log(res.data)
                    res.data.forEach(e => {
                        let item={}
                        item.id=e.id
                        item.text=e.name+' '+e.mobile_phone
                        items.push(item)
                    });
                    return {
                        results:items
                    }
                }
            }
        })
        $("#add-bahan").select2({
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
        }
        function add_order(){
            let data={}
            data.bahan_id=$("#add-bahan").val()
            data.customer_id=$("#add-customer").val()
            data.jumlah=$("#add-jumlah").val()
            data.deskripsi=$('#add-deskripsi').val()
            data.tanggal_kirim=$("#add-tanggal-kirim").val()
            $.ajax({
                method:"POST",
                url:`${api}/v1/sales-order`,
                contentType:'application/json',
                data:JSON.stringify(data),
                headers:{
                    Authorization:`Bearer ${localStorage.getItem("token")}`
                },
                success:res=>{
                    alert('Sales order telah dibuat');
                    window.location.reload()
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
            return false
        }
        function edit_modal(id){

        }

    </script>
@endsection

