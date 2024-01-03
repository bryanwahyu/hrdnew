@extends('layout.admin')
@section('isi')
<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
@endsection
@section('content')

<div class="row">
    <div class="col-12 my-2">
            <button class="btn-primary btn" onclick="tambah_modal()">Tambah</button>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Customer
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table id="customer">
                        <thead>
                            <th>Nama</th>
                            <th>Alamat</th>
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
                <form class="form-horizontal " onsubmit="return add_customer();">
                    <div class="form-group form-row">
                        <label class="col-2">Nama:</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="add-nama">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label  class="col-2">Phone Number :</label>
                        <div class="col-8">
                            <input type="tel" class="form-control" id="add-phone">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Alamat :</label>
                        <div class="col-8">
                            <textarea id="add-alamat" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Deskripsi:</label>
                        <div class="col-8">
                            <textarea id="add-deskripsi" class="form-control" cols="30" rows="10"></textarea>
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
              <h5 class="modal-title">Edit</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal " onsubmit="return edit_customer();">
                    <div class="form-group form-row">
                        <input hidden id="id">
                        <label class="col-2">Nama:</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="edit-nama">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">No Telpon</label>
                        <div class="col-8">
                            <input type="tel" class="form-control" id="edit-phone">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Alamat :</label>
                        <div class="col-8">
                            <textarea id="edit-alamat" class="form-control" cols="30" rows="10"></textarea>
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
        let data= $('#customer').DataTable()
        $.ajax({
            method:"get",
            url:api+'/v1/customer',
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`,
            },
            success:res=>{
                res.data.forEach(item => {
                    let action=`<div class="btn-group">
                                <button class="btn btn-success" onclick="edit_modal(${item.id})"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger" onclick="delete_customer(${item.id})"><i class="fa fa-trash"></i></button>
                                </div>`
                    data.row.add([
                        item.name,
                        item.address,
                        action
                    ]).draw(false)
                });
            }

        })
        function tambah_modal(){
            $('#tambah-modal').modal('show')
        }
        function add_customer(){
            let data={}
            data.name=$("#add-nama").val()
            data.address=$('#add-alamat').val()
            data.mobile_phone=$('#add-phone').val()
            data.description=$("#add-deskripsi").val()
            $.ajax({
                method:"POST",
                url:api+`/v1/customer`,
                contentType:'application/json',
                data:JSON.stringify(data),
                headers:{
                    Authorization:`Bearer ${localStorage.getItem("token")}`
                },
                success:res=>{
                    alert('Customer sudah di buat');
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
            $.ajax({
                method:"GET",
                url:api+`/v1/customer/${id}`,
                headers:{
                    Authorization:`Bearer ${localStorage.getItem('token')}`
                },
                success:res=>{

                    $("#edit-nama").val(res.data.name)
                    $('#edit-phone').val(res.data.mobile_phone)
                    $("#edit-alamat").val(res.data.address)
                    $('#edit-deskripsi').val(res.data.description)
                    $("#id").val(id)
                }
            })
            $('#edit-modal').modal('show')
        }
        function delete_customer(id){
            $.ajax({
                method:"delete",
                url:api+`/v1/customer/${id}`,
                headers:{
                    Authorization:`Bearer ${localStorage.getItem('token')}`
                },
                success:res=>{
                    alert('Berhasil Dihapus')
                    window.location.reload()
                }
            })
        }
        function edit_customer(){
            let id=$("#id").val()
            let data={}
            data.name=$("#edit-nama").val()
            data.mobile_phone= $('#edit-phone').val()
            data.address= $("#edit-alamat").val()
            data.description=$('#edit-deskripsi').val()
             $.ajax({
                method:"PUT",
                url:api+`/v1/customer/${id}`,
                contentType:'application/json',
                data:JSON.stringify(data),
                headers:{
                    Authorization:`Bearer ${localStorage.getItem("token")}`
                },
                success:res=>{
                    alert('Customer sudah di edit');
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
    </script>
@endsection
