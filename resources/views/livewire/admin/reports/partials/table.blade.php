<table class="admin-table">
    <thead>
        <tr>
            <th>Usuario Reportado</th>
            <th>Contenido</th>
            <th>Motivo</th>
            <th>Reportado por</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($reports as $report)
            <tr>
                <td>
                    @if ($report->reportable)
                        <a href="{{ route('user.profile', $report->reportable->user->username) }}" class="admin-link">
                            {{ $report->reportable->user->name }}
                        </a>
                    @else
                        <span class="text-gray-400">Post eliminado</span>
                    @endif
                </td>

                <td>
                    @if ($report->reportable)
                        @php
                            $reportable = $report->reportable;
                        @endphp

                        @if ($reportable instanceof \App\Models\Post)
                            <a href="{{ route('posts.show', $reportable->id) }}" class="admin-link">
                                {{ \Illuminate\Support\Str::limit($reportable->content, 60) }}
                            </a>
                        @elseif ($reportable instanceof \App\Models\PostComment)
                            <a href="{{ route('posts.show', $reportable->post_id) }}#comment-{{ $reportable->id }}"
                                class="admin-link">
                                {{ \Illuminate\Support\Str::limit($reportable->content, 60) }}
                            </a>
                        @else
                            —
                        @endif
                    @else
                        —
                    @endif
                </td>

                <td>
                    {{ $report->reason }}
                </td>

                <td>
                    <a href="{{ route('user.profile', $report->reporter->username) }}" class="admin-link">
                        {{ $report->reporter->name }}
                    </a>
                </td>

                <td class="admin-actions">
                    <button wire:click="confirmAction({{ $report->id }}, 'accept')" class="admin-accept-btn">
                        Aceptar
                    </button>

                    <button wire:click="confirmAction({{ $report->id }}, 'dismiss')" class="admin-dismiss-btn">
                        Descartar
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-400">
                    No hay reportes pendientes
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
