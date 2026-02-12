<div>
    <x-header />

    <div class="admin-container">
        <x-banner-categories>
            <a href="{{ route('manage') }}" class="cat {{ request()->routeIs('manage') ? 'active' : '' }}">
                ADMIN DASHBOARD
            </a>
        </x-banner-categories>

        <!-- STATS -->
        <div class="admin-stats">
            <div class="admin-card">
                <h3 class="admin-card-label">Total Posts</h3>
                <p class="admin-card-number">{{ $postsCount }}</p>
            </div>

            <div class="admin-card">
                <h3 class="admin-card-label">Total Comments</h3>
                <p class="admin-card-number">{{ $commentsCount }}</p>
            </div>

            <div class="admin-card">
                <h3 class="admin-card-label">Total Users</h3>
                <p class="admin-card-number">{{ $usersCount }}</p>
            </div>
        </div>

        <!-- MODULE LINKS -->
        <div class="admin-modules">
            <a class="admin-module module-posts">
                <i class="bx bx-news admin-icon"></i>
                Manage Posts
            </a>

            <a class="admin-module module-comments">
                <i class="bx bx-message-dots admin-icon"></i>
                Manage Comments
            </a>

            <a class="admin-module module-users">
                <i class="bx bx-group admin-icon"></i>
                Manage Users
            </a>
        </div>
    </div>
</div>
