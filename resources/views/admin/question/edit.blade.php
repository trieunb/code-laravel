<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Edit Question: {{$question->id}}</h4>
    <div id="ajax-message"></div>
</div>
<div class="modal-body">
    <form class="form-horizontal" method="post" enctype="multipart/form-data" role="form" id="form-update" action="{{ route('api.question.post.editAdmin') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="id" value="{{$question->id}}">

        <div class="form-group">
            <label for="content" class="col-sm-2 control-label">Content</label>
            <div class="col-sm-10">
                <input type="text" value="{{ $question->content }}" class="form-control" id="content" placeholder="Content">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button typ e="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary update">Save changes</button>
</div>

<script>
    $(document).ready(function() {
        var isBusy = false;
        $(document).off('click', 'button.update').on('click', 'button.update', function(e) {
            e.preventDefault();
            
            if (isBusy) return;

            isBusy = true;
            $.ajax({
                url: $('#form-update').attr('action'),
                type: 'POST',
                data: {
                    token: $('input[name=_token]').val(),
                    id: $('input[name=id]').val(),
                    content: $('#content').val()
                },
                success: function(result) {
                    var message = result.status == true
                        ? '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Update successfully.</strong> </div>'
                        : '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error when update!</strong></div>';
                    $('#ajax-message').html(message);
                }
            }).always(function() {
                isBusy = false;
            });
        });
    });
</script>