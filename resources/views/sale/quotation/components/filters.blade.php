<div class="omni-filters col ml-2 mr-2">
    <h2>Filters</h2>
    <form action="{{ route('customerquotation.list.sale') }}" method="get">
        <input type="hidden" name="filters" value="go"/>
        <div class="row">
            <div class="col-md">
                <label for="sales_person" class="normal">Sales Person</label>
                <select name="sales_person" class="form-control" id="sales_person">
                    <option selected="selected" value>Select</option>
                    @foreach($sales_people as $person)
                        <option value="{{ $person->id }}"{!! $request->sales_person == $person->id ? ' selected':'' !!}>{{ ucfirst( $person->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <label for="customer_id" class="normal">Customer</label>
                <select name="customer_id" class="form-control" id="customer_id">
                    <option selected="selected" value>Select</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"{!! $request->customer_id == $customer->id ? ' selected':'' !!}>{{ ucfirst( $customer->customer_name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <label for="project" class="normal">Project</label>
                <input type="text" name="project" class="form-control" id="project"
                       placeholder="Project name"
                       value="{{ $request->project }}">
            </div>
            <div class="col-md">
                <label for="from" class="normal">From</label>
                <input type="date" name="from" class="form-control" id="from"
                       value="{{ $request->from }}">
            </div>
            <div class="col-md">
                <label for="to" class="normal">To</label>
                <input type="date" name="to" class="form-control" id="to"
                       value="{{ $request->to }}">
            </div>
            <div class="col-md">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <a href="{{ $reset_url }}" class="btn btn-default btn-sm btn-block">Reset</a>
                </div>
            </div>
            <div class="col-md">
                <label class="normal">&nbsp;</label>
                <div class="input-group input-group-sm">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Apply filters</button>
                </div>
            </div>
        </div>
    </form>
</div>
