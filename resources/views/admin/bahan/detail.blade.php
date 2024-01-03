@extends('layout.admin')
@section('isi')
<script src="{{asset('datatable.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('datatable.min.css')}}">
@endsection
@section('content')
<input type="hidden" value="{{$id}}" id="id">
<div class="row">
    <div class=" col-12">
        <div class="card">
            <div class="card-header" id="nama-bahan">

            </div>
            <div class="card-body">
                <span class="col-2">SKU :</span>
                <span id="sku-bahan"></span>

                <span class="col-2">Data Stok  Saat ini :</span>
                <span id="stok-bahan"></span>
                <span class="col-2">Category :</span>
                <span id="category-bahan"></span>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary" onclick="edit_modal()">Edit Data</button>
                <button class="btn btn-success" onclick="import_modal()">import Excel</button>
            </div>
        </div>
    </div>
    <div class="col-12 my-4">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Masuk
                    </div>
                    <div class="card-body">
                        <div class="col-12 mb-2">
                            <button class="btn btn-primary" onclick="add_masuk_modal()">tambah data masuk</button>
                        </div>
                        <div class="col-12 table-responsive">
                            <table id="tabel-masuk">
                                <thead>
                                  <th>Nama</th>
                                  <th>Tanggal</th>
                                  <th>Keterangan</th>
                                  <th>Jumlah</th>
                                  <th>Action</th>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">Keluar</div>
                    <div class="card-body">
                        <div class="col-12 mb-2">
                            <button class="btn btn-danger" onclick="add_keluar_modal()">tambah data keluar</button>
                        </div>
                        <div class="col-12 table-responsive">
                            <table id="tabel-keluar">
                                <thead>
                                  <th>Nama</th>
                                  <th>Tanggal</th>
                                  <th>Keterangan</th>
                                  <th>Jumlah</th>
                                  <th>Action</th>
                              </thead>
                            </table>
                        </div>
                    </div>
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


<div class="modal fade" id="add-keluar-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="test">data keluar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return add_keluar();">
                <div class=" form-row form-group">
                    <label class="col-2">Nama</label>
                    <div class="col-8">
                        <input type="text" id="add-nama-keluar" class="form-control">
                    </div>
                </div>
                <div class="form-row form-group">
                    <label class="col-2">jumlah</label>
                    <div class="col-8">
                        <input type="number"  id="add-jumlah-keluar" class="form-control">
                    </div>
                </div>
                <div class="form-row form-group">
                    <label class="col-2">Keterangan</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="add-keterangan-keluar">
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

<div class="modal fade" id="add-masuk-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="test">data masuk</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return add_masuk();">
                <div class=" form-row form-group">
                    <label class="col-2">Nama</label>
                    <div class="col-8">
                        <input type="text" id="add-nama-masuk" class="form-control">
                    </div>
                </div>
                <div class="form-row form-group">
                    <label class="col-2">jumlah</label>
                    <div class="col-8">
                        <input type="number"  id="add-jumlah-masuk" class="form-control">
                    </div>
                </div>
                <div class="form-row form-group">
                    <label class="col-2">Keterangan</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="add-keterangan-masuk">
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
          <h5 class="modal-title" id="bahan">SKU</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return editbahan();">
                <div class="form-group">
                    <label  class="col-2">SKU</label>
                    <div class="col-8">
                        <input type="text" id="edit-sku" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-2">Nama</label>
                    <div class="col-8">
                        <input type="text" id="edit-name" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-2">Category</label>
                    <div class="col-8">
                        <select id="edit-category" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-2">Satuan</label>
                    <div class="col-8">
                        <select id="edit-satuan" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-2">Bisa diHitung</label>
                    <div class="col-8">
                        <select id="edit-counter" class="form-control">
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
    let id=$("#id").val()
    function import_modal(){
        $("#import-modal").modal('show')
    }
    function import_excel(){
        let data=new FormData()
        data.append('file', $('#import')[0].files[0]);

        $.ajax({
            method:"POST",
            url:`${api}/v1/import/masuk-keluar/${id}`,
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
            url:`${api}/v1/export/excel/masuk-keluar`,
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
                link.download = 'masukkeluar.csv';

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
        method:"GET",
        url:api+`/v1/bahan/${id}?with=category,satuan`,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            $('#nama-bahan').text(res.data.name)
            $('#sku-bahan').text(res.data.sku)
            $("#stok-bahan").text(`${res.data.stok} ${res.data.satuan.name}`)
            $('#category-bahan').text(res.data.category.name)
        }
        })
        function edit_modal(){
            $.ajax({
                method:"get",
                url:api+"/v1/category?select=yes",
            headers:{
                Authorization :"Bearer "+localStorage.getItem('token')
            },
            success:res=>{
                    $('#edit-category').html(res.data)
                }
            })
            $.ajax({
                method:"get",
                url:api+"/v1/satuan?select=yes",
            headers:{
                Authorization :"Bearer "+localStorage.getItem('token')
            },
            success:res=>{
                    $('#edit-satuan').html(res.data)
                }
            })
            $('#edit-modal').modal('show')
            $.ajax({
                  method:"GET",
                  url:api+`/v1/bahan/${id}`,
                  headers:{
                        Authorization:`Bearer ${localStorage.getItem('token')}`
                },
            success:res=>{
                    $('#edit-name').val(res.data.name)
                    $('#edit-sku').val(res.data.sku)
                    $('#edit-category').val(res.data.category_id)
                    $('#edit-satuan').val(res.data.satuan_id)
                    $('#edit-counter').val(res.data.is_count)
                }
        })
    }
    $.ajax({
        method:"GET",
        url:`${api}/v1/masuk?bahan_id=${id}`,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            res.data.forEach(item => {
                let action=`
                    <div class="btn-group">
                        <button class="btn btn-danger" onclick="hapus_masuk(${item.id})">Hapus</button>
                    </div>`
                masuk.row.add([
                    item.nama,
                    new Date(item.created_at).toLocaleString(),
                    item.keterangan,
                    item.jumlah,
                    action
                ]).draw(false)
            });
        }
    })

    function add_masuk(){
        let data={}
        data.bahan_id=id
        data.nama=$("#add-nama-masuk").val()
        data.jumlah=$("#add-jumlah-masuk").val()
        data.keterangan=$("#add-keterangan-masuk").val()
        $.ajax({
            method:"POST",
            url:`${api}/v1/masuk`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            data:JSON.stringify(data),
            contentType:"application/json",
            success:res=>{
                alert("Data Masuk berhasil ditambahkan")
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
    function add_keluar(){
        let data={}
        data.bahan_id=id
        data.nama=$("#add-nama-keluar").val()
        data.jumlah=$("#add-jumlah-keluar").val()
        data.keterangan=$("#add-keterangan-keluar").val()
        $.ajax({
            method:"POST",
            url:`${api}/v1/keluar`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            data:JSON.stringify(data),
            contentType:"application/json",
            success:res=>{
                alert("Data Keluar berhasil ditambahkan")
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
    function hapus_masuk(id){
        console.log(id)
        $.ajax({
            method:"DELETE",
            url:`${api}/v1/masuk/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert("berhasil menghapus masuk")
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
    }
    function hapus_keluar(id){
        $.ajax({
            method:"DELETE",
            url:`${api}/v1/keluar/${id}`,
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            success:res=>{
                alert("berhasil menghapus keluar")
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
    }

    $.ajax({
        method:"GET",
        url:`${api}/v1/keluar?bahan_id=${id}`,
        headers:{
            Authorization:`Bearer ${localStorage.getItem('token')}`
        },
        success:res=>{
            res.data.forEach(item => {
                let action=`
                    <div class="btn-group">
                        <button class="btn btn-danger" onclick="hapus_keluar(${item.id})">Hapus</button>
                    </div>`
                keluar.row.add([
                    item.nama,
                    new Date(item.created_at).toLocaleString(),
                    item.keterangan,
                    item.jumlah,
                    action
                ]).draw(false)
                })
        }
    })
    function editbahan(){
        let data={}
        data.name=$("#edit-name").val()
        data.sku=$('#edit-sku').val()
        data.satuan_id=$("#edit-satuan").val()
        data.category_id=$('#edit-category').val()
        data.is_count=$("#edit_counter").val()
        $.ajax({
            method:"PUT",
            url:api+`/v1/bahan/${id}`,
            contentType:"application/json",
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`
            },
            data:JSON.stringify(data),
            success:res=>{
                alert("data bahan berhasil di edit")
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
    function add_masuk_modal(){
        $('#add-masuk-modal').modal('show')
    }
    function add_keluar_modal(){
        $("#add-keluar-modal").modal('show')
    }


    let keluar=$("#tabel-keluar").DataTable()

    let masuk=$("#tabel-masuk").DataTable()
</script>
@endsection
