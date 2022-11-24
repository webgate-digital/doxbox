@extends('layout', ['ogTitle' => $translations['cart.checkout_title']['text']])

@section('content')

    @include('components.order_loading', ['text' => $translations['general.loading']['text']])

    @if($errors->any() || request()->get('error', null))
        <div class="alert alert--danger">
            {{$translations['general.form_validation_error']['text']}}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert--danger">
            {{session('error')}}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert--success">
            {{session('success')}}
        </div>
    @endif

    @include('components.cart_steps')

    @if($cart['voucher'])
        <form action="{{route(locale().'.remove_voucher')}}" id="remove-voucher-form" method="post">
            @csrf
            @method('delete')
        </form>
    @endif

    <section class="section">
        <div class="container">
            <h1 class="h1">
                {{$translations['cart.checkout_title']['text']}}
            </h1>
            <form action="{{route(locale().'.cart.order')}}" method="post" id="checkout-form">
                @csrf
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full lg:w-1/2 px-4">
                        <div>
                            <label class="checkout-form--label"
                                   for="name">{{$translations['cart.fullname']['text']}}
                                *</label>
                            <input type="text" name="name" id="name" class="checkout-form--input"

                                   value="{{old('name', $user ? $user['name'] : null)}}">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="flex flex-wrap -mx-4">
                            <div class="w-full lg:w-1/2 px-4">
                                <label class="checkout-form--label"
                                       for="email">E-mail
                                    *</label>
                                <input type="text" id="email" name="email"
                                       class="checkout-form--input"
                                       value="{{old('email', $user ? $user['email'] : null)}}"
                                       @if($user) readonly @endif>
                                @error('email')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="w-full lg:w-1/2 px-4">
                                <label class="checkout-form--label" for="phone">{{$translations['cart.phone']['text']}}
                                    *</label>
                                <input type="text" id="phone" name="phone" class="checkout-form--input"

                                       value="{{old('phone', $user ? $user['phone'] : null)}}">
                                @error('phone')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-4">
                            <div class="w-full lg:w-2/3 px-4">
                                <label class="checkout-form--label"
                                       for="street">{{$translations['cart.street']['text']}}
                                    *</label>
                                <input type="text" id="street" name="street" class="checkout-form--input"

                                       value="{{old('street', $user ? $user['street'] : null)}}">
                                @error('street')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="w-full lg:w-1/3 px-4">
                                <label class="checkout-form--label"
                                       for="house_number">{{$translations['cart.house_number']['text']}}
                                    *</label>
                                <input type="text" id="house_number" name="house_number" class="checkout-form--input"

                                       value="{{old('house_number', $user ? $user['house_number'] : null)}}">
                                @error('house_number')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-4">
                            <div class="w-full lg:w-1/2 px-4">
                                <label class="checkout-form--label" for="city">{{$translations['cart.city']['text']}}
                                    *</label>
                                <input type="text" id="city" name="city" class="checkout-form--input"

                                       value="{{old('city', $user ? $user['city'] : null)}}">
                                @error('city')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="w-full lg:w-1/2 px-4">
                                <label class="checkout-form--label" for="zip">{{$translations['cart.zip']['text']}}
                                    *</label>
                                <input type="text" id="zip" name="zip" class="checkout-form--input"

                                       value="{{old('zip', $user ? $user['zip'] : null)}}">
                                @error('zip')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="state" value="">
                        <div>
                            <label class="checkout-form--label" for="country">{{$translations['cart.country']['text']}}
                                *</label>
                            <input type="hidden" name="country"
                                   value="{{old('country', $shippingCountry['name'])}}">
                            <input type="text" id="country" name=""
                                   class="checkout-form--input"

                                   disabled
                                   readonly
                                   value="{{old('country', $shippingCountry['name'])}}">
                            <small class="">{{$translations['cart.country_change']['text']}}
                                <a href="{{route(locale() . '.cart.shipping')}}"
                                   class="text-secondary">{{$translations['cart.shipping_and_payment_title']['text']}}</a></small>
                            @error('country')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="mb-4 mt-8">
                            <a href="#collapseCompany" class="flex items-center collapsed" data-toggle="collapse"
                               aria-expanded="false">
                                <h3 class="h3 !mb-0">{{$translations['cart.company_billing']['text']}}</h3>
                                <img src="{{asset('images/icons/expand_more_black_24dp.svg')}}" class="ml-4"
                                     alt="{{$translations['cart.company_billing']['text']}}">
                            </a>
                        </div>
                        <div aria-expanded="false" id="collapseCompany"
                             class="collapse @if($errors->has('company_name') || $errors->has('company_id') || $errors->has('company_tax_id') || $errors->has('company_vat_id') || $errors->has('company_address') || $errors->has('company_city') || $errors->has('company_zip') || $errors->has('company_state') || $errors->has('company_country')) show @endif">
                            <div>
                                <label class="checkout-form--label"
                                       for="company_name">{{$translations['cart.company_name']['text']}}</label>
                                <input type="text" id="company_name" name="company_name"
                                       class="checkout-form--input"

                                       value="{{old('company_name', $user ? $user['company_name'] : null)}}">
                                @error('company_name')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="flex flex-wrap -mx-4">
                                <div class="w-full lg:w-1/3 px-4">
                                    <label class="checkout-form--label"
                                           for="company_id">{{$translations['general.company_id']['text']}}</label>
                                    <input type="text" id="company_id" name="company_id"
                                           class="checkout-form--input"

                                           value="{{old('company_id', $user ? $user['company_id'] : null)}}">
                                    @error('company_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-1/3 px-4">
                                    <label class="checkout-form--label"
                                           for="company_tax_id">{{$translations['general.company_tax_id']['text']}}</label>
                                    <input type="text" id="company_tax_id" name="company_tax_id"
                                           class="checkout-form--input"

                                           value="{{old('company_tax_id', $user ? $user['company_tax_id'] : null)}}">
                                    @error('company_tax_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-1/3 px-4">
                                    <label class="checkout-form--label"
                                           for="company_vat_id">{{$translations['general.company_vat_id']['text']}}</label>
                                    <input type="text" id="company_vat_id" name="company_vat_id"
                                           class="checkout-form--input"

                                           value="{{old('company_vat_id', $user ? $user['company_vat_id'] : null)}}">
                                    @error('company_vat_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="checkout-form--label"
                                       for="company_address">{{$translations['cart.street']['text']}}
                                    , {{$translations['cart.house_number']['text']}}</label>
                                <input type="text" id="company_address" name="company_address"
                                       class="checkout-form--input"

                                       value="{{old('company_address', $user ? $user['company_address'] : null)}}">
                                @error('company_address')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div>
                                <label class="checkout-form--label"
                                       for="company_city">{{$translations['cart.city']['text']}}</label>
                                <input type="text" id="company_city" name="company_city"
                                       class="checkout-form--input"

                                       value="{{old('company_city', $user ? $user['company_city'] : null)}}">
                                @error('company_city')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div>
                                <label class="checkout-form--label"
                                       for="company_zip">{{$translations['cart.zip']['text']}}</label>
                                <input type="text" id="company_zip" name="company_zip"
                                       class="checkout-form--input"

                                       value="{{old('company_zip', $user ? $user['company_zip'] : null)}}">
                                @error('company_zip')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <input type="hidden" name="company_state" value="">
                            <div>
                                <label class="checkout-form--label"
                                       for="company_country">{{$translations['cart.country']['text']}}</label>
                                <input type="text" id="company_country" name="company_country"
                                       class="checkout-form--input"

                                       value="{{old('company_country', $user ? $user['company_country'] : null)}}">
                                @error('company_country')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4 mt-8">
                            <a href="#collapseShipping" class="flex items-center collapsed" data-toggle="collapse"
                               aria-expanded="false">
                                <h3 class="h3 !mb-0">{{$translations['cart.different_shipping']['text']}}</h3>
                                <img src="{{asset('images/icons/expand_more_black_24dp.svg')}}" class="ml-4"
                                     alt="{{$translations['cart.different_shipping']['text']}}">
                            </a>
                        </div>
                        <div aria-expanded="false" id="collapseShipping"
                             class="collapse @if($errors->has('shipping_name') || $errors->has('shipping_street') || $errors->has('shipping_house_number') || $errors->has('shipping_city') || $errors->has('shipping_zip') || $errors->has('shipping_state') || $errors->has('shipping_country')) show @endif">
                            <div>
                                <label class="checkout-form--label"
                                       for="shipping_name">{{$translations['cart.fullname']['text']}}
                                </label>
                                <input type="text" name="shipping_name" id="shipping_name" class="checkout-form--input"

                                       value="{{old('shipping_name', $user ? $user['shipping_name'] : null)}}">
                                @error('shipping_name')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="flex flex-wrap -mx-4">
                                <div class="w-full lg:w-2/3 px-4">
                                    <label class="checkout-form--label"
                                           for="shipping_street">{{$translations['cart.street']['text']}}
                                    </label>
                                    <input type="text" id="shipping_street" name="shipping_street"
                                           class="checkout-form--input"

                                           value="{{old('shipping_street', $user ? $user['shipping_street'] : null)}}">
                                    @error('shipping_street')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-1/3 px-4">
                                    <label class="checkout-form--label"
                                           for="shipping_house_number">{{$translations['cart.house_number']['text']}}
                                    </label>
                                    <input type="text" id="shipping_house_number" name="shipping_house_number"
                                           class="checkout-form--input"

                                           value="{{old('shipping_house_number', $user ? $user['shipping_house_number'] : null)}}">
                                    @error('shipping_house_number')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-4">
                                <div class="w-full lg:w-1/2 px-4">
                                    <label class="checkout-form--label"
                                           for="shipping_city">{{$translations['cart.city']['text']}}
                                    </label>
                                    <input type="text" id="shipping_city" name="shipping_city"
                                           class="checkout-form--input"

                                           value="{{old('shipping_city', $user ? $user['shipping_city'] : null)}}">
                                    @error('shipping_city')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-1/2 px-4">
                                    <label class="checkout-form--label"
                                           for="shipping_zip">{{$translations['cart.zip']['text']}}
                                    </label>
                                    <input type="text" id="shipping_zip" name="shipping_zip"
                                           class="checkout-form--input"

                                           value="{{old('shipping_zip', $user ? $user['shipping_zip'] : null)}}">
                                    @error('shipping_zip')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="shipping_state" value="">
                            <div>
                                <label class="checkout-form--label"
                                       for="shipping_country">{{$translations['cart.country']['text']}}
                                </label>
                                <input type="hidden" name="shipping_country"
                                       value="{{old('shipping_country', $user ? $user['shipping_country'] : $shippingCountry['name'])}}">
                                <input type="text" id="shipping_country" name=""
                                       class="checkout-form--input"
                                       disabled
                                       readonly
                                       value="{{old('shipping_country', $user ? $user['shipping_country'] : $shippingCountry['name'])}}">
                                <small class="">{{$translations['cart.country_change']['text']}}
                                    <a href="{{route(locale() . '.cart.shipping')}}"
                                       class="text-secondary">{{$translations['cart.shipping_and_payment_title']['text']}}</a></small>
                                @error('country')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="checkout-form--label" for="notes">{{$translations['cart.notes']['text']}}
                            </label>
                            <textarea class="checkout-form--input" id="notes" name="notes"
                                      placeholder=""
                                      rows="4">{{old('notes')}}</textarea>
                        </div>
                        <div class="hidden lg:block mt-4">
                            <a href="{{route(locale() . '.cart.shipping')}}"
                               class="button button--ghost button--inline">{{$translations['cart.shipping_and_payment_title']['text']}}</a>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 px-4">
                        <div class="bg-grey p-8 lg:p-16">
                            <h2 class="h2">{{$translations['cart.title']['text']}}</h2>
                            @foreach($cart['items'] as $product)
                                <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                    <div class="w-1/2">
                                        <a class=""
                                           href="{{route(locale() . '.product.detail', [$product['meta']['category_slug'], $product['meta']['slug']])}}">{{$product['meta']['name']}}</a>
                                        @if(isset($product['multipack']))
                                            @foreach($product['multipack'] as $mp)
                                                <br>
                                                <small>{{$mp['count']}} x {{$mp['name']}}</small>
                                            @endforeach
                                        @endif
                                        @if($product['availableCount'] === 0 || $product['availableCount'] !== $product['count'])
                                            <br>
                                            <small
                                                class="text-danger">{{$translations['Na sklade nie je dostatočný počet kusov']['text']}}</small>
                                        @endif
                                    </div>
                                    <div class="w-1/4 flex flex-col items-end mt-4 lg:mt-0">
                                        {{$product['availableCount']}} {{$translations['ks']['text']}}.
                                    </div>
                                    <div class="w-1/4 flex flex-col items-end mt-4 lg:mt-0">
                                        <b>{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $product['price_formatted']) !!}</b>
                                    </div>
                                </div>
                            @endforeach
                            <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                <div class="w-1/2">
                                    {{$cart['shipping']['name']}}
                                </div>
                                <div class="w-1/2 flex flex-col items-end">
                                    <b>{{$cart['shipping']['price'] && ($cart['free_shipping'] >= 0  || !$cart['free_shipping_allowed']) ? $cart['shipping']['price_formatted'] : $translations['cart.free_price']['text']}}</b>
                                </div>
                            </div>
                            <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                <div class="w-1/2">
                                    {{$cart['payment']['name']}}
                                </div>
                                <div class="w-1/2 flex flex-col items-end">
                                    <b>{{$cart['payment']['price'] ? $cart['payment']['price_formatted'] : $translations['cart.free_price']['text']}}</b>
                                </div>
                            </div>
                            @if($cart['voucher'])
                                <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                    <div class="w-1/2">
                                        {{$cart['voucher']['code']}}
                                    </div>
                                    <div class="w-1/2 flex flex-col items-end">
                                        <b>- {!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $cart['discount']['value_formatted']) !!}</b>
                                    </div>
                                </div>
                            @endif
                            @if(!$cart['apply_vat_rate'])
                                <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                    <div class="w-1/2">
                                        {{$translations['cart.total']['text']}}
                                    </div>
                                    <div class="w-1/2 flex flex-col items-end">
                                        <b class="text-h3">{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $cart['price_formatted']) !!}</b>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                    <div class="w-1/2">
                                        {{$translations['cart.total_without_vat']['text']}}
                                    </div>
                                    <div class="w-1/2 flex flex-col items-end">
                                        <b>{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $cart['price_without_vat_formatted']) !!}</b>
                                    </div>
                                </div>
                                <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                    <div class="w-1/2">
                                        {{$translations['cart.vat']['text']}}
                                    </div>
                                    <div class="w-1/2 flex flex-col items-end">
                                        <b>{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $cart['vat_rate_formatted']) !!}</b>
                                    </div>
                                </div>
                                <div class="flex flex-wrap flex-row items-center border-grey border-b pb-8 text-small">
                                    <div class="w-1/2">
                                        {{$translations['cart.total']['text']}}
                                    </div>
                                    <div class="w-1/2 flex flex-col items-end">
                                        <b class="text-h3">{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $cart['price_formatted']) !!}</b>
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" name="toc" value="1">
                            <div class="flex gap-3 text-small border-t mt-8 pt-8 mb-4">
                                <input type="hidden" name="newsletter" value="0">
                                <input class="flex-shrink-0 !w-8 !h-8" id="newsletter"
                                       name="newsletter" type="checkbox" value="1"
                                       @if(old('newsletter', false)) checked="checked" @endif>
                                <label class=""
                                       for="newsletter">{{$translations['cart.newsletter_accept']['text']}}</label>
                            </div>
                            <div class="flex gap-3 text-small mb-4">
                                <input type="hidden" name="heureka_allowed" value="1">
                                <input class="flex-shrink-0 !w-8 !h-8" id="heureka_allowed"
                                       name="heureka_allowed" type="checkbox" value="0"
                                       @if(!old('heureka_allowed', true)) checked="checked" @endif>
                                <label class=""
                                       for="heureka_allowed">{{$translations['cart.heureka_accept']['text']}}</label>
                            </div>
                            <div class="text-right">
                                <button type="submit"
                                        class="button button--primary button--inline button--lg"
                                        onclick="document.getElementById('order-loading-wrapper').style.display = 'flex';">
                                    {{$translations['cart.cta_order']['text']}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('#checkout-form input[type=text]').on('keyup', function () {
                localStorage.setItem('checkout_' + $(this).attr('id'), $(this).val())
            })

            $('#checkout-form textarea').on('keyup', function () {
                localStorage.setItem('checkout_' + $(this).attr('id'), $(this).val())
            })

            $('#checkout-form input[type=text]').each(function () {
                if (!$(this).val()) {
                    $(this).val(localStorage.getItem('checkout_' + $(this).attr('id')));
                }
            })

            $('#checkout-form textarea').each(function () {
                if (!$(this).val()) {
                    $(this).val(localStorage.getItem('checkout_' + $(this).attr('id')));
                }
            })
        })
    </script>
@endsection
