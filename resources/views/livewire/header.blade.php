<div class="header">
    <div class="header-title">AVIAEE</div>

    <div class="account-container">
        <img src="{{ Vite::asset('resources/assets/account.png') }}" class="account-icon" onclick="toggleDropdown()">

        <div id="accountDropdown" class="dropdown">
            <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
            <div class="user-email"> {{ Auth::user()->email }}</div>
        <form method="POST" action="{{ route('logout') }}" >
            @csrf
             <flux:menu.item as="button" type="submit" class="logout-btn">
                {{ __('Log Out') }}
                </flux:menu.item>
            @csrf
        </form>
        </div>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById("accountDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.account-icon')) {
            let dropdown = document.getElementById("accountDropdown");
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
</script>