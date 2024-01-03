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
            <div class="card-header">PO List</div>
            <div class="card-body">
                <div class="col-12 my-3">
                    <input type="hidden" id="id" value="{{$id}}">

                    <div class="form-group form-row">
                        <label  class="col-3"> Kode PO</label>
                        <div class="col-8">
                            <input type="text" id="kode-po" readonly class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-3">Status</label>
                        <div class="col-8">
                            <input type="text" readonly class="form-control" id="status-po">
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
                    <div class="col-12 my-3" id="tambah">
                        <button class="btn btn-primary" onclick="show_modal()">Tambah</button>
                    </div>
                    <div class="col-12 table-responsive" >
                        <table id="table">
                            <thead>
                                <th>Bahan</th>
                                <th>jumlah pesanan</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" onclick="print_csv()">Print csv</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pesanan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-horizontal " onsubmit="return edit_pesanan();">
                <div class="form-group form-row">
                    <label  class="col-2">Bahan</label>
                    <div class="col-8">
                        <input type="hidden" id="id-det">
                        <select id="edit-bahan-id" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2"> Order</label>
                    <div class="col-3">
                        <input type="text" id="edit-jumlah-pesanan" class="form-control">
                    </div>
                    <label class="col-2">harga</label>
                    <div class="col-3">
                        <input type="text" id="edit-harga" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-2">Status</label>
                    <div class="col-8">
                        <select  id="edit-status" class="form-control">
                            <option value="0">Belum di check</option>
                            <option value="1">Sudah di check</option>
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
    const status = ['PO Telah dibuat', 'PO Telah Di Approved', "PO Telah Di Tolak", 'KIRIM PO KE SUPPLIER', 'Supplier on Delivered', 'Supplier On Check', 'PO Close', 'PO Selesai']
    let id = $('#id').val()
    let details = $("#table").DataTable()
    function print_csv(){
        $.ajax({
            method:"get",
            url:`${api}/v1/export/excel/request-po/${id}`,
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
    function getAction(id, status_po)
    {
      if(status_po == 5)
      {
        return `
                 <div class="btn-group">
                    <button class="btn btn-primary" onclick="editmodal(${id})">check </button>
                </div>
            `;
      }
      if(status_po < 3)
      {
        return `
            <div class="btn-group">
                    <button class="btn btn-primary" onclick="editmodal(${id})">check </button>
                    <button class="btn btn-danger" onclick="hapusdet(${id})">Hapus</button>
            </div>
                `;
      }
      return '';
    }

    function getstatus(status_det, status_po)
    {
      const data_status = ['Belum di Check', 'Sudah di check', 'Belum ada pengecekan'];
      if(status_po >= 5)
      {
        return data_status[status_det]
      }
      return data_status[2]
    }
    function edit_pesanan(){
        let data={}
        let id=$("#id-det").val()
        data.price = $('#edit-harga').val()
        data.stok_order = $("#edit-jumlah-pesanan").val()
        data.status=$("#edit-status").val()
        $.ajax({
            method:"PUT",
            url:`${api}/v1/detail-po/${id}`,
            data:JSON.stringify(data),
            headers:{
                Authorization:`Bearer ${localStorage.getItem('token')}`,
            },
            contentType:"application/json",
            success:res=>{
                alert('data berhasil di check')
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
        return false
    }
    function add_pesanan()
    {
      let data = {}
      data.bahan_id = $('#add-bahan-id').val()
      data.purchase_order_id = id
      data.price = $('#add-harga').val()
      data.stok_order = $("#add-jumlah-pesanan").val()
      $.ajax(
      {
        method: "POST",
        url: `${api}/v1/detail-po`,
        headers:
        {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
        data: JSON.stringify(data),
        contentType: "application/json",
        success: (res) =>
        {
          alert('data berhasil di buat')
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
      return false
    }
    function editmodal(id)
    {
      $('#edit-modal').modal('show')
      $('#id-det').val(id)
      $.ajax(
      {
        method: "GET",
        url: `${api}/v1/detail-po/${id}?load=bahan`,
        headers:
        {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        },
        success: res =>
        {
          let bahan = $("#edit-bahan-id").select2(
          {
            ajax:
            {
              url: `${api}/v1/bahan`,
              headers:
              {
                Authorization: `Bearer ${localStorage.getItem('token')}`
              },
              processResults: function(res)
              {
                let items = []
                res.data.forEach(e =>
                {
                  let item = {}
                  item.id = e.id
                  item.text = e.name + ' ' + e.sku
                  items.push(item)
                });
                return {
                  results: items
                }
              }
            }
          })
          let options = new Option(res.data.bahan.nama, res.data.bahan_id, true, true)
          bahan.append(options).trigger('change')
          bahan.trigger(
          {
            type: 'select2:select',
            params:
            {
              data: res.data.bahan
            }
          })
          $("#edit-jumlah-pesanan").val(res.data.stok_order)
          $("#edit-harga").val(res.data.price)
          $("#edit-status").val(res.data.status)
        }
      })
    }

    function show_modal()
    {
      $('#tambah-modal').modal('show')
    }
    $(document).ready(function()
    {
      let supplier = $("#supplier-po").select2(
      {
        ajax:
        {
          url: `${api}/v1/supplier`,
          headers:
          {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          },
          processResults: function(res)
          {
            let items = []
            res.data.forEach(e =>
            {
              let item = {}
              item.id = e.id
              item.text = e.nama
              items.push(item)
            });
            return {
              results: items
            }
          }
        }
      })
      $("#add-bahan-id").select2(
      {
        ajax:
        {
          url: `${api}/v1/bahan`,
          headers:
          {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          },
          processResults: function(res)
          {
            let items = []
            res.data.forEach(e =>
            {
              let item = {}
              item.id = e.id
              item.text = e.name + ' ' + e.sku
              items.push(item)
            });
            return {
              results: items
            }
          }
        }
      })
      $.ajax(
      {
        method: "get",
        url: `${api}/v1/purchase-order/${id}?load=detail.bahan,supply`,
        headers:
        {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        },
        success: (res) =>
        {
          $("#kode-po").val(res.data.code)
          $("#status-po").val(status[res.data.status])
          $("#client-po").val(res.data.client)
          $('#alamat-po').val(res.data.alamat)
          let options = new Option(res.data.supply.nama, res.data.supplier_id, true, true)
          supplier.append(options).trigger('change')
          supplier.trigger(
          {
            type: 'select2:select',
            params:
            {
              data: res.data.supply
            }
          })
          if(res.data.status > 3)
          {
            $('#tambah').addClass('d-none')
          }
          res.data.detail.forEach(item =>
          {
            details.row.add([
              item.bahan.name,
              item.stok_order,
              item.price,
              getstatus(item.status, res.data.status),
              getAction(item.id, res.data.status)
            ]).draw(false)
          })
        },
        error: (error) =>
        {}
      })
    })
</script>
  @endsection
