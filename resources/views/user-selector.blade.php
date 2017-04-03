<link rel="stylesheet" href="/sudo-su/css/app.css">

<div class="sudoSu">
    <div class="sudoSu__btn {{ $hasSudoed ? 'sudoSu__btn--hasSudoed' : '' }}" id="sudosu-js-btn">
        <img width="12" height="14" alt="User secret icon" aria-hidden="true" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAABF1BMVEUAAAD////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////jvJXZAAAAXHRSTlMAAQIDBAYHCAsMDRAUFRcaHB4gIiMkKCkqKy0wNDo8PT4/QUVGSUxQUVRZYmxvcHF3eHt8foKGiIyOj5eYm6Clpqqrrbm6vsDFx8jMzs/V2dze5Obr7e/x9ff7/V2zNpYAAAD5SURBVBgZZcELI8JQGAbgdxsp5prILeS+QhIhJHItISqr9///DjvfdrR4Hmhm3ARik/jnlq1siVzDH0v0dSwMemJgA2FGktpzDH37LkMaSQSMK4Z9p/ErctwunlZab5cn11w0ELLSNAADwAwt9Nl2mgtAfgootW17FNoLyc+dQ77v5ujZg5bigCi0IYY9QjPvSB7U6DmvkNxG4IieVRTJOdzTE4eIUsnDrGdg9Oh5gNikUgPOEhijGIFyQWGi6WCZYhZKnWIiwhtkKbagdCjWE3RRpXCguBQFhxzvUWSgVCley2SBvhSUefq6ZJfiw4KYLn8xpJEbBvAD4nJnwFNQ0yUAAAAASUVORK5CYII="
    </div>
    
    <div class="sudoSu__interface {{ $hasSudoed ? 'sudoSu__interface--hasSudoed' : '' }} hidden" id="sudosu-js-interface">
        @if ($hasSudoed)
            <div class="sudoSu__infoLine">
                You are using account: <span>{{ $currentUser->name }}</span>
            </div>
            
            @if ($originalUser)
                <div class="sudoSu__infoLine">
                    You are logged in as: <span>{{ $originalUser->name }}</span>
                </div>
            @endif
            
            <form action="{{ route('sudosu.logout') }}" method="post">
                {!! csrf_field() !!}
                <input type="submit" class="sudoSu__resetBtn" value="{{ $originalUser ? 'Return to original user' : 'Log out' }}">
            </form>
        @endif

        <form action="{{ route('sudosu.login_as_user') }}" method="post">
            <select name="userId" onchange="this.form.submit()">
                <option disabled selected>Sudo Su</option>

                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            
            {!! csrf_field() !!}

            <input type="hidden" name="originalUserId" value="{{ $originalUser->id ?? null }}">
        </form>
    </div>
</div>

<script>
    const btn = document.getElementById('sudosu-js-btn');
    const element = document.getElementById('sudosu-js-interface');

    btn.addEventListener('click', event => element.classList.toggle('hidden'));
</script>
