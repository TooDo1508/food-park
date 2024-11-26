<div class="tab-pane fade show active" id="paypal-settings" role="tabpanel"
aria-labelledby="home-tab4">
<div class="card">
    <div class="card-body border">
        <form action="{{ route('admin.general-setting.index') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="">Paypal status</label>
                <select name="site_default_currency" id=""
                    class="form-control select2">
                    <option value="1">Active</option>
                    <option value="0">InActive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Paypal Account Mode</label>
                <select name="site_default_currency" id=""
                    class="form-control select2">
                    <option value="sandbox">Sandbox</option>
                    <option value="live">Live</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Paypal Country Name</label>
                <select name="site_default_currency" id=""
                    class="form-control select2">
                    <option value="sandbox">Sandbox</option>
                    <option value="live">Live</option>
                </select>
            </div>

            <div class="form-group">
                <label for="">Paypal Currency Name</label>
                <select name="site_default_currency" id=""
                    class="form-control select2">
                    <option value="">Select Currency</option>
                    @foreach (config('currencys.currency_list') as $currency)
                        <option @selected(config('settings.site_default_currency') === $currency) value="{{ $currency }}">
                            {{ $currency }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="">Currency Rate</label>
                <input type="text" class="form-control" name=""
                    value="">
            </div>

            <div class="form-group">
                <label for="">Paypal Client Id</label>
                <input type="text" class="form-control" name=""
                    value="">
            </div>

            <div class="form-group">
                <label for="">Paypal Secret Key</label>
                <input type="text" class="form-control" name=""
                    value="">
            </div>

            <div class="form-group">
                <label for="">Image</label>
                <div class="image-preview" id="image-preview">
                    <label for="image-update" id="image-label">Choose File</label>
                    <input type="file" name="image" id="image-update"/>
                </div>

            </div>
            <button type="submit" class="btn btn-primary form=con">Save</button>
        </form>
    </div>
</div>
</div>
