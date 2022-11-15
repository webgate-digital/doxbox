<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-2 rounded-lg bg-yellow-100">
            <div class="flex items-center justify-center gap-8 flex-wrap">
                <div class="ml-3 text-black cookie-consent__message">
                    {!! trans('cookie-consent::texts.message') !!}
                </div>
                <button class="button button--sm button--primary rounded-lg button--inline js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-yellow-800 bg-yellow-400 hover:bg-yellow-300">
                    {{ trans('cookie-consent::texts.agree') }}
                </button>
            </div>
        </div>
    </div>
</div>
