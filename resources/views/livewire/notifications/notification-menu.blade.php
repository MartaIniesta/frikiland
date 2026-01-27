<div class="notification-menu" x-data="{ open: false }">
    <button @click="open = !open" class="notification-btn">
        <i class="bx bx-bell notification-icon"></i>

        {{-- Badge --}}
        <span class="notification-badge">3</span>
    </button>

    <div x-show="open" x-transition @click.away="open = false" class="notification-dropdown">
        <p class="dropdown-title">Notificaciones</p>

        <ul class="notification-list">
            <li class="notification-item">Usuario X comentó tu post</li>
            <li class="notification-item">Usuario Y te siguió</li>
            <li class="notification-item">Nueva respuesta a tu comentario</li>
        </ul>

        <a href="{{ route('notifications.index') }}" class="view-more">
            Ver más →
        </a>
    </div>
</div>
