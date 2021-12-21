<div class="row">
    <div class="col pb-0">
        <h4 class="mb-1">Available Documents</h4>
        @forelse ($documents as $item)
            <a class="btn btn-primary btn-block mb-0 mt-3" target="_blank" href="{{ route('inquiry.documents.download.sale', $item->id) }}">
                <i class="fa fa-download mr-1"></i>
                Download Document {{ $loop->iteration }}
            </a>
        @empty
            <span class="text-danger">No documents found for this Inquiry!</span>
        @endforelse
    </div>
</div>
