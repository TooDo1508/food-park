<div class="tab-pane fade show active" id="paypal-settings" role="tabpanel" aria-labelledby="home-tab4">
    <div class="card">
        <div class="card-body border">
            <form action="{{ route('admin.payment-setting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="">Paypal status</label>
                    <select name="paypal_status" id="" class="form-control select2">
                        <option @selected($paypalSetting['paypal_status'] === 1) value="1">Active</option>
                        <option @selected($paypalSetting['paypal_status'] === 0) value="0">InActive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Paypal Account Mode</label>
                    <select name="paypal_acount_mode" id="" class="form-control select2">
                        <option @selected($paypalSetting['paypal_acount_mode'] === 'sandbox') value="sandbox">Sandbox</option>
                        <option @selected($paypalSetting['paypal_acount_mode'] === 'live') value="live">Live</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Paypal Country Name</label>
                    <select name="paypal_country" id="" class="form-control select2">
                        <option value="">Select</option>
                        @foreach (config('country_list') as $key => $country)
                            <option @selected($paypalSetting['paypal_country'] === $key) value="{{ $key }}">{{ $country }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Paypal Currency Name</label>
                    <select name="paypal_currency" id="" class="form-control select2">
                        <option value="">Select Currency</option>
                        @foreach (config('currencys.currency_list') as $currency)
                            <option @selected(config('settings.site_default_currency') === $currency) value="{{ $currency }}">
                                {{ $currency }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Currency Rate (Per {{ config('settings.site_default_currency') }})</label>
                    <input type="text" class="form-control" name="paypal_rate"
                        value="{{ $paypalSetting['paypal_rate'] }}">
                </div>

                <div class="form-group">
                    <label for="">Paypal Client Id</label>
                    <input type="text" class="form-control" name="paypal_api_key"
                        value="{{ $paypalSetting['paypal_api_key'] }}">
                </div>

                <div class="form-group">
                    <label for="">Paypal Secret Key</label>
                    <input type="text" class="form-control" name="paypal_secret_key"
                        value="{{ $paypalSetting['paypal_secret_key'] }}">
                </div>

                <div class="form-group">
                    <label for="">Image</label>
                    <div class="image-preview" id="image-preview">
                        <label for="image-upload" id="image-label">Choose File</label>
                        <input type="file" name="paypal_logo" id="image-upload" />
                    </div>

                </div>
                <button type="submit" class="btn btn-primary form=con">Save</button>
            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            $('.image-preview').css({
                'background-image': 'url({{ asset($paypalSetting['paypal_logo']) }})',
                'background-size': 'cover',
                'background-position': 'center center',
            })
        })
    </script>
@endpush
