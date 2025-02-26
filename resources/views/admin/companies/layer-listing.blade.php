@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    body {
        margin: 0;
    }
    .card {
    border-radius: 8px;
}
    table {
    width: 100%;
    border-collapse: collapse;
}

div#myModal .modal-dialog {
    max-width: 60%;
}

table thead tr th,table tbody tr td {
    color: #4b5563;
    padding: 8px 5px;
    border-bottom: 1px solid #e5e7eb;
    text-align: center;
}

table thead tr th {
    font-size: 18px;
}

.card-table {
    overflow: auto;
}

div#myModal .modal-content {
    box-shadow: none;
}

div#myModal .modal-content .modal-body {
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
}

div#myModal .modal-content .modal-body .card {
    border: 0;
    box-shadow: none;
}
</style>
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-head">
                            <h1>Layer Management</h1>
                        </div>
                        <div class="card-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Layer Name</th>
                                        <th>Pipe</th>
                                        <th>Head</th>
                                        <th>Lock</th>
                                        <th>Hide</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>AS_RANDOM_PIPE 1</td>
                                        <td>AS_RANDOM_PIPE 1</td>
                                        <td>AS_RANDOM_PIPE 1</td>
                                        <td>AS_RANDOM_PIPE 1</td>
                                        <td>AS_RANDOM_PIPE 1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>        
            </div>
        </div>
    </div>
