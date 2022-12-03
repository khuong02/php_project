@extends('layouts/contentNavbarLayout')

@section('title', 'Topics - Management')

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/masonry/masonry.js')}}"></script>
@endsection

@section('content')


<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Questions Management /</span> Topics
</h4>
<p>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createtopic">
    Create a new topic
  </button>
</p>

  <!-- Start Modal Create -->
<div class="modal fade" id="createtopic" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">CREATE TOPIC</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="/topics">
            @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label for="nameBasic" class="form-label">Name</label>
              <input type="text" id="nameBasic" name="nametopic" class="form-control" placeholder="Enter Name">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
        </form>
      </div>
    </div>
</div>
{{-- End modal create --}}

{{-- Start modal delete --}}
<div class="modal fade" id="deletetopic" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">DELETE CONFIRM</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" id="formdel">
            @csrf
            @method("PUT")
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
                <h5>Are you sure want to delete this topic!!</h5>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Delete</button>
        </div>
        </form>
      </div>
    </div>
</div>

{{-- End modal delete --}}
<div class="card">
  <h5 class="card-header">Quản lý lĩnh vực</h5>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Topic name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($listTopic as $topic)
        <tr>
            <td>
                 <strong>{{$topic->name}}</strong>
            </td>
          @if($topic->deleted_at === null)
          <td><span class="badge bg-label-primary me-1">
            Active
            @else
            <td><span class="badge bg-label-warning me-1">
            Inactive
          @endif
          </span></td>
          <td>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" id="delete" data-bs-target="#deletetopic" data-id="{{$topic->id}}">
                    <i class="bx bx-trash me-1"></i> Delete
                  </button>
            </div>
          </td>
        </tr>
        @endforeach

      </tbody>
    </table>
  </div>
</div>
<script>

</script>
@endsection

@section('page-script')
<script>
    $(document).on("click", "#delete", function () {
            var eventId = $(this).data('id');
            document.getElementById('formdel').action = "/topics/"+eventId;

        });
</script>
@endsection
