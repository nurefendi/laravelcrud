<script type="text/javascript">
    var $categoriesTable = $('#category-table tbody');
    var $paginationLinks = $('#pagination-links');
    var $searchInput = $('#search');
    var $modalTambah = $('#exampleModal');
    var currentPage = 1;

    $(document).ready(function () {
        // start load categorys
        loadCategorys( $('#search').text(), 1);

        // pagination categorys
        $(document).on('click', '#pagination-links a.page-link', function (e) {
            e.preventDefault();
            if ($(this).parent().hasClass('disabled') || $(this).parent().hasClass('active')) {
                return;
            }
            var page = $(this).data('page');
            loadCategorys('',page);
        });

        $searchInput.change(function(){
            loadCategorys($(this).val(), 1)
        })
        $searchInput.keyup(function(){
            loadCategorys($(this).val(), 1)
        })

        $(document).on('click', '#exampleModal div.modal-dialog div.modal-footer button.btn-simpan-tambah', function (e) {
            let form = $('#form-tambah');
            var button = $(this);
            button.prop('disabled', true);
            if ($('#form-tambah input[type="hidden"]').length) {
                let id = $('#form-tambah input[type="hidden"]').val()
                $.ajax({
                    url: "{{ route('api.category.update', ':id') }}".replace(':id', id),
                    data: JSON.stringify(validate(form)),
                    type: 'PUT',
                    contentType: "application/json",
                    success: function(result) {
                        $modalTambah.modal('hide');
                        loadCategorys('', 1);
                        form[0].reset();
                        alert('Berhasil mengubah kategori :'+ response.data.name );
                        button.prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        button.prop('disabled', false);
                        alert('Gagal mengubah kategori : ' + xhr.responseText)
                    }
                });
            } else{
                $.post({
                    url: "{{ route('api.category.store') }}",
                    data: JSON.stringify(validate(form)),
                    contentType: "application/json"
                }).done(function(response) {
                    if(response.status == 'SUCCESS'){
                        loadCategorys('', 1);
                        $modalTambah.modal('hide');
                        form[0].reset();
                        alert('Berhasil menambah kategori :'+ response.data.name );
                        
                    } else {
                        alert('Gagal menambah kategori')
                    }
                    button.prop('disabled', false);
                }).fail(function(error) {
                    alert('Gagal menambah kategori')
                    button.prop('disabled', false);
                });
            }
        });

        $categoriesTable.on('click', '#btn-edit', function (e) {
            let form = $('#form-tambah');
            let id = $(this).data('id');
            $('#exampleModalLabel').text("Edit Kategori")

            $.ajax({
                url: "{{ route('api.category.show', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function(result) {
                    form.append($('<input type="hidden" name="id" value="'+id+'">'));
                    $modalTambah.modal('show');
                    form.find('#inputKategori').val(result.data.name);
                    form.find('#exampleCheck1').prop("checked", result.data.is_publish == 1)
                },
                error: function(xhr, status, error) {
                    alert('Gagal menghapus kategori')
                }
            });

            

        });

        $categoriesTable.on('click', '#btn-delete', function (e) {
            let id = $(this).data('id');
            if(confirm("Anda akan menghapus kategori ini?")){
                $.ajax({
                    url: "{{ route('api.category.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    success: function(result) {
                        alert('Berhasil menghapus kategori')
                        loadCategorys('', 1);
                    },
                    error: function(xhr, status, error) {
                        alert('Gagal menghapus kategori')
                    }
                });
            } 
            
        });

        $modalTambah.on('hide.bs.modal', function(e){
            $('#form-tambah input[type="hidden"]').remove();
            $('#form-tambah')[0].reset();
        })


    });

    function validate($data){
        var formData = $data.serializeArray();
        var data = {
            name: '',
            is_publish: false
        };
        formData.forEach(function(field) {
            if (field.name === 'name') {
                data.name = field.value
            }
            if (field.name === 'is_publish') {
                data.is_publish = field.value == 'on'
            }
        });

        return data;
    }

    function loadCategorys(search, page){
            $.get("{{ route('api.category.index') }}", {
                search: search,
                page: page
            }, function(data, status) {
                    $categoriesTable.empty();
                    $paginationLinks.empty();
                    $.each(data.data, function (key, category) {
                        var $row = $('<tr>');
                        $row.append($('<td>').text(category.id));
                        $row.append($('<td>').text(category.name));
                        $row.append($('<td>').text(category.is_publish ? 'Yes' : 'No'));
                        // $row.append($('<td>').text(category.created_at));
                        // $row.append($('<td>').text(category.updated_at));

                        let button = '<button type="button" data-id="'+category.id+'" id="btn-edit"  class="btn btn-primary btn-sm">Edit</button> ' +
                                    '<button type="button" data-id="'+category.id+'" id="btn-delete" class="btn btn-danger btn-sm">delete</button>';
                        $row.append($('<td>')
                            .html(button));
                        $categoriesTable.append($row);
                    });
                    if (data.data.length == 0) {
                        let row = '<tr><td colspan="4" class="text-center">No categories found.</td></tr>';
                        $categoriesTable.append(row);
                    }
                    if (data.links) {
                        var links = '';
                        var prevClass = data.prev_page_url ? '' : 'disabled';
                        var nextClass = data.next_page_url ? '' : 'disabled';

                        links += '<li class="page-item '+prevClass+'">';
                        links += '<a class="page-link" href="javascript:void(0)" data-page="'+(page-1)+'" tabindex="-1" aria-disabled="'+(prevClass ? 'true' : 'false')+'">Previous</a>';
                        links += '</li>';

                        for (var i = 1; i <= data.last_page; i++) {
                            var activeClass = data.current_page == i ? 'active' : '';
                            links += '<li class="page-item '+activeClass+'">';
                            links += '<a class="page-link" href="javascript:void(0)" data-page="'+i+'">'+i+'</a>';
                            links += '</li>';
                        }

                        links += '<li class="page-item '+nextClass+'">';
                        links += '<a class="page-link" href="javascript:void(0)"  data-page="'+(page+1)+'" aria-disabled="'+(nextClass ? 'true' : 'false')+'">Next</a>';
                        links += '</li>';

                        $('#pagination-links').html(links);
                    }
            });
        }
    
</script>
