<script>
    $(document).ready(function() {

        getDataUser()

    });

    function getDataUser() {

        $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('user-management.dataTable') }}",
            },
            responsive: true,
            pageLength: 10,
            lengthChange: true,
            lengthMenu: [
                [10, 20, 50, 100, -1],
                [10, 20, 50, 100, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'index',
                    fixedColumns: true,
                    width: '5%',
                    defaultContent: '-',
                    className: 'text-center',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    fixedColumns: true,
                    defaultContent: '-',
                    className: 'text-left',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'email',
                    name: 'email',
                    fixedColumns: true,
                    defaultContent: '-',
                    className: 'text-left',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'phone',
                    name: 'phone',
                    fixedColumns: true,
                    defaultContent: '-',
                    className: 'text-left',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'role',
                    name: 'role',
                    fixedColumns: true,
                    defaultContent: '-',
                    className: 'text-left',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    fixedColumns: true,
                    width: '20%',
                    defaultContent: '-',
                    className: 'text-center',
                    orderable: true,
                    searchable: false
                },
            ],
        });

        $('#date_production').change(function() {

            $('#datatable').DataTable().ajax.reload();
        });

    }

    function destroy(param) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                swalProcess();
                $.ajax({
                    url: "{{ route('user-management.destory') }}",
                    type: "POST",
                    data: {
                        id: param,
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                        setTimeout(function() {
                            location.reload();
                            window.scrollTo(0, 0);
                        }, 1500);

                    },
                    error: function(xhr, status, error) {
                        swalError(error);
                    }
                });

            }
        });
    }
</script>
