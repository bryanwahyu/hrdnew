@extends('layout.admin')
@section('isi')
<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <button class="btn btn-danger" onclick="add_excel()">Import Excel</button>
    </div>
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header">
                Stok  Bahan Saat ini
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                    <table id="data">
                        <thead>
                            <th>SKU</th>
                            <th>Nama</th>
                            <th>Category</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Terakhir di Update</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Import </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="col-12 mb-3">
                    <button class="btn btn-secondary" onclick="template()">
                        Template
                    </button>
            </div>
            <form class="form-horizontal " onsubmit="return import_excel();">
                <div class="form-horizontal form-group">
                    <label  class="col-2">File</label>
                    <div class="col-8">
                        <input type="file" id="import" class="form-control">
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
let table=$("#data").DataTable()
$.ajax({
        method:"get",
        url:api+'/v1/bahan?with=category,satuan',
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            console.log(res);
            res.data.forEach(item => {
                table.row.add([
                    item.sku,
                    item.name,
                    item.category.name,
                    item.satuan.name,
                    item.stok,
                    new Date(item.created_at).toLocaleString()
                ]).draw(false)

           })
        }
    })
    function add_excel(){
        $("#import-modal").modal('show')
    }

    function import_excel(){
        let data=new FormData()
        data.append('file', $('#import')[0].files[0])
        $.ajax({
            method:"POST",
            url:`${api}/v1/import/stok-opname`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            data: data,
            contentType: false,
            processData: false,
            success:res=>{
                alert("import sukses");
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
    function template(){
        $.ajax({
            method:"get",
            url:`${api}/v1/export/excel/stok-opname`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            responseType: 'arraybuffer', // Set the response type to 'arraybuffer'
            success:res=>{

                let blob = new Blob([res], { type: 'text/csv' });

                // Create a data URL representing the Blob
                var blobUrl = URL.createObjectURL(blob);

                // Create a temporary link element
                var link = document.createElement('a');
                link.href = blobUrl;
                link.download = 'template.csv';

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
