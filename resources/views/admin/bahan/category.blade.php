@extends('layout.admin')


@section('isi')

<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">

@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <button class="btn-primary btn" onclick="tambah_category()">Tambah Kategori</button>
    </div>
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header">Category
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                    <table id="data">
                        <thead>
                            <th>Category</th>
                            <th>Code</th>
                            <th>Jumlah Bahan</th>
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
          <h5 class="modal-title">Tambah Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return addcategory();">
                <div class="form-group form-row">
                    <label class="col-2">Code:</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="add-kode">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Nama :</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="add-nama">
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
          <h5 class="modal-title">Edit Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <form class="form-horizontal " onsubmit="return editcategory();">
            <input type="hidden" id="id">
                <div class="form-group form-row">
                    <label class="col-2">Code:</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="edit-kode">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Nama :</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="edit-nama">
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
    let table=  $("#data").DataTable()
    $.ajax({
        method:"GET",
        url:api+'/v1/category?count=bahan',
        headers:{
            Authorization:"Bearer "+localStorage.getItem('token')
        },
        success:res=>{
            console.log(res)
            res.data.forEach(item => {
                let action=`<div class="btn-group">
                            <button class="btn btn-success" onclick="edit(${item.id})"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger" onclick="deletecategory(${item.id})"><i class="fa fa-trash"></i></button>
                            </div>`
                table.row.add([
                    item.name,
                    item.code,
                    item.bahan_count,
                    action
                ]).draw(false)
            });
        }
    })
    function tambah_category(){
        $("#tambah-modal").modal('show')
    }
    function edit(id){
        $("#id").val(id)
        $.ajax({
            method:"get",
            url:api+`/v1/category/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                $("#edit-kode").val(res.data.code)
                $("#edit-nama").val(res.data.name)
            }
        })
        $("#edit-modal").modal('show')
    }
    function deletecategory(id){
        $.ajax({
            method:"delete",
            url:api+`/v1/category/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert('Berhasil Dihapus')
                window.location.reload()
            }
        })
    }
    function editcategory(){
        let id=$("#id").val()
        let data={}
        data.name=$("#edit-nama").val()
        data.code=$("#edit-kode").val()
        $.ajax({
            method:'put',
            url:api+`/v1/category/${id}`,
            contentType:"application/json",
            headers:{
                Authorization :"Bearer "+localStorage.getItem('token')

            },
            data:JSON.stringify(data),
            success:res=>{
                alert('data category berhasil disimpan')
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
    function addcategory(){
        let data={}
        data.name=$("#add-nama").val()
        data.code=$("#add-kode").val()
        $.ajax({
            method:'post',
            url:api+'/v1/category',
            contentType:"application/json",
            headers:{
                Authorization :"Bearer "+localStorage.getItem('token')

            },
            data:JSON.stringify(data),
            success:res=>{
                alert('data category berhasil disimpan')
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
        return false;
    }

</script>

@endsection
