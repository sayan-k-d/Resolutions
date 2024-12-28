<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Reply;
use App\Models\Resolution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Auth;

class ResolutionController extends Controller
{
    public function getResolutions()
    {
        $resolutions = Resolution::where('status', 'public')->orderBy('created_at', 'DESC')->get();
        $userName = auth()->check() ? auth()->user()->name : null;
        $likes = auth()->check() ? Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray() : [];
        // dd($likes);
        return view('Dashboard.dashboard', ['resolutions' => $resolutions, 'name' => $userName, 'likes' => $likes]);
    }

    public function getSearchs(Request $request)
    {
        $search = $request->search;
        $resolutions = Resolution::where('description', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->get();

        $userName = auth()->check() ? auth()->user()->name : null;
        $likes = auth()->check() ? Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray() : [];
        // dd($request->search);
        return view('Dashboard.dashboard', ['resolutions' => $resolutions, 'likes' => $likes, 'name' => $userName, 'search' => $request->search]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'status' => 'nullable|in:checked',
        ]);
        if ($request->has('status') && $request->status == 'checked') {
            $status = 'private';
        } else {
            $status = 'public';
        }

        Resolution::create([
            'name' => auth()->user() ? auth()->user()->name : 'Guest',
            'description' => $data['description'],
            'status' => $status,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('message', 'Resolution saved');
    }

    public function storeLikes($id)
    {
        $resolution = Resolution::find($id);
        $like = Like::where('user_id', auth()->id())->where('resolution_id', $id)->first();
        if (!$like) {
            // dd(Like::where('user_id', auth()->id())->where('resolution_id', $id)->first());
            $totalLikes = $resolution->likes;
            $resolution->likes = $totalLikes + 1;
            $resolution->save();
            Like::create([
                'user_id' => auth()->id(),
                'resolution_id' => $id,
                'status' => 1,
            ]);
        } else {
            $totalLikes = $resolution->likes;
            $resolution->likes = $totalLikes - 1;
            $resolution->save();

            $like->delete();
        }
        $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
        $likes = Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray();
        // dd($likes);
        return redirect()->back();
        // ->with(['likes' => $likes, 'resolutions' => $resolutions]);
    }

    public function storeComments(Request $request, $id)
    {
        $data = $request->validate([
            'comment' => 'required|string',
        ]);
        // dd(auth()->user()->name);
        Comment::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'resolution_id' => $id,
            'comment' => $data['comment'],
        ]);

        $resolution = Resolution::find($id);
        $totalComments = $resolution->comments;
        $resolution->comments = $totalComments + 1;
        $resolution->save();

        $comments = Comment::where('resolution_id', $id)->orderBy('created_at', 'DESC')->get();
        $replies = Reply::where('resolution_id', $id)->orderBy('created_at', 'DESC')->get();
        $resolution = Resolution::find($id);
        $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
        $userName = auth()->check() ? auth()->user()->name : null;

        return redirect()->back()->with('openModal', true)->with('resolutionId', $id)->with('comments', $comments)->with('replies', $replies)->with('resolutionData', $resolution)->with('resolutions', $resolutions)->with('name', $userName);
    }

    public function getComments($id)
    {
        $comments = Comment::where('resolution_id', $id)->orderBy('created_at', 'DESC')->get();
        $replies = Reply::where('resolution_id', $id)->orderBy('created_at', 'DESC')->get();
        foreach ($replies as $reply) {
            $reply->reply_id = Reply::find($reply->reply_id);
        }
        // dd($replies);
        $resolution = Resolution::find($id);
        $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
        $userName = auth()->check() ? auth()->user()->name : null;
        // dd($resolution);
        return redirect()->route('dashboard')->with('comments', $comments)->with('replies', $replies)->with('resolutionData', $resolution)->with('resolutions', $resolutions)->with('name', $userName)->with('openModal', true);
    }

    public function editComments(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comments = Comment::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
        $replies = Reply::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
        $likes = auth()->check() ? Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray() : [];
        foreach ($replies as $reply) {
            $reply->reply_id = Reply::find($reply->reply_id);
        }
        $resolutionData = Resolution::find($request->resolution_id);
        $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
        $editComment = true;
        $editCommentId = $comment->id;
        $openModal = true;
        if (auth()->id() === $comment->user_id) {
            return view('Dashboard.dashboard', compact('openModal', 'comment', 'editCommentId', 'comments', 'replies', 'editComment', 'resolutionData', 'resolutions', 'likes'));
            // return redirect()->back()->with(['openModal' => true, 'comments' => $comments, 'comment' => $comment, 'editCommentId' => $comment->id, 'replies' => $replies, 'editComment' => true, 'resolutionData' => $resolution, 'resolutions' => $resolutions, 'likes' => $likes]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to edit this Comment.']);
    }

    public function updateComments(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);
        $comment = Comment::find($id);
        if ($comment && auth()->id() === $comment->user_id) {
            $comment->comment = $request->comment;
            $comment->save();

            $comments = Comment::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            $replies = Reply::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            $likes = auth()->check() ? Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray() : [];
            foreach ($replies as $reply) {
                $reply->reply_id = Reply::find($reply->reply_id);
            }
            $resolution = Resolution::find($request->resolution_id);
            $resolutions = Resolution::orderBy('created_at', 'DESC')->get();

            return redirect()->route('dashboard')->with(['openModal' => true, 'comments' => $comments, 'replies' => $replies, 'resolutionData' => $resolution, 'resolutions' => $resolutions, 'likes' => $likes]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to Update this Resolution.']);
    }

    public function deleteComments(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if (auth()->id() === $comment->user_id) {
            $comment->delete();
            $comments = Comment::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            $replies = Reply::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            foreach ($replies as $reply) {
                $reply->reply_id = Reply::find($reply->reply_id);
            }
            $resolution = Resolution::find($request->resolution_id);
            // dd($resolution);
            $totalComments = $resolution->comments;
            $resolution->comments = $totalComments - 1;
            $resolution->save();
            $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
            $userName = auth()->check() ? auth()->user()->name : null;
            return redirect()->back()->with(['openModal' => true, 'comments' => $comments, 'replies' => $replies, 'resolutionData' => $resolution, 'resolutions' => $resolutions, 'name' => $userName]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to delete this Comment.']);
    }

    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);
        $resolution_id = $request->resolution_id;
        $reply_id = $request->reply_id ? $request->reply_id : null;
        Reply::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'comment_id' => $id,
            'resolution_id' => $resolution_id,
            'reply_id' => $reply_id,
            'reply' => $request->reply,
        ]);

        $resolution = Resolution::find($resolution_id);
        $comments = Comment::where('resolution_id', $resolution_id)->orderBy('created_at', 'DESC')->get();
        $replies = Reply::where('resolution_id', $resolution_id)->orderBy('created_at', 'DESC')->get();
        foreach ($replies as $reply) {
            $reply->reply_id = Reply::find($reply->reply_id);
        }
        $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
        $userName = auth()->check() ? auth()->user()->name : null;

        return redirect()->back()->with('openModal', true)->with('resolutionId', $resolution_id)->with('comments', $comments)->with('replies', $replies)->with('resolutionData', $resolution)->with('resolutions', $resolutions)->with('name', $userName);
    }

    public function editReplies(Request $request, $id)
    {
        $reply = Reply::findOrFail($id);
        $comments = Comment::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
        $replies = Reply::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
        $likes = auth()->check() ? Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray() : [];
        foreach ($replies as $reply) {
            $reply->reply_id = Reply::find($reply->reply_id);
        }
        $resolutionData = Resolution::find($request->resolution_id);
        $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
        $editReply = true;
        $editReplyId = $reply->id;
        $openModal = true;
        if (auth()->id() === $reply->user_id) {
            return view('Dashboard.dashboard', compact('openModal', 'reply', 'editReplyId', 'comments', 'replies', 'editReply', 'resolutionData', 'resolutions', 'likes'));
            // return redirect()->back()->with(['openModal' => true, 'comments' => $comments, 'comment' => $comment, 'editCommentId' => $comment->id, 'replies' => $replies, 'editComment' => true, 'resolutionData' => $resolution, 'resolutions' => $resolutions, 'likes' => $likes]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to edit this Comment.']);
    }
    public function updateReplies(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);
        $reply = Reply::find($id);
        if ($reply && auth()->id() === $reply->user_id) {
            $reply->reply = $request->reply;
            $reply->save();

            $comments = Comment::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            $replies = Reply::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            $likes = auth()->check() ? Like::where('user_id', auth()->id())->pluck('resolution_id')->toArray() : [];
            foreach ($replies as $reply) {
                $reply->reply_id = Reply::find($reply->reply_id);
            }
            $resolution = Resolution::find($request->resolution_id);
            $resolutions = Resolution::orderBy('created_at', 'DESC')->get();

            return redirect()->route('dashboard')->with(['openModal' => true, 'comments' => $comments, 'replies' => $replies, 'resolutionData' => $resolution, 'resolutions' => $resolutions, 'likes' => $likes]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to Update this Reply.']);
    }
    public function deleteReply(Request $request, $id)
    {
        $reply = Reply::findOrFail($id);
        if (auth()->id() === $reply->user_id) {
            $reply->delete();
            $comments = Comment::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            $replies = Reply::where('resolution_id', $request->resolution_id)->orderBy('created_at', 'DESC')->get();
            foreach ($replies as $reply) {
                $reply->reply_id = Reply::find($reply->reply_id);
            }
            $resolution = Resolution::find($request->resolution_id);
            $resolutions = Resolution::orderBy('created_at', 'DESC')->get();
            $userName = auth()->check() ? auth()->user()->name : null;
            return redirect()->back()->with(['openModal' => true, 'comments' => $comments, 'replies' => $replies, 'resolutionData' => $resolution, 'resolutions' => $resolutions, 'name' => $userName]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to delete this Reply.']);
    }

    public function updateResolutionStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:checked',
        ]);
        if ($request->has('status') && $request->status == 'checked') {
            $status = 'private';
        } else {
            $status = 'public';
        }
        $resolution = Resolution::findOrFail($id);
        if ($resolution) {
            $resolution->status = $status;
            $resolution->save();
            return redirect()->back();
        }
    }

    public function deleteResolution($id)
    {
        $resolution = Resolution::find($id);
        if ($resolution && auth()->id() === $resolution->user_id) {
            $resolution->delete();
            return redirect()->back();
        }
        return redirect()->back()->with(['error' => 'You are not authorized to delete this Resolution.']);
    }

    public function editResolution($id)
    {
        $resolution = Resolution::find($id);
        $user = Auth::user();
        $resolutions = Resolution::where('user_id', $user->id)->get();
        if ($resolution && auth()->id() === $resolution->user_id) {
            return view('Dashboard.layout.profile', ['editResolutionId' => $resolution->id,
                'description' => $resolution->description,
                'editFlag' => true,
                'user' => $user,
                'resolutions' => $resolutions]);
        }
        return redirect()->back()->with(['error' => 'You are not authorized to edit this Resolution.']);
    }

    public function updateResolution(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ]);
        $resolution = Resolution::find($id);
        if ($resolution && auth()->id() === $resolution->user_id) {
            $resolution->description = $request->description;
            $resolution->save();
            return redirect()->route('profile');
        }
        return redirect()->back()->with(['error' => 'You are not authorized to Update this Resolution.']);
    }
}
