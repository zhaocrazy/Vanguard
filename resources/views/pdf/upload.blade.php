<div class="modal fade" id="choose-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 avatar-source" id="no-photo"
                         data-url="">
                    </div>
                    <div class="col-md-4 avatar-source">
                        <div class="btn btn-light btn-upload">
                            <i class="fa fa-upload"></i>
                            <input type="file" accept="application/pdf"  name="name" id="pdf-upload">
                        </div>
                        <p class="mt-3">Please Select Pdf To Upload </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" data-url="{{ $uploadUrl }}" >
                        <button id="save-upload"  type="button" class="btn btn-primary form-control" >SAVE</button>
                    </div>
                    <div class="col-md-6" >
                        <button id="cancel-upload" type="button" class="btn btn-danger form-control" > CANCEL</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->