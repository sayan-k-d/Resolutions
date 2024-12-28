<x-general>
    <x-slot:content>
        <x-slot:search>
            {{ @$search }}
        </x-slot:search>
        {{-- <x-slot:auth>
            {{ @$name }}
        </x-slot:auth> --}}
        <div class="dashboard w-100 h-100 min-vh-100">
            {{-- @dd(@$name) --}}
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center py-4">
                    <h1 class="text-white page-heading-h1">Welcome, {{ Auth::user()->name ?? 'Guest' }}
                    </h1>
                    <h2 class="text-white d-none page-heading-h2">Welcome, {{ Auth::user()->name ?? 'Guest' }}
                    </h2>
                    <div><button class="btn btn-light" id="addResolutionButton" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">+ Add
                            Resolution</button></div>
                </div>
                <div class="mb-3 display-search d-none">
                    <form action="{{ route('search') }}" role="search" class="d-flex" method="GET">
                        <input class="form-control me-2 search-area" name="search" type="search" placeholder="Search"
                            value="{{ @$search }}" aria-label="Search" id="search"
                            oninput="handleRedirect(event)" required />
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-4">
                    @foreach ($resolutions as $resolution)
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                {{ $resolution->status }}
                                <small class="">{{ $resolution->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="card-body">
                                <blockquote class="blockquote mb-0">
                                    <p>{{ $resolution->description }}</p>
                                    <footer class="blockquote-footer"><cite
                                            title="Source Title">{{ $resolution->name }}</cite></footer>
                                </blockquote>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex align-items-center gap-3">
                                    <form method="POST" action="{{ route('likes', ['id' => $resolution->id]) }}"
                                        class="like-post d-flex align-items-center gap-2">
                                        @csrf
                                        <button type="submit" class="like-button">
                                            @if (Auth::check() && in_array($resolution->id, $likes))
                                                <i class="bi bi-suit-heart-fill text-danger"></i>
                                            @else
                                                <i class="bi bi-heart"></i>
                                            @endif
                                        </button>
                                        <div class="counts text-center">{{ $resolution->likes }}</div>
                                    </form>

                                    <form action="{{ route('comments', ['id' => $resolution->id]) }}" method="get"
                                        class="comment-post d-flex align-items-center gap-2">
                                        <button type="submit" class="btn-transparent">
                                            <i class="bi bi-chat-left-dots" {{-- data-bs-toggle="modal"
                                                data-bs-target="#commentModal" data-id="{{ $resolution->id }}"
                                                data-resolution="{{ $resolution->description }}"
                                                data-name="{{ $resolution->name }}" --}}></i>

                                        </button>
                                    </form>
                                    <div class="counts text-center">{{ $resolution->comments }}</div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
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
        {{-- @dd(session('resolutionData')->description) --}}
        <div class="modal fade" id="commentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered comments-modal">
                <div class="modal-content comments">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Share Your Thought!</h1>
                        <div class="ms-auto modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x-circle fs-4"></i></div>
                    </div>
                    <div class="modal-body">
                        <blockquote class="blockquote mb-0">
                            <p id="blockquote-description" class="blockquote-description">
                                @if (session('resolutionData'))
                                    {{ session('resolutionData')->description }}
                                @elseif (isset($resolutionData) && $resolutionData)
                                    {{ $resolutionData->description }}
                                @endif
                            </p>
                            <footer class="blockquote-footer"><cite id="blockquote-name" title="Source Title">
                                    @if (session('resolutionData'))
                                        {{ session('resolutionData')->name }}
                                    @elseif (isset($resolutionData) && $resolutionData)
                                        {{ $resolutionData->name }}
                                    @endif
                                </cite>
                            </footer>
                        </blockquote>
                        @if (Auth::check())
                            <form
                                @if (session('resolutionData')) action="{{ route('storeComment', ['id' => session('resolutionData')->id]) }}"
                                @elseif (isset($resolutionData) && $resolutionData) action="{{ route('storeComment', ['id' => $resolutionData->id]) }}"
                                @else action="#" @endif
                                method="POST" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control resolution-area" id="comment-area" rows="3" name="comment"
                                        placeholder="write comment..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark float-end">Submit</button>
                            </form>
                        @endif
                    </div>
                    {{-- @dd(var_dump(session('comments'))) --}}
                    @if ((session('comments') && session('comments')->count() > 0) || (isset($comments) && $comments->count() > 0))
                        <div class="modal-footer">
                            <div class="w-100">
                                <h3>Comments</h3>
                                <ul class="list-group">
                                    @foreach (session('comments') ?? $comments as $key => $comment)
                                        <li
                                            class="list-group-item comment-list d-flex justify-content-between align-items-start">
                                            <div class="ms-2 w-100">
                                                <div class="fw-bold" id="commenter-name">{{ $comment->user_name }}
                                                </div>
                                                {{-- @dd(isset($editComment)) --}}
                                                @if (isset($editComment) && $editComment && isset($editCommentId) && $editCommentId === $comment->id)
                                                    <form
                                                        action="{{ route('updateComment', ['id' => $comment->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('put')
                                                        <div class="mb-3">
                                                            <input type="hidden" name="resolution_id"
                                                                value="{{ session('resolutionData')->id ?? $resolutionData->id }}">
                                                            <textarea class="form-control resolution-area" id="comment-area" rows="2" name="comment"
                                                                placeholder="write comment..." required>{{ old('comment', $comment->comment) }}</textarea>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary"><i
                                                                    class="bi bi-floppy"></i></button>
                                                            <a href="{{ route('comments', ['id' => $resolutionData->id]) }}"
                                                                class="btn btn-secondary"><i
                                                                    class="bi bi-x-circle"></i></a>
                                                        </div>
                                                    </form>
                                                @else
                                                    <small id="comment-description">
                                                        {{ $comment->comment }}
                                                    </small>
                                                @endif
                                                @if (Auth::check())
                                                    <div class="mt-3 reply-button">
                                                        <p class="text-gray-500 mb-2" id="get-reply-form">Reply</p>
                                                        <form
                                                            action="{{ route('storeReply', ['id' => $comment->id]) }}"
                                                            method="post" class="reply-form" id="reply-form">
                                                            @csrf
                                                            <div class="input-group mb-3">
                                                                <input type="hidden" name="resolution_id"
                                                                    value="{{ session('resolutionData')->id ?? $resolutionData->id }}">
                                                                <input type="text"
                                                                    class="form-control resolution-area"
                                                                    name="reply" placeholder="Your Idea...">
                                                                <button class="btn btn-outline-secondary"
                                                                    type="submit" id="button-addon2"><i
                                                                        class="bi bi-send"></i></button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                                @if ((session('replies') && session('replies')->count() > 0) || (isset($replies) && $replies->count() > 0))
                                                    <div class="comments-section mt-3">
                                                        <ul class="list-group">
                                                            @foreach (session('replies') ?? $replies as $key => $reply)
                                                                @if ($reply->comment_id == $comment->id)
                                                                    <li id="reply-{{ $reply->id }}"
                                                                        class="list-group-item reply-item d-flex justify-content-between align-items-start">
                                                                        <div class="ms-2 w-100">
                                                                            @if ($reply->reply_id)
                                                                                <div class="linked-reply"
                                                                                    data-reply-id="{{ $reply->reply_id->id }}">
                                                                                    <div class="d-flex gap-3">
                                                                                        <div>
                                                                                            <small
                                                                                                class="fw-bold">{{ $reply->reply_id->user_name }}</small>
                                                                                        </div>
                                                                                        <div>
                                                                                            <small>{{ $reply->reply_id->reply }}</small>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            <div class="fw-bold">
                                                                                {{ $reply->user_name }}</div>
                                                                            @if (isset($editReply) && $editReply && isset($editReplyId) && $editReplyId == $reply->id)
                                                                                <form
                                                                                    action="{{ route('updateReply', ['id' => $reply->id]) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('put')
                                                                                    <div class="mb-3">
                                                                                        <input type="hidden"
                                                                                            name="resolution_id"
                                                                                            value="{{ session('resolutionData')->id ?? $resolutionData->id }}">
                                                                                        <textarea class="form-control resolution-area" id="comment-area" rows="2" name="reply"
                                                                                            placeholder="write your reply..." required>{{ old('reply', $reply->reply) }}</textarea>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex gap-2 justify-content-end">
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary"><i
                                                                                                class="bi bi-floppy"></i></button>
                                                                                        <a href="{{ route('comments', ['id' => $resolutionData->id]) }}"
                                                                                            class="btn btn-secondary"><i
                                                                                                class="bi bi-x-circle"></i></a>
                                                                                    </div>
                                                                                </form>
                                                                            @else
                                                                                <div>
                                                                                    <small>
                                                                                        {{ $reply->reply }}
                                                                                    </small>
                                                                                </div>
                                                                            @endif

                                                                            @if (Auth::check())
                                                                                <div
                                                                                    class="nested-reply-button mt-2 d-flex justify-content-between align-items-center gap-3">
                                                                                    <p class="m-0"
                                                                                        id="get-nested-reply-form">
                                                                                        Reply
                                                                                    </p>
                                                                                    <form
                                                                                        action="{{ route('storeReply', ['id' => $comment->id]) }}"
                                                                                        method="post"
                                                                                        class="nested-reply-form w-100"
                                                                                        id="nested-reply-form">
                                                                                        @csrf
                                                                                        <div
                                                                                            class="input-group input-group-sm border-white">
                                                                                            <input type="hidden"
                                                                                                name="resolution_id"
                                                                                                value="{{ session('resolutionData')->id ?? $resolutionData->id }}">
                                                                                            <input type="hidden"
                                                                                                name="reply_id"
                                                                                                value="{{ $reply->id }}">
                                                                                            <input type="text"
                                                                                                class="form-control resolution-area"
                                                                                                name="reply"
                                                                                                placeholder="Write your reply...">
                                                                                            <button type="submit"
                                                                                                class="btn btn-outline-secondary text-white"><i
                                                                                                    class="bi bi-send"></i></button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        @if (Auth::check() && Auth::user()->id == $reply->user_id)
                                                                            <form
                                                                                action="{{ route('editReply', ['id' => $reply->id]) }}"
                                                                                method="get" class="mx-2">
                                                                                <input type="hidden"
                                                                                    name="resolution_id"
                                                                                    value="{{ $reply->resolution_id }}">
                                                                                <button
                                                                                    class="badge text-bg-warning rounded-pill p-2 border-0"><i
                                                                                        class="bi bi-pencil-square"></i></button>
                                                                            </form>
                                                                            <form
                                                                                action="{{ route('deleteReply', ['id' => $reply->id]) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('delete')
                                                                                <input type="hidden"
                                                                                    name="resolution_id"
                                                                                    value="{{ $reply->resolution_id }}">
                                                                                <button
                                                                                    class="badge text-bg-danger rounded-pill p-2 border-0"><i
                                                                                        class="bi bi-trash"></i></button>
                                                                            </form>
                                                                        @endif
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            @if (Auth::check() && Auth::user()->id == $comment->user_id)
                                                <form action="{{ route('editComment', ['id' => $comment->id]) }}"
                                                    method="get" class="mx-2">
                                                    <input type="hidden" name="resolution_id"
                                                        value="{{ $comment->resolution_id }}">
                                                    <button class="badge text-bg-warning rounded-pill p-2 border-0"><i
                                                            class="bi bi-pencil-square"></i></button>
                                                </form>
                                                <form action="{{ route('deleteComment', ['id' => $comment->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" name="resolution_id"
                                                        value="{{ $comment->resolution_id }}">
                                                    <button class="badge text-bg-danger rounded-pill p-2 border-0"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>
                    @endif
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
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const commentModal = document.getElementById('commentModal');
                const replyButtons = document.querySelectorAll('#get-reply-form');
                const nestedReplyButtons = document.querySelectorAll('#get-nested-reply-form');


                replyButtons.forEach(button => {
                    button.addEventListener('click', (event) => {
                        const replyForm = button.closest('.reply-button').querySelector('.reply-form');
                        if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                            replyForm.style.display = 'block';
                        } else {
                            replyForm.style.display = 'none';
                        }
                    });
                });

                nestedReplyButtons.forEach(button => {
                    button.addEventListener('click', (event) => {
                        const nestedReplyForm = button.closest('.nested-reply-button').querySelector(
                            '.nested-reply-form');
                        if (nestedReplyForm.style.display === 'none' || nestedReplyForm.style
                            .display === '') {
                            nestedReplyForm.style.display = 'block';
                        } else {
                            nestedReplyForm.style.display = 'none';
                        }
                    });
                });

                commentModal.addEventListener('show.bs.modal', (event) => {
                    // Button or element that triggered the modal
                    // const triggerElement = event.relatedTarget;
                    // const resolutionData = triggerElement.getAttribute('data-resolution');
                    // const resolutionN = triggerElement.getAttribute('data-name');
                    // const resolutionId = triggerElement.getAttribute('data-id') ||
                    //     {{ session('resolutionId') }};
                    // console.log(resolutionData);


                    // Set the hidden input field value
                    // const resolutionDescription = document.getElementById('blockquote-description');
                    // const resolutionName = document.getElementById('blockquote-name');
                    // resolutionDescription.textContent = resolutionData
                    // resolutionName.textContent = resolutionN

                    document.querySelectorAll('.linked-reply').forEach(function(linkedReply) {
                        linkedReply.addEventListener('click', function() {
                            // Get the ID of the reply to scroll to
                            const replyId = this.closest('.linked-reply').dataset.replyId;
                            const targetReply = document.getElementById(`reply-${replyId}`);
                            if (targetReply) {
                                targetReply.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });

                                // Add a highlight class
                                targetReply.classList.add('highlight');

                                // Remove the highlight after 2 seconds
                                setTimeout(() => {
                                    targetReply.classList.remove('highlight');
                                }, 2000);
                            }
                        });
                    });
                });



                @if (session('openModal') || @$openModal)
                    let modalElement = document.getElementById('commentModal');
                    let modal = new bootstrap.Modal(modalElement);
                    modal.show();
                @endif
            });
        </script>
        <script>
            function adjustButtonClass() {
                const button = document.getElementById('addResolutionButton');
                if (window.innerWidth <= 380) {
                    button.classList.add('btn-sm');
                } else {
                    button.classList.remove('btn-sm');
                }
            }

            // Adjust on page load
            document.addEventListener('DOMContentLoaded', adjustButtonClass);

            // Adjust on window resize
            window.addEventListener('resize', adjustButtonClass);
        </script>
    </x-slot:content>
</x-general>
