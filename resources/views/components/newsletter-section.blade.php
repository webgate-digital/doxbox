<section class="section section--newsletter">
    <div class="container flex flex-col align-center">
        <h2 class="text-heading-2xs text-center">Odoberaj DOXBOX Newsletter</h2>
        <h3 class="text-body-l text-center">Informácie o zľavách, nových produktoch  a ďalších novinkách</h3>
        <div class="text-body-xs text-center">
            Prihlásením sa k odberu súhlasíš s
            <a class="text-primary" href="#">
                spracovaním osobných údajov
            </a>
            na marketingové účely.
        </div>
        <div class="mx-auto md:mt-12 mt-4">
            <label for="email" class="label text-gray-50 text-body-s">
                Tvoja e-mailová adresa
            </label>
            <form action="{{ route('newsletter.store') }}" method="POST">
                @csrf
                <div class="md:flex">
                    <input id="email" type="email" class="input rounded-lg p-4 disabled:opacity-50 disabled:cursor-not-allowed" placeholder="Vyplň e-mail" required name="email">
                    <button class="w-full md:w-auto justify-center button button--primary !inline-flex items-center md:ml-6 mt-4 md:mt-0 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed" type="submit">
                        <svg class="animate-spin hidden mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>
                            Odoberať
                        </span>
                    </button>
                </div>
                <div class="message text-body-xs mt-4"></div>
            </form>
        </div>
    </div>
</section>