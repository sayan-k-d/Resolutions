<x-general>
    <x-slot:title>Profile</x-slot:title>
    <x-slot:search>
        {{ @$search }}
    </x-slot:search>
    <x-slot:content>
        <div class="profile w-100 h-100 min-vh-100">
            <div class="container py-4">
                <!-- Profile Header -->
                <div class="profile-header text-center">
                    <div class="profile-info">
                        @if (isset($editProfile) && $editProfile)
                            <form action="{{ route('updateProfile', ['id' => $user->id]) }}" method="POST">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control resolution-area" placeholder="Name" required
                                        id="floatingInput">
                                    <label for="floatingInput" class="text-dark">Name</label>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control resolution-area" placeholder="Email" required
                                        id="floatingInput">
                                    <label for="floatingInput" class="text-dark">Email</label>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-outline-light">Update</button>
                                <a href="{{ route('profile') }}" class="btn btn-dark">cancel</i></a>
                            </form>
                        @elseif (isset($editPassword) && $editPassword)
                            <form action="{{ route('updatePassword', ['id' => $user->id]) }}" method="POST">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="password" name="oldPassword" class="form-control resolution-area"
                                        placeholder="Old Password" required id="floatingInput">
                                    <label for="floatingInput" class="text-dark-emphasis">Old Password</label>
                                    @error('oldPassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control resolution-area"
                                        placeholder="New Password" required id="floatingInput">
                                    <label for="floatingInput" class="text-dark-emphasis">New Password</label>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-outline-light">Update</button>
                                <a href="{{ route('profile') }}" class="btn btn-dark">cancel</i></a>
                            </form>
                        @else
                            <h3>{{ $user->name }}</h3>
                            <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                            <p><i class="fas fa-calendar-alt"></i> Member since {{ $user->created_at->format('M Y') }}
                            </p>
                            <div class="d-flex justify-content-center mt-3 gap-3 align-items-center profile-actions">
                                <form action="{{ route('editProfile') }}" method="GET">
                                    <button class="badge text-bg-light rounded-pill p-2 border-0 "><i
                                            class="bi bi-pencil-square fs-4"></i></button>
                                </form>
                                <button class="badge text-bg-light rounded-pill p-2 border-0" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">+
                                    Add
                                    Resolution</button>
                                <form action="{{ route('editPassword') }}" method="GET">
                                    <button class="badge text-bg-light rounded-pill p-2 border-0 ">Update
                                        Password</button>
                                </form>
                                <form action="{{ route('deleteProfile', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="badge text-bg-light rounded-pill p-2 border-0 "><i
                                            class="bi bi-trash fs-4"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="text-white">My Resolutions</h4>
                    <div class="row mt-3">
                        @foreach ($resolutions as $resolution)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5>{{ $resolution->name }}</h5>
                                        <div>
                                            <form id="statusForm{{ $resolution->id }}"
                                                action="{{ route('updateStatus', ['id' => $resolution->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('put')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="flexSwitchCheckDefault{{ $resolution->id }}" name="status"
                                                        value="checked"
                                                        {{ $resolution->status === 'private' ? 'checked' : '' }}
                                                        onchange="document.getElementById('statusForm{{ $resolution->id }}').submit()">
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckDefault">Private</label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if (isset($editFlag) && $editFlag && isset($editResolutionId) && $editResolutionId === $resolution->id)
                                            <form action="{{ route('update', ['id' => $resolution->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('put')
                                                <div class="mb-3">
                                                    <textarea class="resolution-area form-control" id="exampleFormControlTextarea1" rows="3" name="description"
                                                        required>{{ old('description', $description) }}</textarea>
                                                </div>
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bi bi-floppy"></i></button>
                                                    <a href="{{ route('profile') }}" class="btn btn-secondary"><i
                                                            class="bi bi-x-circle"></i></a>
                                                </div>
                                            </form>
                                        @else
                                            <blockquote class="blockquote mb-0">
                                                <small>{{ $resolution->description }}</small>
                                            </blockquote>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <small class="">{{ $resolution->created_at->format('M d, Y') }}</small>
                                        <div class="float-end">
                                            <div class="d-flex gap-3">
                                                @if (!isset($editFlag) || !$editFlag || $editResolutionId !== $resolution->id)
                                                    <form action="{{ route('edit', ['id' => $resolution->id]) }}"
                                                        method="get">
                                                        <button
                                                            class="badge text-bg-warning rounded-pill p-2 border-0"><i
                                                                class="bi bi-pencil-square"></i></button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('destroy', ['id' => $resolution->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="badge text-bg-danger rounded-pill p-2 border-0"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Your Resolution</h5>
                        <div class="ms-auto modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x-circle fs-4"></i></div>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('store') }}" method="POST">
                            @csrf
                            @if (!Auth::check())
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                                    <input type="text" name="name" class="form-control resolution-area"
                                        aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default">
                                </div>
                            @endif
                            <div class="mb-3">
                                <textarea class="resolution-area form-control" id="exampleFormControlTextarea1" rows="3" name="description"
                                    required></textarea>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="flexSwitchCheckDefault" name="status" value="checked">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Private</label>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-dark">Add Resolution</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if (session('error'))
            <script>
                Swal.fire({
                    icon: "error",
                    text: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif
    </x-slot:content>
</x-general>
