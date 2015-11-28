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
                <input type="text" name="content" value="{{ $question->content }}" class="form-control" id="content" placeholder="Content">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button typ e="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary update">Save changes</button>
            </div>
        </div>
       
     </form>
</div>

<script>
var isBusy = false;
$('#form-update').validate({
    rules: {
        content: {required :true}
    },
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
    submitHandler : function(form) {
       event.preventDefault();

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
    }
 });
</script>