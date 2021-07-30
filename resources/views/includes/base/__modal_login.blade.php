<div class="modal fade" id="modal_login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="btn btn-primary facebook form-control" href="{{ route('social.login', ['provider' => 'facebook']) }}"><span>Login with Facebook</span> <i class="fa fa-facebook"></i></a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="btn btn-danger google form-control" href="{{ route('social.login', ['provider' => 'google']) }}"><span>Login with Google</span> <i class="fa fa-google-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
