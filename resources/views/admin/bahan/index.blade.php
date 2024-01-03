@extends('layout.admin')


@section('isi')

<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">

@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <button class="btn-primary btn" onclick="tambah_bahan()">Tambah Bahan</button>
        <button class="btn btn-danger" onclick="add_excel()">Import Excel</button>
    </div>
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header">
                Bahan
            </div>
            <div class="card-body">
                <div class="col-12 mb-2">
                  <div class="form-group row">
                        <label  class="col-2">Category</label>
                        <select  id="fil-category" class="form-control col-2"></select>

                    </div>
                </div>
                <div class="table table-responsive">
                    <table id="data">
                        <thead>
                            <th>SKU</th>
                            <th>Nama</th>
                            <th>Category</th>
                            <th>Satuan</th>
                            <th>Bisa Dihitung</th>
                            <th>Stok</th>
                            <th>Action</th>
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
                    <button class="btn btn-secondary" onclick="list_cat()">
                        list Category
                    </button>
                    <button class="btn btn-secondary" onclick="list_sat()">
                        list Satuan
                    </button>
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

<div class="modal fade" id="tambah-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Bahan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return addbahan();">
                <div class="form-group">
                    <label  class="col-2">SKU</label>
                    <div class="col-8">
                        <input type="text" id="add-sku" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-2">Nama</label>
                    <div class="col-8">
                        <input type="text" id="add-name" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-2">Category</label>
                    <div class="col-8">
                        <select id="add-category" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-2">Satuan</label>
                    <div class="col-8">
                        <select id="add-satuan" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-2">Bisa diHitung</label>
                    <div class="col-8">
                        <select id="add-counter" class="form-control">
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
    let table=$("#data").DataTable()
    function add_excel(){
        $("#import-modal").modal('show')
    }
    function list_cat(){
        $.ajax({
            method:"get",
            url:`${api}/v1/export/excel/category`,
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
                link.download = 'category_list.csv';

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
    $.ajax({
        method:"get",
        url:api+'/v1/bahan?with=category,satuan',
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            console.log(res);
            res.data.forEach(item => {
                let action=`<div class="btn-group">
                            <button class="btn btn-success" onclick="edit(${item.id})"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger" onclick="deletesatuan(${item.id})"><i class="fa fa-trash"></i></button>
                            </div>`
                let bisa= item.is_count?"bisa":"tidak bisa"
                table.row.add([
                    item.sku,
                    item.name,
                    item.category.name,
                    item.satuan.name,
                    bisa,
                    item.stok,
                    action
                ]).draw(false)
           })
        }
    })
    $.ajax({
        method:"get",
        url:api+"/v1/category?select=yes",
        headers:{
                Authorization :"Bearer "+localStorage.getItem('token')
            },
        success:res=>{
            $("#fil-category").html(res.data)
            $("#add-category").html(res.data)
        }
    })
    function list_sat(){
        $.ajax({
            method:"get",
            url:`${api}/v1/export/excel/satuan`,
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
    function import_excel(){
        let data=new FormData()
        data.append('file', $('#import')[0].files[0]);

        $.ajax({
            method:"POST",
            url:`${api}/v1/import/bahan`,
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
            url:`${api}/v1/export/excel/bahan`,
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
    function addbahan(){
       let data={}
       data.sku=$("#add-sku").val()
       data.name=$("#add-name").val()
       data.category_id=$("#add-category").val()
       data.satuan_id=$("#add-satuan").val()
       $.ajax({
        method:"POST",
        url:api+'/v1/bahan',
        contentType:"application/json",
        headers:{
            Authorization:"Bearer "+localStorage.getItem('token')
        },
        data:JSON.stringify(data),
        success:res=>{
            alert('berhasil nyimpan data')
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
    function tambah_bahan(){
        $("#tambah-modal").modal('show')

        $.ajax({
        method:"get",
        url:api+"/v1/satuan?select=yes",
        headers:{
                Authorization :"Bearer "+localStorage.getItem('token')
            },
        success:res=>{
         $("#add-satuan").html(res.data)
        }
    })
    }
    function edit(id){
        window.location.replace(url+`/bahan/${id}`)
    }
    function deletebahan(id){
        $.ajax({
            method:"Delete",
            url:api+`/v1/bahan/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert("Bahan berhasil dihapus")
                window.location.reload()

            }
        })
    }
    </script>
@endsection
