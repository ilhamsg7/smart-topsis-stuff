<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-md">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('image/DARK WISHUB LOGO.png') }}" alt="Logo" width="75" height="24" class="d-inline-block align-text-top img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ ($active === "home") ? 'active' : '' }}" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active === "criteria") ? 'active' : '' }}" href="{{ route('criteria.index') }}">Criteria</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active === "alternatives") ? 'active' : '' }}" href="{{ route('alternatives.index') }}">Alternative</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active === "combine") ? 'active' : '' }}" href="{{ route('combine.index') }}">Combine</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active === "result") ? 'active' : '' }}" href="{{ route('result.index') }}">Result</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
