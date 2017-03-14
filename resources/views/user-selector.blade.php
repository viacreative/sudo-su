<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/sudo-su/css/app.css">

<div class="sudoSu">
    <div class="sudoSu__btn {{ $hasSudoed ? 'sudoSu__btn--hasSudoed' : '' }}" id="sudosu-js-btn">
        <i class="fa fa-user-secret" aria-hidden="true"></i>
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
            
            <form action="{{ route('sudosu.return') }}" method="post">
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