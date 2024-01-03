@extends('layout.admin')


@section('isi')

<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">

@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <button class="btn-primary btn" onclick="tambah_satuan()">Tambah Satuan</button>
    </div>
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header">Satuan
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                    <table id="data">
                        <thead>
                            <th>Satuan</th>
                            <th>Bisa Dihitung</th>
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
          <h5 class="modal-title">Tambah Satuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return addsatuan();">
                <div class="form-group form-row">
                    <label class="col-2">Nama:</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="add-name">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Bisa Dihtung :</label>
                    <div class="col-8">
                        <select class="form-control" id="add-count">
                            <option value="1">Bisa</option>
                            <option value="0">Tidak Bisa</option>
                        </select>
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
          <h5 class="modal-title">Edit Satuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <form class="form-horizontal " onsubmit="return editsatuan();">
            <input type="hidden" id="id">
            <div class="form-group form-row">
                <label class="col-2">Nama:</label>
                <div class="col-8">
                    <input type="text" class="form-control" id="edit-name">
                </div>
            </div>
            <div class="form-group form-row">
                <label class="col-2">Bisa Dihtung :</label>
                <div class="col-8">
                    <select class="form-control" id="edit-count">
                        <option value="1">Bisa</option>
                        <option value="0">Tidak Bisa</option>
                    </select>
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
        url:api+'/v1/satuan',
        headers:{
            Authorization:"Bearer "+localStorage.getItem('token')
        },
        success:res=>{
            console.log(res)
            res.data.forEach(item => {
                let action=`<div class="btn-group">
                            <button class="btn btn-success" onclick="edit(${item.id})"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger" onclick="deletesatuan(${item.id})"><i class="fa fa-trash"></i></button>
                            </div>`
                let bisa= item.is_count?"bisa":"tidak bisa"
                table.row.add([
                    item.name,
                    bisa,
                    action
                ]).draw(false)
            });
        }
    })
    function tambah_satuan(){
        $("#tambah-modal").modal('show')
    }
    function edit(id){
        $("#id").val(id)
        $.ajax({
            method:"get",
            url:api+`/v1/satuan/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                $("#edit-name").val(res.data.name)
                $("#edit-count").val(res.data.is_count)
            }
        })
        $("#edit-modal").modal('show')
    }
    function deletesatuan(id){
        $.ajax({
            method:"delete",
            url:api+`/v1/satuan/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert('Berhasil Dihapus')
                window.location.reload()
            }
        })
    }
    function editsatuan(){
        let id=$("#id").val()
        let data={}
        data.name=$("#edit-name").val()
        data.is_count=$("#edit-count").val()
        $.ajax({
            method:'put',
            url:api+`/v1/satuan/${id}`,
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
    function addsatuan(){
        let data={}
        data.name=$("#add-name").val()
        data.is_count=$("#add-count").val()
        $.ajax({
            method:'post',
            url:api+'/v1/satuan',
            contentType:"application/json",
            headers:{
                Authorization :"Bearer "+localStorage.getItem('token')

            },
            data:JSON.stringify(data),
            success:res=>{
                alert('data satuan berhasil disimpan')
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
