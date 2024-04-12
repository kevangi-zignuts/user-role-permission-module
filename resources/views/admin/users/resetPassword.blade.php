<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Reset Password</h3>
                </div>
                <!-- Add role form -->
                {{-- {{ $user->id }} --}}
                <form action="" id="resetPasswordForm" class="row g-3" method="post">
                    @csrf
                    @if (Auth()->user()->email === 'admin@example.com')
                        <div class="col-12 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" tabindex="-1"
                                disabled />
                        </div>
                    @else
                        <div class="col-12 mb-2">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" tabindex="-1" />
                        </div>
                    @endif
                    <div class="col-12 mb-2">
                        <label class="form-label">Reset Password</label>
                        <input type="password" name="password" class="form-control" tabindex="-1" />
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" tabindex="-1" />
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
