@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
@php
    $stats = [
        ['icon' => '🎉', 'label' => 'Eventos',        'count' => \App\Models\Evento::count(),          'route' => 'admin.eventos'],
        ['icon' => '🏄', 'label' => 'Actividades',    'count' => \App\Models\Actividad::count(),        'route' => 'admin.actividades'],
        ['icon' => '🏨', 'label' => 'Alojamientos',   'count' => \App\Models\Alojamiento::count(),      'route' => 'admin.alojamientos'],
        ['icon' => '🏖️', 'label' => 'Balnearios',    'count' => \App\Models\Balneario::count(),        'route' => 'admin.balnearios'],
        ['icon' => '🍽️', 'label' => 'Gastronómicos', 'count' => \App\Models\Gastronomico::count(),     'route' => 'admin.gastronomicos'],
        ['icon' => '🏢', 'label' => 'Complejos',      'count' => \App\Models\Complejo::count(),         'route' => 'admin.complejos'],
        ['icon' => '👥', 'label' => 'Usuarios',       'count' => \App\Models\User::count(),             'route' => 'admin.usuarios'],
        ['icon' => '🏷️', 'label' => 'Tipos',         'count' => \App\Models\Tipo::count(),             'route' => 'admin.tipos'],
        ['icon' => '🍴', 'label' => 'Tipos Gastron.', 'count' => \App\Models\TipoGastronomico::count(), 'route' => 'admin.tipo-gastronomico'],
        ['icon' => '📋', 'label' => 'Menús',          'count' => \App\Models\Menu::count(),             'route' => 'admin.menus'],
        ['icon' => '🏨', 'label' => 'Tipos Alojamiento', 'count' => \App\Models\TipoAlojamiento::count(), 'route' => 'admin.tipo-alojamiento'],
    ];
@endphp

<div class="stats-grid">
    @foreach($stats as $stat)
        <a href="{{ route($stat['route']) }}" class="stat-card">
            <div class="stat-icon">{{ $stat['icon'] }}</div>
            <div class="stat-value">{{ $stat['count'] }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
        </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Accesos rápidos</span>
    </div>
    <div class="card-body">
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            @foreach($stats as $stat)
                <a href="{{ route($stat['route']) }}" class="btn btn-secondary">
                    {{ $stat['icon'] }} {{ $stat['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
