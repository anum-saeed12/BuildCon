<script type="text/javascript">
    function itemSelect(ele) {
        let target = ele.data('target'), href = ele.data('href'), item_id = ele.val(), spinner = ele.data('spinner'), brands;
        $.ajax({
            dataType: 'json',
            url: `${href}?item=${item_id}`,
            beforeSend: function() {
                $(spinner).text('Loading brands...');
            },
            success: function(data) {
                $(spinner).html('');
                brands += '<option>Select Brand</option>';
                $.each(data, function(index, json){
                    brands += `<option value="${json.id}">${json.brand_name}</option>`;
                })
                $(target).html(brands);
            }
        });
    }
    function fetchPrice(ele) {
        let unit = $(ele.data('unit')),
            rate = $(ele.data('rate')),
            quantity = $(ele.data('quantity')),
            amount = $(ele.data('amount')),
            href = ele.data('href'),
            item_id = $(ele.data('item')).val(),
            brand_id = ele.val(),
            spinner = ele.data('spinner');
        $.ajax({
            dataType: 'json',
            url: `${href}?item=${item_id}&brand=${brand_id}`,
            beforeSend: function() {
                $(spinner).text('Loading prices...');
            },
            success: function(data) {
                $(spinner).html('');
                unit.val(data.unit);
                rate.val(data.price);
                quantity.val(0);
                amount.val(0);
                calculateTotal();
            }
        });
    }
    function categorySelect(ele) {
        let target = ele.data('target'), href = ele.data('href'), item_id = ele.val(), spinner = ele.data('spinner'), brands;
        $.ajax({
            dataType: 'json',
            url: `${href}?category=${item_id}`,
            beforeSend: function() {
                $(spinner).text('Loading items...');
            },
            success: function(data) {
                $(spinner).html('');
                brands += '<option>Select Item</option>';
                $.each(data, function(index, json){
                    brands += `<option value="${json.item_name}">${json.item_name}</option>`;
                })
                $(target).html(brands);
            }
        });
    }
    function calculateTotal() {
        var total = 0, val;
        $('.total').each(function() {
            val = $(this).val()
            val = isNaN(val) || $.trim(val) === "" ? 0 : parseFloat(val);
            total += val;
        });
        $('#total').val(Math.round(total));
        applyDiscount();
    }
</script>
