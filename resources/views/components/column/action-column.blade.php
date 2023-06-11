<a href="#" class="btn btn-sm btn-icon btn-primary edit-btn"
    @foreach ($row as $key => $data)
        data-{{ $key }}="{{ $data }}"
    @endforeach
    data-bs-toggle="modal"
    data-bs-target="#showModal">
    <i class="fa fa-edit"></i>
</a>

<button type="button" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="modal"
    data-bs-target="#exampleModal{{ $row['id'] }}"><i class="fa fa-trash"></i></button>

<div class="modal fade" id="exampleModal{{ $row['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-dark" id="exampleModalLabel">
                    Konfirmasi Hapus</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                Apakah anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                <form action="{{ route('criteria.destroy', $row['id']) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
