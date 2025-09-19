@php
    $segments = request()->segments();
    $breadcrumbs = [['label' => 'Dashboard', 'url' => route('admin.dashboard')]];

    $path = '';
    foreach ($segments as $segment) {
        $path .= '/' . $segment;
        $label = ucfirst(str_replace('-', ' ', $segment));
        $url = url($path);

        switch ($segment) {
            case 'elections':
                $label = 'Elections';
                $url = route('admin.elections.index');
                break;
            case 'voters':
                $label = 'Voters';
                // If we're on the voters page, the URL should link to the voters list for the current election.
            if (isset($election)) {
                $url = route('admin.voters.index', $election);
            }
            break;
        case 'positions':
            $label = 'Positions';
            // If we're on the positions page, the URL should link to the positions list for the current election.
                if (isset($election)) {
                    $url = route('admin.positions.index', $election);
                }
                break;
        case 'results':
                $label = 'Results';
                // The URL for results is handled by the main view. The breadcrumb link is not needed.
                $url = null;
                break;

         case 'candidates':
                $label = 'Candidates';
                // If we're on the candidates page, the URL should link to the candidates list for the current position.
                if (isset($election) && isset($position)) {
                    $url = route('admin.elections.positions.candidates.index', [$election, $position]);
                }
                break;
        case 'create':
            $label = 'Create';
            break;
        case 'import':
            $label = 'Import Voters';
            break;
        default:
            // If the segment is a numeric ID, we'll try to use the election's title.
            if (is_numeric($segment) && isset($election)) {
                $label = $election->title;
                $url = route('admin.elections.show', $election);
            }
            break;
    }

    if ($segment !== 'admin') {
        $breadcrumbs[] = ['label' => $label, 'url' => $url];
    }
}

// Set the last breadcrumb to be a span
$last = array_pop($breadcrumbs);
$breadcrumbs[] = ['label' => $last['label'], 'url' => null];
@endphp

<nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    @foreach ($breadcrumbs as $crumb)
        @if ($crumb['url'])
            <a href="{{ $crumb['url'] }}" class="hover:text-gray-700 transition-colors">{{ $crumb['label'] }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        @else
            <span class="font-medium text-gray-700">{{ $crumb['label'] }}</span>
        @endif
    @endforeach
</nav>
