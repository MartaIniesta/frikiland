<table class="admin-table">
    <thead>
        <tr>
            <th class="col-user">User</th>
            <th class="col-role">Role</th>
            <th class="col-posts">Posts</th>
            <th class="col-comments">Comments</th>
            <th class="col-reports">Reports</th>
            <th class="col-date">Joined</th>
            <th class="col-action">Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>
                    <a href="{{ route('user.profile', $user->username) }}" class="admin-link">
                        <div class="wrap-user-admin">
                            <img src="{{ asset($user->avatar) }}" class="profile-avatar">
                            <div class="profile-user-admin">
                                {{ $user->name }}
                                <span>{{ '@' . $user->username }}</span>
                            </div>
                        </div>
                    </a>
                </td>

                <td>
                    <select wire:change="updateRole({{ $user->id }}, $event.target.value)" class="admin-select">
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected($user->hasRole($role))>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <span class="media-indicator">
                        <i class="bx bx-news"></i>
                        {{ $user->posts_count }}
                    </span>
                </td>

                <td>
                    <span class="media-indicator">
                        <i class="bx bx-message-dots"></i>
                        {{ $user->comments_count }}
                    </span>
                </td>

                <td>
                    <a href="{{ route('admin.users.reports', $user) }}"
                        class="a-reports {{ $user->reports_count > 0 ? 'has-reports' : '' }}">
                        Reports ({{ $user->reports_count }})
                    </a>
                </td>

                <td>
                    {{ $user->created_at->format('d/m/Y') }}
                </td>

                <td>
                    @if ($user->id !== auth()->id())
                        <button wire:click="confirmDelete({{ $user->id }})" class="admin-delete-btn">
                            <i class="bx bx-trash"></i>
                        </button>
                    @else
                        <span style="color:#aaa;">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
