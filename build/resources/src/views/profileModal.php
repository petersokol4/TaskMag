<div class="modal fade animated bounceInDown" id="profileModal" tabindex="-1" role="dialog"
     aria-labelledby="profile" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Môj Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="conteiner-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex flex-wrap justify-content-end">
                                <a class="action uploadAvatar" href="javascript:void(0)" role="button" data-toggle="modal" data-target="#changeAvatarModal" title="Zmeniť fotku"><i class="fas fa-edit"></i></a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <img src="" class="rounded-circle profile avatarImg uAvatarAjax" alt="" width="200" height="200">
                            </div>
                            <div class="text-center mt-4"><h6 class="userEmail"></h6></div>
                        </div>
                        <div class="col-lg-8">
                            <form accept-charset="utf-8" id="_profile" method="post">
                                <div class="form-group putError">
                                    <label for="userName"><span class="text-uppercase formLabel required">Meno</span></label>
                                    <input type="text" class="form-control" id="userName" name="userName" aria-describedby="klient-projektu" value="">
                                </div>
                                <div class="form-group putError">
                                    <label for="userAbout"><span class="text-uppercase formLabel">Poznámka</span></label>
                                    <textarea class="form-control" id="userAbout" name="userAbout" rows="3"></textarea>
                                </div>
                                <button class="btn" id="updateProfile" name="updateProfile" type="submit">Upraviť profil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>