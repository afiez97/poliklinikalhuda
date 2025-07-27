<div class="language-switcher">
    <div class="dropdown">
        <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-globe me-2"></i>
            {{ $availableLocales[$currentLocale] }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
            @foreach($availableLocales as $locale => $name)
                @if($locale !== $currentLocale)
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('locale.change', $locale) }}">
                            <span class="flag-icon flag-icon-{{ $locale === 'ms' ? 'my' : $locale }} me-2"></span>
                            {{ $name }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<style>
.language-switcher {
    display: inline-block;
}

.language-switcher .btn {
    border-color: rgba(255,255,255,0.3);
    color: rgba(255,255,255,0.9);
}

.language-switcher .btn:hover {
    background-color: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
    color: white;
}

.flag-icon {
    width: 20px;
    height: 15px;
    background-size: cover;
    display: inline-block;
    border-radius: 2px;
}

.flag-icon-en {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI2MDAiIHZpZXdCb3g9IjAgMCAxMjAwIDYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cGF0aCBmaWxsPSIjMDAyNDdkIiBkPSJtMCwwaDEyMDB2NjAwaC0xMjAweiIvPgogIDxwYXRoIGZpbGw9IiNmZmYiIGQ9Im0wLDBoMTIwMHY2MGgtMTIwMHptMCwxMjBoMTIwMHY2MGgtMTIwMHptMCwxMjBoMTIwMHY2MGgtMTIwMHptMCwxMjBoMTIwMHY2MGgtMTIwMHptMCwxMjBoMTIwMHY2MGgtMTIwMHptMCwxMjBoMTIwMHY2MGgtMTIwMHptMCwxMjBoMTIwMHY2MGgtMTIwMHoiLz4KICA8cGF0aCBmaWxsPSIjY2YxNDJiIiBkPSJtMCwwaDEyMDB2NjBoLTEyMDB6bTAsMTIwaDEyMDB2NjBoLTEyMDB6bTAsMTIwaDEyMDB2NjBoLTEyMDB6bTAsMTIwaDEyMDB2NjBoLTEyMDB6bTAsMTIwaDEyMDB2NjBoLTEyMDB6bTAsMTIwaDEyMDB2NjBoLTEyMDB6Ii8+CiAgPHBhdGggZmlsbD0iIzAwMjQ3ZCIgZD0ibTAsMGg0ODB2MzAwaC00ODB6Ii8+CiAgPHBhdGggZmlsbD0iI2ZmZiIgZD0ibTIyNiw2MGw2LDE4aDE5bC0xNSwxMSA2LDE4LTE2LTExLTE2LDExIDYtMTgtMTUtMTEgMTktMXoiLz4KICA8cGF0aCBmaWxsPSIjY2YxNDJiIiBkPSJtMjI2LDEzOGwtNjAsNDB2LTQwem02MC00MHYtNDB6bTAtNDBoLTYwbDYwLDQwem0tNjAsNDBoNjB2NDB6Ii8+Cjwvc3ZnPgo=');
}

.flag-icon-my {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI2MDAiIHZpZXdCb3g9IjAgMCAxMjAwIDYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cGF0aCBmaWxsPSIjZGMxNDNjIiBkPSJtMCwwaDEyMDB2NjAwaC0xMjAweiIvPgogIDxwYXRoIGZpbGw9IiNmZmYiIGQ9Im0wLDQzaDEyMDB2NDNoLTEyMDB6bTAsODZoMTIwMHY0M2gtMTIwMHptMCw4NmgxMjAwdjQzaC0xMjAwem0wLDg2aDEyMDB2NDNoLTEyMDB6bTAsODZoMTIwMHY0M2gtMTIwMHptMCw4NmgxMjAwdjQzaC0xMjAwem0wLDg2aDEyMDB2NDNoLTEyMDB6Ii8+CiAgPHBhdGggZmlsbD0iIzAwNmZjZSIgZD0ibTAsMGg2MDB2MzAwaC02MDB6Ii8+CiAgPHBhdGggZmlsbD0iI2ZmZiIgZD0ibTMwMCwxNTBjMCw0MS40MjEtMzMuNTc5LDc1LTc1LDc1cy03NS0zMy41NzktNzUtNzUgMzMuNTc5LTc1IDc1LTc1IDc1LDMzLjU3OSA3NSw3NXptLTI1LDBjMCwyNy42MTQtMjIuMzg2LDUwLTUwLDUwcy01MC0yMi4zODYtNTAtNTAgMjIuMzg2LTUwIDUwLTUwIDUwLDIyLjM4NiA1MCw1MHoiLz4KICA8cGF0aCBmaWxsPSIjZmZkNzAwIiBkPSJtMzAwLDE1MGMwLDQxLjQyMS0zMy41NzksNzUtNzUsNzVzLTc1LTMzLjU3OS03NS03NSAzMy41NzktNzUgNzUtNzUgNzUsMzMuNTc5IDc1LDc1em0tMjUsMGMwLDI3LjYxNC0yMi4zODYsNTAtNTAsNTBzLTUwLTIyLjM4Ni01MC01MCAyMi4zODYtNTAgNTAtNTAgNTAsMjIuMzg2IDUwLDUweiIvPgo8L3N2Zz4K');
}

.dropdown-menu .dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>
