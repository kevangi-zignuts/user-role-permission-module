<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5"
            style="background: linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%);">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2 text-white">Edit Details</h3>
                </div>
                <form action="{{ route('user.update') }}" id="resetPasswordForm" class="row g-3" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-white">First Name *</label>
                            <input type="text"
                                class="form-control border-0 border border-bottom rounded-0 border-white text-white bg-transparent"
                                name="first_name" value="{{ $user->first_name }}" required />
                            @error('first_name')
                                <div class="text-danger pt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white">Last Name</label>
                            <input type="text"
                                class="form-control phone-mask border-0 border border-bottom rounded-0 border-white text-white bg-transparent"
                                name="last_name" value="{{ $user->last_name }}" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">Email *</label>
                        <input type="email"
                            class="form-control border-0 border border-bottom rounded-0 border-white text-white bg-transparent"
                            name="email" value="{{ $user->email }}" autofocus disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">Contact no</label>
                        <input type="tel"
                            class="form-control border-0 border border-bottom rounded-0 border-white text-white bg-transparent"
                            name="contact_no" value="{{ $user->contact_no }}" autofocus>
                        @error('contact_no')
                            <div class="text-danger pt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label text-white">Address</label>
                        <textarea class="form-control rounded-0 border-white text-white bg-transparent" id="exampleFormControlTextarea1"
                            rows="3" name="address">{{ $user->address }}</textarea>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit"
                            class="bg-white text-primary btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
